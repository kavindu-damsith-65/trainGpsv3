
# python face_landmarks.dat


# import libraries
from scipy.spatial import distance as dist
from imutils.video import VideoStream
from imutils import face_utils
from threading import Thread
import numpy as np

import argparse
import imutils
import time
import dlib
import cv2
import pygame



def eye_level_measurement(eye):
    
    left = dist.euclidean(eye[1], eye[5])
    right = dist.euclidean(eye[2], eye[4])

    
    distance = dist.euclidean(eye[0], eye[3])

    
    ey = (left + right) / (2.0 * distance)

    
    return ey
 

# arp = argparse.ArgumentParser()


# arp.add_argument("-w", "--webcam", type=int, default=0,
#     help="index of webcam on system")
# ag = vars(arp.parse_args())
 

Eye_Thresh = 0.3
Eye_Low = 5
Eye_High = 10


COUNT = 0
A_ON = False
A2_ON = False


print("loading eye location predictor...")
detector = dlib.get_frontal_face_detector()
predictor = dlib.shape_predictor("mainPro/project/face_landmarks.dat")
# predictor = dlib.shape_predictor("trainDamageReduction/mainPro/project/face_landmarks.dat")


(lStart, lEnd) = face_utils.FACIAL_LANDMARKS_IDXS["left_eye"]
(rStart, rEnd) = face_utils.FACIAL_LANDMARKS_IDXS["right_eye"]


# print("starting webcam")
# vs = VideoStream(src=ag["webcam"]).start()
time.sleep(1.0)


def getEyeDetection(frame=""):
    global COUNT
    global A_ON
    global A2_ON
    global Eye_Thresh
    global Eye_Low
    global Eye_High


    warning,finalValue='0',0

    # frame=vs.read()
    frame = imutils.resize(frame, width=500)
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

    
    rects = detector(gray, 0)

   
    for rect in rects:
        
        
        shape = predictor(gray, rect)
        shape = face_utils.shape_to_np(shape)

        
        
        L_Eye = shape[lStart:lEnd]
        R_Eye = shape[rStart:rEnd]
        L_EAR = eye_level_measurement(L_Eye)
        R_EAR = eye_level_measurement(R_Eye)

        
        ey = (L_EAR + R_EAR) / 2.0

        
        L_EyeHull = cv2.convexHull(L_Eye)
        R_EyeHull = cv2.convexHull(R_Eye)
        cv2.drawContours(frame, [L_EyeHull], -1, (0, 255, 0), 1)
        cv2.drawContours(frame, [R_EyeHull], -1, (0, 255, 0), 1)

        
        
        if ey < Eye_Thresh:
            COUNT += 1
            print("the counter is :"+str(COUNT)) 

            
            if (COUNT >= Eye_Low) and (COUNT <=10):
                warning='1'
                if not A_ON:
                    A_ON = True

                    pygame.mixer.init()
                    
                
                # print("WARNING !!! You are loosing control")
                cv2.putText(frame, "WARNING !!!", (10, 30),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 0, 255), 2)
             
                
            elif COUNT >= Eye_High:
                
                if not A2_ON:
                    A2_ON = True

                        
                    pygame.mixer.init()
                    
                warning='1'
                # print("WARNING !!! You are loosing control")
                cv2.putText(frame, "WARNING !!!", (10, 30),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 0, 255), 2)

        
        else:
            COUNT = 0
            A_ON = False
            A2_ON = False
            

        finalValue="{:.2f}".format(ey)
        cv2.putText(frame, "EAR: "+finalValue, (300, 30),
            cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 0, 255), 2)
    print(warning,finalValue)
   
    

    # show the frame
    # cv2.imshow("Frame", frame)
    # key = cv2.waitKey(1) & 0xFF
    return warning,finalValue
    


cv2.destroyAllWindows()
# vs.stop()





