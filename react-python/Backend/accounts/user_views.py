from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.conf import settings
import pymongo
import json
from datetime import datetime

# Connect to MongoDB
client = pymongo.MongoClient(settings.DATABASES['default']['CLIENT']['host'])
db = client['SAMS']  # Access database
users_collection = db['Users']  # Access Users collection

@csrf_exempt
def register(request):
    """
    Handles user registration.
    Accepts user_id from input and stores passwords in plain text.
    """
    #write your code


@csrf_exempt
def login(request):
    """
    Handles user login.
    Validates email and plain-text password.
    """
    #write your code
