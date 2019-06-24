<?php

$localhost = "127.0.0.1";
$username = "prob";
$password = "1201";
$dbname = "monitortemp";

$connect = new mysqli($localhost, $username, $password, $dbname);

if($connect->connect_error) {
    die("Falha na conexao : " . $connect->connect_error);
} else {
    // echo "Conectado!";
}

?>
