from django.urls import path
from . import views

urlpatterns = [
    path('recommend/', views.recommend_itinerary, name='recommend_itinerary'),
]
