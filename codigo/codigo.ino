#include <UIPEthernet.h>
#include <math.h>

// Endereco MAC do controlador
const byte mac[] PROGMEM = {
  0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xE3
};

float temp;

unsigned long tempoPassado = 0; //Variavel para guardar o valor da funcao millis() utilizada abaixo. 
                                //Essa funcao retorna a quantidade de milissegundos passados desde o momento que o arduino foi ligado
                                
unsigned long intervaloConexao = 600000; //Tempo de espera até a proxima conexao com o raspberry (10 minutos)

const double a PROGMEM = 0.001129148;     //
const double b PROGMEM = 0.000234125;     //Parâmetros de Steinhart–Hart
const double c PROGMEM = 0.0000000876741; //

EthernetClient client;

char charServer[] = "192.168.15.186"; //IP do Servidor do banco de dados (para imprimir no header HTTP)
IPAddress ipServer(192,168,15,186); //IP do Servidor do banco de dados (para passar por parametro)


float calcularTemp(){

  float R;    //Resistência do NTC
  float T;    //Temperatura em Kelvin
  int leitura; 
  leitura = analogRead(A0);
  R = (10000.0 * ((float)1023/leitura - 1));  //Calcula valor de R em função da leitura
  T = 1 / (a + (b * log(R)) + (c * pow(log(R),3))); //Equação de Steinhart–Hart 
  T = T - 273.15; //Converte Kelvin para Celsius
  return T;
}


void setup() {
  
  Serial.begin(9600);
  while (!Serial) {
    ;
  }
  Serial.println("Iniciando requisição DHCP...");

  // Inicia a requisição DHCP
  if (Ethernet.begin(mac) == 1){
    Serial.println("Requisição DHCP concluida.");
  }else{
    Serial.println("Falha no DHCP.");
  }

  // Verifica se ha conexao
  while (Ethernet.linkStatus() == LinkOFF) {
    Serial.println("Nao ha conexao.");  
  }

  Serial.print("Endereco IP: ");
  Serial.println(Ethernet.localIP());
  Serial.println("Hostname: MonitorTemp");
}

void loop() {
  if ((millis() - tempoPassado > intervaloConexao) || tempoPassado == 0){ //Utiliza a funcao millis para verificar se o intervalo de tempo foi cumprido.
    tempoPassado = millis();
    // Tenta realizar uma conexao com o servidor
    if (client.connect(ipServer, 80)) {
      Serial.println("-> Conectado.");
      temp = calcularTemp();
      // Make a HTTP request:
      client.print( "GET /receberdados.php?");
      client.print("localizacao=0");
      client.print("&");
      client.print("valor=");
      client.print(temp);
      client.print( " HTTP/1.1\r\n");
      client.print( "Host: " );
      client.print(charServer);
      client.print( "\r\n" );
      client.print( "Connection: close\r\n" );
      client.println();
      //client.println();
      client.stop();
    }
    else {
      // Se nao conseguir conectar, imprime a mensagem:
      Serial.println("--> Erro de conexao!/n");
      Enc28J60.init(mac); //Tenta iniciar novamente o controlador de Ethernet, caso o mesmo esteja OFF.
    }
  }else{ //Enquanto o intervalo de tempo nao for cumprido, executa a funcao de "manutencao" abaixo.
    delay(10); //Espera um certo tempo para o proximo "maintain()"    
    Ethernet.maintain(); //Funcao que reliza a "manutencao" do arduino na rede. (Responde a pings, ARPs, etc.)
    //Serial.println(temp);
  }
}
