<?php
$host = "localhost";
$user = "root"; // Altere conforme seu banco
$password = "";
$database = "imobiliaria";

$conexao = new mysqli($host, $user, $password, $database);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}
?>
