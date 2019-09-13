#include <Sim800L.h>
#include <SoftwareSerial.h>               

#define RX  10
#define TX  11

Sim800L GSM(RX, TX);

/*
 * In alternative:
 * Sim800L GSM;                       // Use default pinout
 * Sim800L GSM(RX, TX, RESET);        
 * Sim800L GSM(RX, TX, RESET, LED);
 */

char* text;
char* number;
bool error;
String signal_quality;


void setup(){
  Serial.begin(4800);
  GSM.begin(4800);      
  text = (char*)"TESTE SMS";     //text for the message. 
  number = (char*)"28999145628";    //change to a valid number.

  // OR 
  //Sim800L.sendSms("+540111111111","the text go here")
}

void loop(){
  //do nothing
  signal_quality = GSM.signalQuality();
  if(signal_quality != ""){
      Serial.print(signal_quality);
    }else{
      Serial.println("variavel vazia");
    }
  Serial.println("Enviando...");
  error=GSM.sendSms(number, text);
  if(error == 1){
    Serial.print("Enviado.");
  }else{
    Serial.print("Erro.");
  }
  delay(1000);
}
