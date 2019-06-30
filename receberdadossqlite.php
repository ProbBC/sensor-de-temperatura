<?php
    include("conexaosqlite3.php");

    $data = date("Y-m-d");
    $hora = date("H:i:s");


    $localizacao = $_GET["localizacao"];
    $valor = $_GET["valor"];

    $sql = "INSERT INTO temperatura (cod_localizacao, valor_temperatura, data_temperatura, hora_temperatura) ";
    //$sql .= "VALUES ('".$_GET["localizacao"]."', ".$_GET["valor"].", '$data', '$hora');";
    $sql .= "VALUES ('$localizacao', $valor, '$data', '$hora');";

    sqlite_query($sql);
?>
