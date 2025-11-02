from django.shortcuts import render

from django.http import JsonResponse
from django.views.decorators.http import require_GET
from .models import TouristSpots
import random

@require_GET
def recommend_itinerary(request):
    """
    Expected query params:
      user_id, days, budget, preference (comma-separated categories)
    """
    try:
        days = int(request.GET.get('days', 1))
        budget = float(request.GET.get('budget', 0))
    except ValueError:
        return JsonResponse({'error': 'invalid days or budget'}, status=400)

    prefs = request.GET.get('preference', '')
    pref_list = [p.strip().lower() for p in prefs.split(',') if p.strip()]

    # Load spots from DB
    spots_qs = TouristSpot.objects.all()

    # Basic scoring: +2 if category in preference, +1 if cost fits per-day budget
    per_day_budget = budget / max(days, 1)
    scored = []
    for s in spots_qs:
        score = 0
        if s.category and s.category.lower() in pref_list:
            score += 2
        if s.cost <= per_day_budget:
            score += 1
        scored.append((score, s))

    # sort by score desc then randomize ties
    scored.sort(key=lambda x: (x[0], random.random()), reverse=True)

    itinerary = []
    idx = 0
    for day in range(1, days + 1):
        if idx >= len(scored):
            break
        spot = scored[idx][1]
        itinerary.append({
            'day': day,
            'spot_id': spot.spot_id,
            'name': spot.name,
            'category': spot.category,
            'description': spot.description,
            'cost': spot.cost,
            'lat': spot.latitude,
            'lng': spot.longitude,
            'address': spot.address
        })
        idx += 1

    return JsonResponse({'itinerary': itinerary})

