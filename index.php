<?php
    //importa a conexao do banco
    require_once("conexaodb.php");


    if(isset($_GET["data"])) {  //verifica se há algum valor no cabecario GET
        $data = $_GET["data"];  //"pega" a data passada pelo usuario atraves do cabecario GET
    }else{
        $data = date("Y-m-d");  //se nenhum valor for passado pelo usuario, a data atual é utilizada
                                //a funcao date() retorna a data atual no formato passado por parametro
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registros de Temperatura</title>

    <style type="text/css">
        .manageMember {
            width: 50%;
            margin: auto;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

    </style>

</head>
<body>

<div class="manageMember">
    <p>Último registro de Temperatura: </p>
    <?php
    $sql = "SELECT * FROM temperatura WHERE cod_localizacao = 0 ORDER BY data_temperatura, hora_temperatura;";
    $result = $db->query($sql); //Executa a query e guarda o resultado em result
                                //$db->query($sql) retorna uma instancia de SQLite3::result
                                //que é uma classe utilizada para manipular o resultado da query
                                //(https://www.php.net/manual/pt_BR/class.sqlite3result.php)

    $result_array = array(); //Declara o array para guardar o resultado da query

    //percorre o resultado da query enquanto "salva" esse resultado na variavel $res
    //SQLite3::result->fetchArray() retorna um array com os valores da linha atual
    //Cada vez que fetchArray é chamado, o ponteiro da classe passa pra próxima linha, por isso a repeticao.
    while($res = $result->fetchArray(SQLITE3_ASSOC)){
        array_push($result_array, $res); //Insere os dados da linha no array $result_array
    }

    $numLinhas = count($result_array); //Conta a quantidade de linhas do array
    $primeiraData = $result_array[0]["data_temperatura"]; //Salva a primeira data armazenada
    $ultimaData = $result_array[$numLinhas-1]["data_temperatura"]; //Salva a ultima data armazenada

    ?>
    <table border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Temperatura</th>
                <th>Hora</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($numLinhas > 0) { // Verifica se ha algum dado armazenado
                echo "<tr>
                    <td>".$result_array[$numLinhas-1]['valor_temperatura']." °C</td>
                    <td>".$result_array[$numLinhas-1]['hora_temperatura']."</td>
                    <td>".date("d/m/Y", strtotime($result_array[$numLinhas-1]['data_temperatura']))."</td>
                </tr>"; // Imprime os valores da ultima linha do array (Ultima temperatura registrada)

            } else {
                echo "<tr><td colspan='5'><center>Sem valores registrados</center></td></tr>";
            }
            ?>
        </tbody>
    </table>
    <p></p>
    <form action="" method="get">
      <div>
        <input type="date" id="data" name="data" min=<?php echo $primeiraData ?> max= <?php echo $ultimaData ?>
        onchange='this.form.submit()'>
      </div>
    </form>

    <p>Registros do dia <?php echo date("d/m/Y", strtotime($data)); //Converte e imprime a data para o padrao BR?></p>

    <table border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Temperatura</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($numLinhas > 0) { //Verifica se ha dados no array
                $count = 0; //Declara a variavel de contador
                foreach($result_array as $value) { //Percorre o array, passando os valores do mesmo para $value
                    if ($value['data_temperatura'] == $data){ //Verifica se o valor de data do array
                                                              //é igual a data que deve ser mostrada
                        echo "<tr>
                            <td>".$value['valor_temperatura']." °C</td>
                            <td>".$value['hora_temperatura']."</td>
                        </tr>"; //Imprime o valor da temperatura, caso haja valores na data solicitada
                        $count++; //Incrementa o contador
                    }
                }
                if($count == 0){ //Se o contador nao foi incrementado, entao nao ha valores na data solicitada
                    echo "<tr><td colspan='5'><center>Sem valores registrados</center></td></tr>";
                }
            } else {
                echo "<tr><td colspan='5'><center>Sem valores registrados</center></td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
