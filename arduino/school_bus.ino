#include <SPI.h>
#include <MFRC522.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <string.h>
#include <ArduinoJson.h>
#include <TinyGPSPlus.h>
#include "twilio.hpp"
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>

#define SS_PIN  5  
#define RST_PIN  4

TinyGPSPlus gps; 
LiquidCrystal_I2C lcd(0x27,20,4);

char uid[] = {0, 0, 0, 0, 0, 0}; // Array to store UID
char uidString[14];

//const char* serverName = "https://schoolbusmonitoring.azurewebsites.net/student_log_up.php";
const char* serverName = "http://172.20.10.3/schoolBusMonitoring/student_log_up.php";
const char* urlParams = "?card_uid=";

const char* ssid = "Plandi"; //choose your wireless ssid
const char* wifi_password =  "Makali@1205"; //put your wireless password here.
// Values from Twilio (find them on the dashboard)
static const char *account_sid = "AC69bcde5459ced29c842d881afb9d572c";
static const char *auth_token = "4c6eb30f92780239d056050f4ca98c95";
static const char *from_number = "+13654008318";
int bus_id = 1010;


MFRC522 mfrc522(SS_PIN, RST_PIN); 

void setup() {

  pinMode(1, OUTPUT);
  pinMode(0, INPUT);
  Serial.begin(115200);
  Serial1.begin(9600, SERIAL_8N1, 16, 17);
  while (!Serial); 
  SPI.begin();
  mfrc522.PCD_Init();
  delay(4);
  mfrc522.PCD_DumpVersionToSerial();
  lcd.init();
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(0,1);
  lcd.print("Scan your card...");
  delay(50);
  
}

void loop() {
  //check if there's a connection to Wi-Fi or not
  if(!WiFi.isConnected()){
    connectToWifi();    //Retry to connect to Wi-Fi
  }
//---------------------------------------------------
  updateLocation();
  if ( ! mfrc522.PICC_IsNewCardPresent()) {
    return;
  }

  if ( ! mfrc522.PICC_ReadCardSerial()) {
    return;
  }
 
  // Store UID into uid array
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    uid[i] = mfrc522.uid.uidByte[i];
     
  }

  for(byte i = 0; i < mfrc522.uid.size; i++){
    sprintf(uidString+2*i, "%02X", uid[i]); 
  }

  // Print UID 
  Serial.print("UID: ");
  Serial.print(uidString);
  Serial.println();
  mfrc522.PICC_HaltA(); // Halt PICC
  mfrc522.PCD_StopCrypto1(); // Stop encryption

  sendCardUID();
  delay(1000);
  

}

void connectToWifi(){
  WiFi.mode(WIFI_OFF);        //Prevents reconnection issue (taking too long to connect)
  delay(1000);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, wifi_password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.println("Connecting to WiFi..");
  }
  Serial.println("Connected to the WiFi network "+ String(ssid));
}

void sendCardUID(){
  if(WiFi.status() == WL_CONNECTED){
    
    HTTPClient https;
    https.useHTTP10(true);
    String request = String(serverName) + String(urlParams) + String(uidString) + "&bus_id=" + String(bus_id);
    https.begin(request);
    //Serial.println(request);
    
    int httpResponseCode = https.GET();

    if (httpResponseCode > 0){
      Serial.print("Code: ");
      Serial.print(httpResponseCode);
      Serial.println();
      processAPIResponse(https.getStream());
      https.end();
    }
    else{
      Serial.print("HTTP Error code: ");
      Serial.println(httpResponseCode);
    }
  } 
   
}

void processAPIResponse(Stream& apiResponse){
  
  StaticJsonDocument<192> doc;

  DeserializationError error = deserializeJson(doc, apiResponse);

  if (error) {
    Serial.print("deserializeJson() failed: ");
    Serial.println(error.c_str());
    return;
  }

  JsonObject root_0 = doc[0];
  String response = root_0["response"];
  if(response == "Student approved"){
    const char* phone = root_0["phone"];
    const char* sms = root_0["sms"]; 
    lcd.clear();
    lcd.setCursor(0,1);
    lcd.print(response);
    lcd.setCursor(0,2);
    lcd.print("You may get in");
    send_message(phone,sms);
    delay(2000);
    lcd.clear();
    lcd.setCursor(0,1);
    lcd.print("Scan your card...");
  }
  else{
    lcd.clear();
    lcd.setCursor(0,0);
    lcd.print("Access denied");
    lcd.setCursor(0,1);
    lcd.print("Student not found");
    lcd.setCursor(0,2);
    lcd.print("Or wrong bus");
    delay(2000);
    lcd.clear();
    lcd.setCursor(0,1);
    lcd.print("Scan your card...");
  }

}

void send_message(const char* phone, const char* sms){
  Twilio *twilio;
  twilio = new Twilio(account_sid, auth_token);

  static const char *to_number = phone;
  String message = String(sms);

  delay(1000);
  String response;
  bool success = twilio->send_message(to_number, from_number, message, response);
  if (success) {
    Serial.println("Message sent successfully!");
  } else {
    Serial.println(response);
  }
}

void updateLocation(){
    // Read from GPS hardware serial
  while (Serial1.available() > 0) {
    gps.encode(Serial1.read());
  }

  // Check for new GPS data
  if (gps.location.isUpdated()) {

    // Construct request data
    String request = String(serverName) + "?lat=";
    request += String(gps.location.lat(), 6);
    request += "&lng=";
    request += String(gps.location.lng(), 6); 
    request += "&bus_id=";
    request += String(bus_id);
    //Serial.println(request);
    

    // Send request to server
    HTTPClient http;
    http.useHTTP10(true);
    
    http.begin(request);
    http.POST(request);
    
    // Print response 
    int response = http.GET();
    //Serial.println(response);

    http.end(); 
  }

  // Check again in 2 seconds
  delay(2000); 
}