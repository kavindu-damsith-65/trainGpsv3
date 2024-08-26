from django.urls import path

from . import views

urlpatterns = [
    path('eyeDetection/', views.eyeDetection, name='eyeDetection'),

    # path('locationDetection/', views.locationDetection, name='locationDetection'),
    # path('cracksDetection/', views.cracksDetection, name='cracksDetection'),

    path('getLocation/', views.getLocation, name='getLocation'),
    path('elephantDetection/', views.elephantDetection, name='elephantDetection'),

    path('getAllData/', views.getAllData, name='getAllData'),
    path('warning/', views.warning, name='warning'),
    
    
    path('', views.index, name='index'),
    
]