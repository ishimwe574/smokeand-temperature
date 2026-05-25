#include <WiFi.h>
#include <HTTPClient.h>
#include "DHT.h"

// WiFi
const char* ssid = "YOUR_WIFI_NAME";
const char* password = "YOUR_WIFI_PASSWORD";

// Server URL
String serverName = "http://YOUR_SERVER_IP/smoke/insert.php";

// MQ2 Pin
int smokeSensor = 34;

// DHT11 Settings
#define DHTPIN 4
#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);

void setup() {

  Serial.begin(115200);

  dht.begin();

  WiFi.begin(ssid, password);

  Serial.print("Connecting to WiFi");

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi Connected");
}

void loop() {

  // Read smoke
  int smokeValue = analogRead(smokeSensor);

  // Read temperature & humidity
  float temperature = dht.readTemperature();
  float humidity = dht.readHumidity();

  Serial.print("Smoke: ");
  Serial.println(smokeValue);

  Serial.print("Temperature: ");
  Serial.println(temperature);

  Serial.print("Humidity: ");
  Serial.println(humidity);

  if (WiFi.status() == WL_CONNECTED) {

    HTTPClient http;

    String serverPath = serverName +
                        "?smoke=" + String(smokeValue) +
                        "&temperature=" + String(temperature) +
                        "&humidity=" + String(humidity);

    http.begin(serverPath.c_str());

    int httpResponseCode = http.GET();

    Serial.print("HTTP Response: ");
    Serial.println(httpResponseCode);

    http.end();
  }

  delay(5000);
}