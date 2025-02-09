<?php
// Incluindo a conexão com o banco de dados
include "conexao.php";

$data = $_GET['data'];  // Data da visita
$id_imovel = $_GET['id_imovel'];  // ID do imóvel

// Gerar os horários predefinidos (por exemplo, de 07:00 a 18:00, com intervalo de 60 minutos)
$horarios_predefinidos = [];
$hora_inicio = 8; // Início às 07:00
$hora_fim = 18; // Fim às 18:00
$intervalo = 60; // Intervalo de 60 minutos

// Gerar os horários predefinidos a cada intervalo
for ($h = $hora_inicio; $h <= $hora_fim; $h++) {
    for ($m = 0; $m < 60; $m += $intervalo) {
        $hora_formatada = sprintf("%02d:%02d", $h, $m); // Formato 'HH:MM'
        $horarios_predefinidos[] = $hora_formatada;
    }
}

// Agora, vamos filtrar os horários já agendados
$sql = "
    SELECT hora
    FROM visita
    WHERE id_imovel = ? 
    AND data = ?
";

// Preparando a consulta
$stmt = $conexao->prepare($sql);
$stmt->bind_param("is", $id_imovel, $data);
$stmt->execute();
$result = $stmt->get_result();

$horarios_agendados = [];
while ($row = $result->fetch_assoc()) {
    // Formatando o horário para 'HH:MM' (sem segundos)
    $hora_formatada = date("H:i", strtotime($row['hora']));
    $horarios_agendados[] = $hora_formatada;
}

// Filtrando os horários disponíveis
$horarios_disponiveis = array_diff($horarios_predefinidos, $horarios_agendados);

// Retorna os horários disponíveis em formato JSON
echo json_encode(array_values($horarios_disponiveis));
?>
