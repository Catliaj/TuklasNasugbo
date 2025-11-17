# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#   * Rearrange models' order
#   * Make sure each model has one field with primary_key=True
#   * Make sure each ForeignKey and OneToOneField has `on_delete` set to the desired behavior
#   * Remove `managed = False` lines if you wish to allow Django to create, modify, and delete the table
# Feel free to rename the models, but don't rename db_table values or field names.
from django.db import models


class ActivityLog(models.Model):
    log_id = models.AutoField(primary_key=True)
    user = models.ForeignKey('Users', models.DO_NOTHING)
    activity_type = models.CharField(max_length=100)
    entity_type = models.CharField(max_length=100)
    entity_id = models.IntegerField(blank=True, null=True)
    description = models.TextField()
    ip_address = models.CharField(max_length=45)
    user_agent = models.TextField()
    created_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'activity_log'


class AuthGroup(models.Model):
    name = models.CharField(unique=True, max_length=150)

    class Meta:
        managed = False
        db_table = 'auth_group'


class AuthGroupPermissions(models.Model):
    id = models.BigAutoField(primary_key=True)
    group = models.ForeignKey(AuthGroup, models.DO_NOTHING)
    permission = models.ForeignKey('AuthPermission', models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'auth_group_permissions'
        unique_together = (('group', 'permission'),)


class AuthPermission(models.Model):
    name = models.CharField(max_length=255)
    content_type = models.ForeignKey('DjangoContentType', models.DO_NOTHING)
    codename = models.CharField(max_length=100)

    class Meta:
        managed = False
        db_table = 'auth_permission'
        unique_together = (('content_type', 'codename'),)


class AuthUser(models.Model):
    password = models.CharField(max_length=128)
    last_login = models.DateTimeField(blank=True, null=True)
    is_superuser = models.IntegerField()
    username = models.CharField(unique=True, max_length=150)
    first_name = models.CharField(max_length=150)
    last_name = models.CharField(max_length=150)
    email = models.CharField(max_length=254)
    is_staff = models.IntegerField()
    is_active = models.IntegerField()
    date_joined = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'auth_user'


class AuthUserGroups(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(AuthUser, models.DO_NOTHING)
    group = models.ForeignKey(AuthGroup, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'auth_user_groups'
        unique_together = (('user', 'group'),)


class AuthUserUserPermissions(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(AuthUser, models.DO_NOTHING)
    permission = models.ForeignKey(AuthPermission, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'auth_user_user_permissions'
        unique_together = (('user', 'permission'),)


class Bookings(models.Model):
    booking_id = models.AutoField(primary_key=True)
    spot = models.ForeignKey('TouristSpots', models.DO_NOTHING, blank=True, null=True)
    customer = models.ForeignKey('Customers', models.DO_NOTHING, blank=True, null=True)
    booking_date = models.DateTimeField(blank=True, null=True)
    visit_date = models.DateField(blank=True, null=True)
    visit_time = models.TimeField(blank=True, null=True)
    num_adults = models.IntegerField(blank=True, null=True)
    num_children = models.IntegerField(blank=True, null=True)
    num_seniors = models.IntegerField(blank=True, null=True)
    total_guests = models.IntegerField(blank=True, null=True)
    price_per_person = models.DecimalField(max_digits=10, decimal_places=2, blank=True, null=True)
    subtotal = models.DecimalField(max_digits=15, decimal_places=2, blank=True, null=True)
    discount_amount = models.DecimalField(max_digits=15, decimal_places=2, blank=True, null=True)
    tax_amount = models.DecimalField(max_digits=15, decimal_places=2, blank=True, null=True)
    total_price = models.DecimalField(max_digits=15, decimal_places=2, blank=True, null=True)
    booking_status = models.CharField(max_length=9, blank=True, null=True)
    payment_status = models.CharField(max_length=8, blank=True, null=True)
    special_requests = models.TextField(blank=True, null=True)
    cancellation_reason = models.TextField(blank=True, null=True)
    internal_notes = models.TextField(blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)
    confirmed_at = models.DateTimeField(blank=True, null=True)
    cancelled_at = models.DateTimeField(blank=True, null=True)
    completed_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'bookings'


class Businesses(models.Model):
    business_id = models.AutoField(primary_key=True)
    user = models.ForeignKey('Users', models.DO_NOTHING)
    business_name = models.CharField(max_length=100)
    contact_email = models.CharField(max_length=100)
    contact_phone = models.CharField(max_length=15)
    business_address = models.TextField()
    logo_url = models.TextField(blank=True, null=True)
    status = models.CharField(max_length=8)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'businesses'


class Customers(models.Model):
    customer_id = models.AutoField(primary_key=True)
    user = models.ForeignKey('Users', models.DO_NOTHING)
    type = models.CharField(max_length=50)
    phone = models.CharField(max_length=15)
    address = models.TextField()
    date_of_birth = models.DateField()
    emergency_contact = models.CharField(max_length=100)
    emergency_phone = models.CharField(max_length=15)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'customers'


class DjangoAdminLog(models.Model):
    action_time = models.DateTimeField()
    object_id = models.TextField(blank=True, null=True)
    object_repr = models.CharField(max_length=200)
    action_flag = models.PositiveSmallIntegerField()
    change_message = models.TextField()
    content_type = models.ForeignKey('DjangoContentType', models.DO_NOTHING, blank=True, null=True)
    user = models.ForeignKey(AuthUser, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'django_admin_log'


class DjangoContentType(models.Model):
    app_label = models.CharField(max_length=100)
    model = models.CharField(max_length=100)

    class Meta:
        managed = False
        db_table = 'django_content_type'
        unique_together = (('app_label', 'model'),)


class DjangoMigrations(models.Model):
    id = models.BigAutoField(primary_key=True)
    app = models.CharField(max_length=255)
    name = models.CharField(max_length=255)
    applied = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'django_migrations'


class DjangoSession(models.Model):
    session_key = models.CharField(primary_key=True, max_length=40)
    session_data = models.TextField()
    expire_date = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'django_session'


class Itinerary(models.Model):
    itinerary_id = models.AutoField(primary_key=True)
    preference = models.ForeignKey('UserPreferences', models.DO_NOTHING, blank=True, null=True)
    spot = models.ForeignKey('TouristSpots', models.DO_NOTHING, blank=True, null=True)
    description = models.TextField(blank=True, null=True)
    day = models.IntegerField()
    budget = models.DecimalField(max_digits=15, decimal_places=2, blank=True, null=True)
    adults = models.IntegerField()
    children = models.IntegerField()
    seniors = models.IntegerField()
    trip_title = models.CharField(max_length=255, blank=True, null=True)
    start_date = models.DateField(blank=True, null=True)
    end_date = models.DateField(blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'itinerary'


class Migrations(models.Model):
    id = models.BigAutoField(primary_key=True)
    version = models.CharField(max_length=255)
    class_field = models.CharField(db_column='class', max_length=255)  # Field renamed because it was a Python reserved word.
    group = models.CharField(max_length=255)
    namespace = models.CharField(max_length=255)
    time = models.IntegerField()
    batch = models.PositiveIntegerField()

    class Meta:
        managed = False
        db_table = 'migrations'


class Payments(models.Model):
    payment_id = models.AutoField(primary_key=True)
    booking = models.ForeignKey(Bookings, models.DO_NOTHING)
    amount = models.DecimalField(max_digits=15, decimal_places=2)
    payment_method = models.CharField(max_length=13)
    payment_date = models.DateTimeField()
    transaction_id = models.CharField(max_length=100)
    reference_number = models.CharField(max_length=100)
    status = models.CharField(max_length=9)
    notes = models.TextField(blank=True, null=True)
    processed_by = models.PositiveIntegerField()
    created_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'payments'


class RevenueAnalytics(models.Model):
    analytics_id = models.AutoField(primary_key=True)
    business = models.ForeignKey(Businesses, models.DO_NOTHING)
    spot = models.ForeignKey('TouristSpots', models.DO_NOTHING)
    by_date = models.DateField(db_column='by_Date')  # Field name made lowercase.
    total_bookings = models.IntegerField()
    confirmed_bookings = models.IntegerField()
    cancelled_bookings = models.IntegerField()
    total_visitors = models.IntegerField()
    gross_revenue = models.DecimalField(max_digits=15, decimal_places=2)
    discounts = models.DecimalField(max_digits=15, decimal_places=2)
    refunds = models.DecimalField(max_digits=15, decimal_places=2)
    net_revenue = models.DecimalField(max_digits=15, decimal_places=2)
    avg_booking_value = models.DecimalField(max_digits=10, decimal_places=2)
    avg_party_size = models.DecimalField(max_digits=10, decimal_places=2)
    created_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'revenue_analytics'


class ReviewFeedback(models.Model):
    review_id = models.AutoField(primary_key=True)
    booking = models.ForeignKey(Bookings, models.DO_NOTHING)
    spot = models.ForeignKey('TouristSpots', models.DO_NOTHING)
    customer = models.ForeignKey(Customers, models.DO_NOTHING)
    business = models.ForeignKey(Businesses, models.DO_NOTHING)
    rating = models.IntegerField()
    title = models.CharField(max_length=150)
    comment = models.TextField()
    cleanliness_rating = models.IntegerField()
    staff_rating = models.IntegerField()
    value_rating = models.IntegerField()
    location_rating = models.IntegerField()
    status = models.CharField(max_length=8)
    is_verified_visit = models.IntegerField()
    owner_response = models.TextField(blank=True, null=True)
    response_date = models.DateTimeField(blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'review_feedback'


class SpotAvailability(models.Model):
    availability_id = models.AutoField(primary_key=True)
    spot = models.ForeignKey('TouristSpots', models.DO_NOTHING)
    available_date = models.DateField()
    total_capacity = models.IntegerField()
    booked_capacity = models.IntegerField()
    available_capacity = models.IntegerField()
    is_available = models.IntegerField()
    reason_unavailable = models.CharField(max_length=255, blank=True, null=True)
    special_price = models.DecimalField(max_digits=10, decimal_places=2, blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'spot_availability'


class SpotGallery(models.Model):
    image_id = models.AutoField(primary_key=True)
    spot = models.ForeignKey('TouristSpots', models.DO_NOTHING)
    image = models.TextField()

    class Meta:
        managed = False
        db_table = 'spot_gallery'


class TouristSpots(models.Model):
    spot_id = models.AutoField(primary_key=True)
    business = models.ForeignKey(Businesses, models.DO_NOTHING)
    spot_name = models.CharField(max_length=100)
    description = models.TextField()
    latitude = models.DecimalField(max_digits=10, decimal_places=8)
    longitude = models.DecimalField(max_digits=11, decimal_places=8)
    category = models.CharField(max_length=12)
    location = models.CharField(max_length=255)
    capacity = models.IntegerField()
    opening_time = models.TimeField()
    closing_time = models.TimeField()
    operating_days = models.CharField(max_length=100)
    status = models.CharField(max_length=9)
    price_per_person = models.DecimalField(max_digits=10, decimal_places=2)
    child_price = models.DecimalField(max_digits=10, decimal_places=2)
    senior_price = models.DecimalField(max_digits=10, decimal_places=2)
    group_discount_percent = models.DecimalField(max_digits=5, decimal_places=2)
    primary_image = models.TextField(blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)
    status_reason = models.TextField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'tourist_spots'


class UserPreferences(models.Model):
    preference_id = models.AutoField(primary_key=True)
    user = models.ForeignKey('Users', models.DO_NOTHING)
    category = models.CharField(max_length=150, blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'user_preferences'


class UserVisitHistory(models.Model):
    history_id = models.AutoField(primary_key=True)
    user = models.ForeignKey(Customers, models.DO_NOTHING)
    spot = models.ForeignKey(TouristSpots, models.DO_NOTHING)
    liked = models.IntegerField()
    last_visited_at = models.DateTimeField(blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'user_visit_history'


class Users(models.Model):
    userid = models.AutoField(db_column='UserID', primary_key=True)  # Field name made lowercase.
    firstname = models.CharField(db_column='FirstName', max_length=50)  # Field name made lowercase.
    middlename = models.CharField(db_column='MiddleName', max_length=50, blank=True, null=True)  # Field name made lowercase.
    lastname = models.CharField(db_column='LastName', max_length=50)  # Field name made lowercase.
    email = models.CharField(unique=True, max_length=100)
    password = models.CharField(max_length=255)
    role = models.CharField(max_length=10)
    lastlogin = models.DateTimeField(db_column='LastLogin', blank=True, null=True)  # Field name made lowercase.
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'users'


class VisitorCheckins(models.Model):
    checkin_id = models.AutoField(primary_key=True)
    customer = models.ForeignKey(Customers, models.DO_NOTHING)
    booking = models.ForeignKey(Bookings, models.DO_NOTHING)
    checkin_time = models.DateTimeField()
    checkout_time = models.DateTimeField(blank=True, null=True)
    actual_visitors = models.IntegerField()
    is_walkin = models.IntegerField()
    notes = models.TextField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'visitor_checkins'
