from django.shortcuts import render

from django.http import HttpResponse
import numpy as np
from django.views.decorators.csrf import csrf_exempt
from django.http import JsonResponse
import base64
import cv2
import re
from django.views.decorators import gzip

import mainPro.project.drowsiness_detect as eyeDetectionModel
import joblib
import pickle
import pandas as pd
import geopy.distance
import json
import mysql.connector
from mysql.connector import Error




def index(request):
    return HttpResponse("Hello, world. You're at the index page.")



# change max Elephant count for 100%
maxElephants=5
elephantShowRange=3000
elephantWarningRange=3000
eleMinRange=20



   
# @gzip.gzip_page
@csrf_exempt    
def eyeDetection(request):
    warning,value=0,0
    # return HttpResponse("Hello, world.blblbl")
    if request.method == "POST":
        ImageData = request.POST.get('image')
        
        dataUrlPattern = re.compile('data:image/(png|jpeg);base64,(.*)$')
        ImageData = dataUrlPattern.match(ImageData).group(2)
        
       
        # If none or len 0, means illegal image data
        if (ImageData == None or len(ImageData)) == 0:
            
            return JsonResponse({"warning":'0',"value":'0'})
        
        ImageData = base64.b64decode(ImageData)
        ImageData = np.frombuffer(ImageData, dtype=np.uint8)
        image = cv2.imdecode(ImageData, flags=1)
        # image=cv2.flip(image, 1)
        # cv2.imshow("image",image)
        # key = cv2.waitKey(1) & 0xFF

        warning,value=eyeDetectionModel.getEyeDetection(image)
       
    return JsonResponse({"warning":str(warning),"value":str(value)})





# load train paths
dfTrain = pd.read_csv('mainPro/mlModels/trainPaths.csv')




# load location detection models
locationDetecterPolynomial = joblib.load('mainPro/mlModels/locationDetection/locationDetecterPolynomial')
locationDetecter = joblib.load('mainPro/mlModels/locationDetection/locationDetecter')

scalerLocation = pickle.load(open('mainPro/mlModels/locationDetection/scalerLocation.sav', 'rb'))
scalerLocationYData = pickle.load(open('mainPro/mlModels/locationDetection/scalerLocationYData.sav', 'rb')) 



# load crack detection models
crackDetecterPolynomial = joblib.load('mainPro/mlModels/crackDetection/crackDetecterPolynomial')
crackDetecter = joblib.load('mainPro/mlModels/crackDetection/crackDetecter')

scalerCrack = pickle.load(open('mainPro/mlModels/crackDetection/scalerCracks.sav', 'rb'))
scalerCracksYData = pickle.load(open('mainPro/mlModels/crackDetection/scalerCracksYData.sav', 'rb')) 




def trainPathDistances(location):
    for j in range(len(dfTrain)):   
        lat,lon = dfTrain.iat[j,0],dfTrain.iat[j,1]
        dist=geopy.distance.geodesic((lat,lon),location).m
        if dist<1500:
            return 1
    else:
        return 0




@csrf_exempt  
def getLocation(request):
    
    if request.method == "POST":
     
        lat = request.POST.get('lat')
        lon = request.POST.get('lon')
        location=[float(lat),float(lon)]
        # print(location)
      
        # location=[6.9340391,79.850372]
        if trainPathDistances(tuple(location)):
            locationVal=locationDetection(location)
            crackVal=crackDetection(location)

            return JsonResponse({"locationVal":str(locationVal),"crackVal":str(crackVal)})
        else:
            return  JsonResponse({"locationVal":str(0),"crackVal":str(0)})





def normalize(val,minVal,maxVal):
       return round(minVal+ (maxVal-minVal)*val,2)




# location detection
def locationDetection(location):
   
    transformed=scalerLocation.transform([location])     #[[]]

    X_val_prep = locationDetecterPolynomial.transform(transformed)
    predictions = locationDetecter.predict(X_val_prep)
    # return  scalerLocationYData.inverse_transform(predictions)
    predictions = [[0]] if predictions[0][0]<0 else predictions
    predictions = [[1]] if predictions[0][0]>1 else predictions
    return normalize(predictions[0][0] ,0,0.5)





# crack detection
def crackDetection(location):
   
    transformed=scalerCrack.transform([location])     #[[]]

    X_val_prep = crackDetecterPolynomial.transform(transformed)
    predictions = crackDetecter.predict(X_val_prep)
    predictions[0][0]=predictions[0][0]-0.2
    # return  scalerLocationYData.inverse_transform(predictions)
    predictions = [[0]] if predictions[0][0]<0 else predictions
    predictions = [[1]] if predictions[0][0]>1 else predictions
    return normalize(predictions[0][0] ,0,0.5)





# elephant detection
def getDistance(eleLocations,trainLocation):

    elephantCount,eleArray,distArray=0,[],[]
    # print(eleLocations)
    
    for elephant in eleLocations:
        dist=geopy.distance.geodesic(elephant,trainLocation).m
        if dist<elephantWarningRange:
            elephantCount+=1
            distArray.append(dist)
        if dist<elephantShowRange:
            eleArray.append(elephant)


    return elephantCount,eleArray,distArray




def getConnection():
    connection = mysql.connector.connect(host='localhost',
                                            database='train_gps',
                                            user='root',
                                            password='')
    return connection




def connectToDatabase(location,trainId):
    elephantCount=0
    eleArray=[]
    distArray=[]
    try:
        connection=getConnection()
        if connection.is_connected() and(location[0]!=0 and location[1]!=0):
            # db_Info = connection.get_server_info()
            # print("Connected to MySQL Server version ", db_Info)
            cursor = connection.cursor(buffered=True)
            
            cursor.execute("SELECT `train_id` FROM `train_locations` WHERE `train_id`='"+trainId+"'")
            cursor.fetchall()
            if int(cursor.rowcount)>0:
                sql_select_Query="UPDATE `train_locations` SET `lat`='"+location[0]+"',`lon`='"+location[1]+"' WHERE `train_id`='"+trainId+"'"
            else:
            
                 sql_select_Query ="INSERT INTO `train_locations`(`train_id`, `lat`, `lon`) VALUES ('"+trainId+"','"+location[0]+"','"+location[1]+"')"
            cursor.execute(sql_select_Query)
            connection.commit()


            sql2="SELECT * FROM `elephant_locations`" 
            cursor.execute(sql2)
            if cursor.rowcount>0:

                elephants=cursor.fetchall()
                eleLocations=list(map(lambda x:(float(x[2]),float(x[3])),elephants))
                
                elephantCount,eleArray,distArray=getDistance(eleLocations,tuple(map(float,location)))
        

    except Error as e:
        pass
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("MySQL connection is closed")

    return elephantCount,eleArray,distArray









def elephantNormalize(distArray):
    # print(distArray)
    distSum=sum([1/i for  i in distArray if i>0])

    val=0.5/(1/eleMinRange-1/elephantWarningRange)*(distSum-1/elephantWarningRange)

    # print(val,(1/eleMinRange-1/elephantWarningRange),distSum-1/elephantWarningRange)
    val = val if val<=0.5 else 0.5


    print("Value is ",val)
    return val if val>=0 else 0





@csrf_exempt  
def elephantDetection(request):
    elephantCount=0
    if request.method == "POST":
     
        lat = request.POST.get('lat')
        lon = request.POST.get('lon')
        trainId = request.POST.get('trainId')
        location=[lat,lon]
        elephantCount,eleArray,distArray=connectToDatabase(location,trainId)
        
        # elephantCount=maxElephants if elephantCount>maxElephants else elephantCount

        eleVal=elephantNormalize(distArray)
        # eleVal=normalize(elephantCount/maxElephants ,0,0.5)
        # print("Elephant Count is : ",elephantCount,eleVal)

    # print(eleArray)

    return JsonResponse({"eleCount":str(round(eleVal*200))+"%","eleVal":str(eleVal),"eleArray": json.dumps(eleArray)})












# admin section

@csrf_exempt  
def getAllData(request):

    elephants,trains=[],[]
    if request.method == "POST":
        try:
            connection=getConnection()

            if connection.is_connected():
                cursor = connection.cursor(buffered=True)
                
                cursor.execute("SELECT `lat`, `lon` FROM `elephant_locations`")
                elephants=cursor.fetchall()
                # print(elephants)

                cursor.execute("SELECT `lat`, `lon` FROM `train_locations`")
                trains=cursor.fetchall()

        except Error as e:
                pass
        finally:
            if connection.is_connected():
                cursor.close()
                connection.close()
                print("MySQL connection is closed")
    
    return JsonResponse({"elephants":json.dumps(elephants),"trains":json.dumps(trains)})
     




@csrf_exempt 
def warning(request):
    if request.method == "POST":
        lat = request.POST.get('lat')
        lon = request.POST.get('lon')
        trainId = request.POST.get('trainId')
        
        try:
            connection=getConnection()

            if connection.is_connected():
                cursor = connection.cursor(buffered=True)
                cursor.execute("SELECT `train_id` FROM `warnings` WHERE `train_id`='"+trainId+"'")
                cursor.fetchall()
                if int(cursor.rowcount)>0:
                    sql_select_Query="UPDATE `warnings` SET `lat`='"+lat+"',`lon`='"+lon+"', `time`=CURRENT_TIMESTAMP WHERE `train_id`='"+trainId+"'"
                    print(sql_select_Query)
                else:
            
                    sql_select_Query ="INSERT INTO `warnings`(`train_id`, `lat`, `lon`) VALUES ('"+trainId+"','"+lat+"','"+lon+"')"
                cursor.execute(sql_select_Query)
                connection.commit()

                
               
             


        except Error as e:
                pass
        finally:
            if connection.is_connected():
                cursor.close()
                connection.close()
                print("MySQL connection is closed")

    return HttpResponse("ok")