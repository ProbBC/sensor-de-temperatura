<?php
    //importa a conexÃ£o do banco
    require_once("conexaodb.php");

    //"pega" a data passada pelo usuÃ¡rio atravÃ©s do cabeÃ§Ã¡rio GET
    if(isset($_GET["data"])) {
        $data = $_GET["data"];
    }else{
        $data = date("Y-m-d");
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

    $result_array = array(); //Declara o array para guardar o resultado da query

    while($res = $result->fetchArray(SQLITE3_ASSOC)){
        array_push($result_array, $res); //Insere os dados no array
    }

    $numLinhas = count($result_array);
    $primeiraData = $result_array[0]["data_temperatura"];
    $ultimaData = $result_array[$numLinhas-1]["data_temperatura"];

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
            if($numLinhas > 0) {
                echo "<tr>
                    <td>".$result_array[$numLinhas-1]['valor_temperatura']." °C</td>
                    <td>".$result_array[$numLinhas-1]['hora_temperatura']."</td>
                    <td>".date("d/m/Y", strtotime($result_array[$numLinhas-1]['data_temperatura']))."</td>
                </tr>";

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

    <p>Registros do dia <?php echo date("d/m/Y", strtotime($data)); ?></p>

    <table border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Temperatura</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($numLinhas > 0) {
                $count = 0;
                foreach($result_array as $value) {
                    if ($value['data_temperatura'] == $data){
                        echo "<tr>
                            <td>".$value['valor_temperatura']." °C</td>
                            <td>".$value['hora_temperatura']."</td>
                        </tr>";
                        $count++;
                    }
                }
                if($count == 0){
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
