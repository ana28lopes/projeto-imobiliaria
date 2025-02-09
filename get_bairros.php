<?php
include 'conexao.php'; // Inclua o arquivo de conexão com o banco de dados

if (isset($_POST['id_cidade'])) {
    $id_cidade = $_POST['id_cidade'];

    $query = "SELECT id_bairro, descricao FROM bairro WHERE id_cidade = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $id_cidade);
    $stmt->execute();
    $result = $stmt->get_result();

    $bairros = "<option value=''>Selecione</option>";
    while ($row = $result->fetch_assoc()) {
        $bairros .= "<option value='{$row['id_bairro']}'>{$row['descricao']}</option>";
    }
    echo $bairros;
}
?>
