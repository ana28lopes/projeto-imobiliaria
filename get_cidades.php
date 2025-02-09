<?php
include 'conexao.php'; // Inclua o arquivo de conexão com o banco de dados

if (isset($_POST['id_estado'])) {
    $id_estado = $_POST['id_estado'];

    $query = "SELECT id_cidade, descricao FROM cidade WHERE id_estado = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $id_estado);
    $stmt->execute();
    $result = $stmt->get_result();

    $cidades = "<option value=''>Selecione</option>";
    while ($row = $result->fetch_assoc()) {
        $cidades .= "<option value='{$row['id_cidade']}'>{$row['descricao']}</option>";
    }
    echo $cidades;
}
?>
