<?php
include "conexao.php";

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$sexo = $_POST['sexo'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$data = $_POST['data'];
$hora = $_POST['hora'];
$id_imovel = $_POST['id_imovel'];
$id_endereco = 0;

// Inserir pessoa
$sql = "INSERT INTO pessoa (cpf, nome, sexo, telefone, id_endereco) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("isssi", $cpf, $nome, $sexo, $telefone, $id_endereco);
$stmt->execute();
$id_pessoa = $conexao->insert_id;

// Inserir locatário
$sql = "INSERT INTO locatario (email, data_cadastro, cpf_pessoa, id_pessoa) VALUES (?, NOW(), ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("sii", $email, $cpf, $id_pessoa);
$stmt->execute();
$id_locatario = $conexao->insert_id;

// Inserir visita
$sql = "INSERT INTO visita (data, hora, id_locatario, id_imovel) VALUES (?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ssii", $data, $hora, $id_locatario, $id_imovel);
$stmt->execute();
?>

<!-- Exibir pop-up com a mensagem de sucesso -->
<script>
    alert("Agendamento realizado com sucesso!");
    // Opcionalmente, você pode redirecionar após o pop-up
    window.location.href = "buscar_imovel.php";
</script>

<?php
// Agora, você pode manter o mesmo layout ou redirecionar o usuário para outra página, se desejar
?>
