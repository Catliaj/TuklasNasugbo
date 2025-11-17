from django.http import JsonResponse
from django.views.decorators.http import require_GET
from django.core.cache import cache
from .models import TouristSpots, Itinerary
from django.utils import timezone
import random
import google.generativeai as genai
from django.db.models import Q
from concurrent.futures import ThreadPoolExecutor, as_completed
import hashlib

# -----------------------------
# AI setup
# -----------------------------
api_key = "AIzaSyCPgoCu1dHDsgrFEfrnvavV8V-BHpzxbTY"
genai.configure(api_key=api_key)
ai_model = genai.GenerativeModel("gemini-2.5-flash")


def generate_ai_description_cached(spot_name, category, lat, lng):
    """Generate AI description with caching to avoid redundant API calls"""
    # Create a cache key based on spot details
    cache_key = f"ai_desc_{hashlib.md5(f'{spot_name}{category}{lat}{lng}'.encode()).hexdigest()}"
    
    # Check if description already exists in cache (24 hour expiry)
    cached_desc = cache.get(cache_key)
    if cached_desc:
        return cached_desc
    
    prompt = f"""
    Generate a short, friendly description (1-2 sentences, ~50 words) with emojis, 
    no hashtags, for visiting {spot_name}, a {category} destination.
    Mention key activities and tips briefly.
    Use coordinates {lat}, {lng} as context.
    """
    try:
        response = ai_model.generate_content(prompt)
        description = response.text.strip()
        # Cache for 24 hours (86400 seconds)
        cache.set(cache_key, description, 86400)
        return description
    except Exception as e:
        return f"Discover the beauty of {spot_name} 🌟"


def generate_descriptions_batch(spots_data):
    """Generate AI descriptions in parallel for multiple spots"""
    descriptions = {}
    
    # Use ThreadPoolExecutor to generate descriptions in parallel
    with ThreadPoolExecutor(max_workers=5) as executor:
        future_to_spot = {
            executor.submit(
                generate_ai_description_cached,
                spot['name'],
                spot['category'],
                spot['lat'],
                spot['lng']
            ): spot['spot_id']
            for spot in spots_data
        }
        
        for future in as_completed(future_to_spot):
            spot_id = future_to_spot[future]
            try:
                descriptions[spot_id] = future.result()
            except Exception as e:
                descriptions[spot_id] = "Explore this amazing destination! 🌟"
    
    return descriptions


@require_GET
def recommend_itinerary(request):
    """
    Query params (all required):
      days, budget, adults, children, seniors, preference (comma-separated categories)
    """
    try:
        days = int(request.GET['days'])
        budget = float(request.GET['budget'])
        adults = int(request.GET['adults'])
        children = int(request.GET['children'])
        seniors = int(request.GET['seniors'])
    except (KeyError, ValueError):
        return JsonResponse({'error': 'Missing or invalid required parameters'}, status=400)

    # -----------------------------
    # Preferences (case-insensitive)
    # -----------------------------
    prefs = request.GET.get('preference', '')
    pref_list = [p.strip().lower() for p in prefs.split(',') if p.strip()]

    # Optimize DB query - only select needed fields
    spots_qs = TouristSpots.objects.only(
        'spot_id', 'spot_name', 'category', 'price_per_person',
        'child_price', 'senior_price', 'latitude', 'longitude', 'location'
    )
    
    if pref_list:
        query = Q()
        for p in pref_list:
            query |= Q(category__iexact=p)
        spots_qs = spots_qs.filter(query)

    # Convert to list once to avoid multiple DB queries
    all_spots = list(spots_qs)
    
    if not all_spots:
        return JsonResponse({'error': 'No spots found matching preferences'}, status=404)

    total_people = max(adults + children + seniors, 1)

    # -----------------------------
    # Score spots (Optimized)
    # -----------------------------
    scored = []
    for s in all_spots:
        score = 0
        if s.category and s.category.lower() in pref_list:
            score += 2

        price_per_person = float(s.price_per_person or 0)
        child_price = float(s.child_price or 0)
        senior_price = float(s.senior_price or 0)
        total_cost = adults * price_per_person + children * child_price + seniors * senior_price

        if total_cost <= budget:
            score += 1

        scored.append({
            'score': score,
            'spot': s,
            'total_cost': total_cost,
            'price_per_person': price_per_person,
            'child_price': child_price,
            'senior_price': senior_price,
        })

    # Sort by score descending
    scored.sort(key=lambda x: (x['score'], random.random()), reverse=True)

    # -----------------------------
    # Build itinerary WITHOUT AI first
    # -----------------------------
    itinerary = []
    remaining_budget = budget
    spots_to_describe = []  # Collect spots that need AI descriptions

    # Track which spot_ids have already been added across all days
    selected_spot_ids = set()
    total_unique_spots = len(all_spots)
    all_spots_added = False

    for day_num in range(1, days + 1):
        if all_spots_added:
            break

        day_spots = []
        day_budget = remaining_budget / (days - day_num + 1)

        # Shuffle for variety
        random.shuffle(scored)

        for item in scored:
            spot = item['spot']
            total_cost = item['total_cost']

            # Avoid duplicates across all days
            if spot.spot_id in selected_spot_ids:
                continue

            if total_cost > day_budget:
                continue

            spot_data = {
                'spot_id': spot.spot_id,
                'name': spot.spot_name,
                'category': spot.category,
                'description': '',  # Will be filled later
                'price_per_person': item['price_per_person'],
                'child_price': item['child_price'],
                'senior_price': item['senior_price'],
                'total_cost_for_day': total_cost,
                'lat': float(spot.latitude or 0),
                'lng': float(spot.longitude or 0),
                'location': spot.location,
            }

            day_spots.append(spot_data)
            spots_to_describe.append(spot_data)
            selected_spot_ids.add(spot.spot_id)

            day_budget -= total_cost
            remaining_budget -= total_cost

            # If we've used all unique spots available, stop
            if len(selected_spot_ids) >= total_unique_spots:
                all_spots_added = True
                break

        if not day_spots:
            day_spots.append({
                'spot_id': None,
                'name': 'No spots available',
                'category': '',
                'description': 'Budget constraints prevented adding spots for this day.',
                'price_per_person': 0,
                'child_price': 0,
                'senior_price': 0,
                'total_cost_for_day': 0,
                'lat': 0,
                'lng': 0,
                'location': '',
            })

        itinerary.append({'day': day_num, 'spots': day_spots})

    # -----------------------------
    # Generate AI descriptions in PARALLEL
    # -----------------------------
    if spots_to_describe:
        descriptions = generate_descriptions_batch(spots_to_describe)

        # Update descriptions in itinerary
        for day in itinerary:
            for spot in day['spots']:
                if spot['spot_id'] and spot['spot_id'] in descriptions:
                    spot['description'] = descriptions[spot['spot_id']]

    # -----------------------------
    # Build response
    # -----------------------------
    response = {'itinerary': itinerary, 'remaining_budget': remaining_budget}

    if len(itinerary) < days:
        response['warning'] = "Only some days could be generated due to budget constraints."

    # If all unique spots were used across generated days, add a note
    if all_spots_added:
        response['note'] = "All available spots have been added — limited spots because all spots in the database are already included."

    # Optionally persist the generated itinerary to the DB.
    # Save when client includes `save=1` or provides a `trip_title` parameter.
    should_save = request.GET.get('save') == '1' or bool(request.GET.get('trip_title'))
    if should_save:
        trip_title = request.GET.get('trip_title') or ''
        start_date = request.GET.get('start_date') or None
        end_date = request.GET.get('end_date') or None
        # Prevent duplicate whole-itinerary saves: if an itinerary with the same
        # trip_title and start_date (or matching start_date) already exists,
        # avoid creating duplicates and inform the client.
        try:
            dup_exists = False
            if trip_title and start_date:
                dup_exists = Itinerary.objects.filter(trip_title=trip_title, start_date=start_date).exists()
            elif trip_title:
                dup_exists = Itinerary.objects.filter(trip_title=trip_title).exists()
            elif start_date:
                dup_exists = Itinerary.objects.filter(start_date=start_date).exists()

            if dup_exists:
                response['saved'] = False
                response['saved_message'] = 'An itinerary with the same trip title or start date already exists. No new items were added.'
                return JsonResponse(response)

            created_objs = []
            from django.db import transaction
            try:
                with transaction.atomic():
                    for day in itinerary:
                        day_num = day.get('day')
                        for spot in day.get('spots', []):
                            spot_id = spot.get('spot_id')
                            if not spot_id:
                                continue

                            # Row-level duplicate prevention: skip if this exact
                            # spot/day/trip already exists in DB
                            if Itinerary.objects.filter(
                                spot_id=spot_id,
                                day=day_num,
                                trip_title=trip_title,
                                start_date=start_date
                            ).exists():
                                continue

                            obj = Itinerary(
                                spot_id=spot_id,
                                day=day_num,
                                budget=budget,
                                adults=adults,
                                children=children,
                                seniors=seniors,
                                trip_title=trip_title,
                                start_date=start_date or None,
                                end_date=end_date or None,
                                created_at=timezone.now(),
                            )
                            obj.save()
                            created_objs.append(obj.itinerary_id)
                response['saved'] = True
                response['saved_count'] = len(created_objs)
                response['saved_ids'] = created_objs
            except Exception as e:
                response['saved'] = False
                response['saved_error'] = str(e)
        except Exception as e:
            # Catch any unexpected DB-check errors and return a sensible message
            response['saved'] = False
            response['saved_error'] = str(e)

    return JsonResponse(response)


# -----------------------------
# ALTERNATIVE: Generate without AI (FASTEST)
# -----------------------------
@require_GET
def recommend_itinerary_fast(request):
    """
    Ultra-fast version without AI descriptions
    Use this for instant results, generate AI descriptions on frontend if needed
    """
    try:
        days = int(request.GET['days'])
        budget = float(request.GET['budget'])
        adults = int(request.GET['adults'])
        children = int(request.GET['children'])
        seniors = int(request.GET['seniors'])
    except (KeyError, ValueError):
        return JsonResponse({'error': 'Missing or invalid required parameters'}, status=400)

    prefs = request.GET.get('preference', '')
    pref_list = [p.strip().lower() for p in prefs.split(',') if p.strip()]

    spots_qs = TouristSpots.objects.only(
        'spot_id', 'spot_name', 'category', 'price_per_person',
        'child_price', 'senior_price', 'latitude', 'longitude', 'location'
    )
    
    if pref_list:
        query = Q()
        for p in pref_list:
            query |= Q(category__iexact=p)
        spots_qs = spots_qs.filter(query)

    all_spots = list(spots_qs)
    
    if not all_spots:
        return JsonResponse({'error': 'No spots found matching preferences'}, status=404)

    scored = []
    for s in all_spots:
        score = 0
        if s.category and s.category.lower() in pref_list:
            score += 2

        price_per_person = float(s.price_per_person or 0)
        child_price = float(s.child_price or 0)
        senior_price = float(s.senior_price or 0)
        total_cost = adults * price_per_person + children * child_price + seniors * senior_price

        if total_cost <= budget:
            score += 1

        scored.append({
            'score': score,
            'spot': s,
            'total_cost': total_cost,
            'price_per_person': price_per_person,
            'child_price': child_price,
            'senior_price': senior_price,
        })

    scored.sort(key=lambda x: (x['score'], random.random()), reverse=True)

    itinerary = []
    remaining_budget = budget

    for day_num in range(1, days + 1):
        day_spots = []
        day_budget = remaining_budget / (days - day_num + 1)

        random.shuffle(scored)

        for item in scored:
            spot = item['spot']
            total_cost = item['total_cost']

            if spot.spot_id in [s['spot_id'] for s in day_spots]:
                continue

            if total_cost > day_budget:
                continue

            # Use generic description instead of AI
            description = f"Visit {spot.spot_name}, a wonderful {spot.category} destination in {spot.location}. Perfect for creating memorable experiences! 🌟"

            day_spots.append({
                'spot_id': spot.spot_id,
                'name': spot.spot_name,
                'category': spot.category,
                'description': description,
                'price_per_person': item['price_per_person'],
                'child_price': item['child_price'],
                'senior_price': item['senior_price'],
                'total_cost_for_day': total_cost,
                'lat': float(spot.latitude or 0),
                'lng': float(spot.longitude or 0),
                'location': spot.location,
            })

            day_budget -= total_cost
            remaining_budget -= total_cost

        if not day_spots:
            day_spots.append({
                'spot_id': None,
                'name': 'No spots available',
                'category': '',
                'description': 'Budget constraints prevented adding spots for this day.',
                'price_per_person': 0,
                'child_price': 0,
                'senior_price': 0,
                'total_cost_for_day': 0,
                'lat': 0,
                'lng': 0,
                'location': '',
            })

        itinerary.append({'day': day_num, 'spots': day_spots})

    response = {'itinerary': itinerary, 'remaining_budget': remaining_budget}
    return JsonResponse(response)