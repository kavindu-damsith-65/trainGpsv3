#ifdef ESP32
#include <WiFi.h>
#include <HTTPClient.h>
#else
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#endif

#include <Wire.h>
#include <TinyGPS++.h>





#define RXD2 16
#define TXD2 17

HardwareSerial neogps(1);
TinyGPSPlus gps;

// int LED_BUILTIN = 2;
// Replace with your network credentials
const char *ssid = "Anushka Oyege Wifi Apita epa";
const char *password = "50kavindu";

// REPLACE with your Domain name and URL path or IP address with path

const char *serverName = "http://192.168.1.12:80/trainGps/app_model/php/postEspData.php";

// Keep this API Key value to be compatible with the PHP code provided in the project page.
// If you change the apiKeyValue value, the PHP file /postEspData.php also needs to have the same key
String apiKeyValue = "tPmAT5Ab3j7F9";
String elephantId = "elephant_1";




//
//float lat;
//float lon;
char lat[10];
char lon[11];


void setup()
{
  delay(2000);
  Serial.begin(115200);
  // Begin serial communication Neo6mGPS
  neogps.begin(9600, SERIAL_8N1, RXD2, TXD2);

  // set up wifi connection
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  delay(200);
}

void loop()
{
   dtostrf(0, 9, 6, lat); 
   dtostrf(0, 10, 6, lon);

  if (WiFi.status() == WL_CONNECTED)
  {
    HTTPClient http;

    //  get location
    boolean newData = false;
    for (unsigned long start = millis(); millis() - start < 1000;)
    {
      while (neogps.available())
      {
        if (gps.encode(neogps.read()))
        {
          newData = true;
        }
      }
    }

    // If newData is true
    if (newData == true)
    {
      newData = false;

      if (gps.location.isValid() == 1)
      {

        dtostrf(gps.location.lat(), 9, 6, lat); // 9 characters for 6 decimal places
        dtostrf(gps.location.lng(), 10, 6, lon); // 10 characters for 6 decimal places;


        

               http.begin(serverName);
        
               // Specify content-type header
               http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        
               // Prepare your HTTP POST request data
               String httpRequestData = "api_key=" + apiKeyValue + "&elephant_id=" + elephantId + "&lat=" + String(lat) + "&lon=" + String(lon) + "";
               Serial.print("httpRequestData: ");
               Serial.println(httpRequestData);
        
               int httpResponseCode = http.POST(httpRequestData);
        
               if (httpResponseCode > 0)
               {
                 Serial.print("HTTP Response code: ");
                 Serial.println(httpResponseCode);
               }
               else
               {
                 Serial.print("Error code: ");
                 Serial.println(httpResponseCode);
               }
               // Free resources
               http.end();
      }
      else
      {
        Serial.println("Something Went wrong!!");
      }
    }
    else
    {
      Serial.println("No data");
    }
    delay(100);
  }
  else
  {
    Serial.println("WiFi Disconnected");
  }
  // Send an HTTP POST request every 2 seconds
  delay(2000);
}
