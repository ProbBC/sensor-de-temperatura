/*
    This sketch establishes a TCP connection to a "quote of the day" service.
    It sends a "hello" message, and then prints received data.
*/

#include <ESP8266WiFi.h>

#ifndef STASSID
#define STASSID "Ifes Hotspot"
#define STAPSK  ""
#endif

const char* ssid     = STASSID;
const char* password = STAPSK;

const char* host = "172.16.60.114";
const uint16_t port = 80;
                                
unsigned long intervaloConexao = 600000; //Tempo de espera até a proxima conexao com o raspberry (10 minutos)

const double a PROGMEM = 0.001129148;     //
const double b PROGMEM = 0.000234125;     //Parâmetros de Steinhart–Hart
const double c PROGMEM = 0.0000000876741; //

float temp;

float calcularTemp(){
  float R;    //Resistência do NTC
  float T;    //Temperatura em Kelvin
  int leitura; 
  leitura = analogRead(A0);
  Serial.println(leitura);
  R = (10000.0 * ((float)1023/leitura - 1));  //Calcula valor de R em função da leitura
  T = 1 / (a + (b * log(R)) + (c * pow(log(R),3))); //Equação de Steinhart–Hart 
  T = T - 273.15; //Converte Kelvin para Celsius
  return T;
}

void setup() {
  Serial.begin(115200);

  // We start by connecting to a WiFi network

  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  /* Explicitly set the ESP8266 to be a WiFi-client, otherwise, it by default,
     would try to act as both a client and an access-point and could cause
     network-issues with your other WiFi-devices on your WiFi-network. */
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  Serial.print("connecting to ");
  Serial.print(host);
  Serial.print(':');
  Serial.println(port);

  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  if (!client.connect(host, port)) {
    Serial.println("connection failed");
    delay(5000);
    return;
  }

  // This will send a string to the server
  Serial.println("sending data to server");
  if (client.connected()) {
    Serial.println("-> Conectado.");
    temp = calcularTemp();
    // Make a HTTP request:
    client.print( "GET /receberdados.php?");
    client.print("localizacao=0");
    client.print("&");
    client.print("valor=");
    Serial.println(temp);
    client.print(temp);
    client.print( " HTTP/1.1\r\n");
    client.print( "Host: " );
    client.print(host);
    client.print( "\r\n" );
    client.print( "Connection: close\r\n" );
    client.println();
    //client.println();
    //client.stop();
  }

  // wait for data to be available
  unsigned long timeout = millis();
  while (client.available() == 0) {
    if (millis() - timeout > 5000) {
      Serial.println(">>> Client Timeout !");
      client.stop();
      delay(60000);
      return;
    }
  }

  // Read all the lines of the reply from server and print them to Serial
  Serial.println("receiving from remote server");
  // not testing 'client.connected()' since we do not need to send data here
  while (client.available()) {
    char ch = static_cast<char>(client.read());
    Serial.print(ch);
  }

  // Close the connection
  Serial.println();
  Serial.println("closing connection");
  client.stop();

  delay(intervaloConexao); // execute once every 5 minutes, don't flood remote service
}
