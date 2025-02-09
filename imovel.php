<?php
include "./header/header.html";
include "conexao.php";

// Obtém o ID do imóvel da URL
$id_imovel = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Atualiza a consulta SQL para pegar mais informações relacionadas
$sql = "
    SELECT i.*, 
           f.foto1, f.foto2, f.foto3, f.foto4, f.foto5, 
           c.descricao AS categoria_imovel, 
           e.logradouro, e.numero, e.complemento, e.cep, 
           b.descricao AS bairro, 
           ci.descricao AS cidade, 
           es.descricao AS estado
    FROM imovel i
    JOIN fotos_imovel f ON i.id_fotos = f.id_fotos
    JOIN categoria_imovel c ON i.id_categoria = c.id_categoria
    JOIN endereco e ON i.id_endereco = e.id_endereco
    JOIN bairro b ON e.id_bairro = b.id_bairro
    JOIN cidade ci ON e.id_cidade = ci.id_cidade
    JOIN estado es ON e.id_estado = es.id_estado
    WHERE i.id_imovel = $id_imovel
";

// Executa a consulta
$result = mysqli_query($conexao, $sql);
$imovel = mysqli_fetch_assoc($result);

// Determina se o imóvel é uma casa ou apartamento com base no id_categoria
$detalhes_imovel = [];
if ($imovel['id_categoria'] == 2) {  // Exemplo: 1 representa "Casa"
    // Caso seja uma casa, busca os detalhes na tabela casa
    $sql_casa = "SELECT * FROM casa WHERE id_imovel = $id_imovel";
    $result_casa = mysqli_query($conexao, $sql_casa);
    $detalhes_imovel = mysqli_fetch_assoc($result_casa);
} elseif ($imovel['id_categoria'] == 1) {  // Exemplo: 2 representa "Apartamento"
    // Caso seja um apartamento, busca os detalhes na tabela apartamento
    $sql_apartamento = "SELECT * FROM apartamento WHERE id_imovel = $id_imovel";
    $result_apartamento = mysqli_query($conexao, $sql_apartamento);
    $detalhes_imovel = mysqli_fetch_assoc($result_apartamento);
}

$data_ajustada1 = $imovel['data_construcao'];
$data_ajustada2 = DateTime::createFromFormat('Y-m-d', $data_ajustada1);

// Lista de imagens do imóvel
$imagens = [];
for ($i = 1; $i <= 5; $i++) {
    if (!empty($imovel["foto$i"])) {
        $imagens[] = $imovel["foto$i"];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Imóvel</title>
    <style>
    /* Estilos globais do seu site */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
    }

    .container {
        max-width: 1300px;
        margin: 30px auto;
        background: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		font-size: 17px;
    }

    h1, h2 {
        color: #FF7E34;
        margin-bottom: 20px;
    }

    /* Estilos para o carrossel com tamanho fixo */
		.carousel-container {
			position: relative;
			width: 100%; /* largura total do container pai */
			max-width: 100%; /* opcional, para evitar ultrapassar o tamanho do container */
			height: 800px; /* altura fixa do carrossel */
			overflow: hidden;
		}

		.carousel-inner {
			position: relative;
			display: flex;
			
			transition: transform 0.5s ease;
		}

		.carousel-item {
			flex: 0 0 100%; /* Cada item ocupa 100% da largura do carrossel */
		}

		/* Ajustar apenas o tamanho das imagens */
		.carousel-inner img {
			width: 100%; /* As imagens vão ocupar toda a largura do carrossel */
			height: 800px; /* Definindo altura fixa para as imagens */
			object-fit: cover; /* As imagens vão cobrir o espaço, sem distorcer a proporção */
		}

		/* Botões do carrossel */
		.carousel-container .carousel-prev,
		.carousel-container .carousel-next {
			position: absolute;
			top: 50%;
			transform: translateY(-50%);
			background-color: rgba(0, 0, 0, 0.5);
			border: none;
			color: white;
			padding: 10px;
			font-size: 18px;
			cursor: pointer;
		}

		.carousel-prev {
			left: 10px;
		}

		.carousel-next {
			right: 10px;
		}
		
		
		button {
            background-color: #FF7E34;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
		
		
		.modal {
			display: none;
			position: fixed;
			z-index: 1;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			
		}

		.modal-content {
			background-color: white;
			padding: 20px;
			margin: 15% auto;
			width: 50%;
			border-radius: 8px;
		}

		.close {
			float: right;
			cursor: pointer;
		}
		
		.modal-content input,
		.modal-content select,
		.modal-content textarea {
			width: 25%; /* Ocupa toda a largura do modal */
			padding: 8px; /* Adiciona um pequeno espaçamento interno */
			margin: 20px 0; /* Espaçamento entre os campos */
			border: 1px solid #ccc; /* Borda suave */
			border-radius: 5px; /* Bordas arredondadas */
			font-size: 14px; /* Tamanho da fonte */
		}

</style>
</head>

<body>

    <div class="container">
        <h2>Detalhes do Imóvel</h2>
		
		<!-- Carrossel -->
        <?php if (!empty($imagens)): ?>
        <div class="carousel-container">
            <div class="carousel-inner">
                <?php foreach ($imagens as $imagem): ?>
                <div class="carousel-item">
                    <img src="<?php echo htmlspecialchars($imagem); ?>" alt="Imagem do imóvel">
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="carousel-next" onclick="moveSlide(1)">&#10095;</button>
        </div>
        <?php else: ?>
            <p>Sem imagens disponíveis.</p>
        <?php endif; ?>
		
		<br>
		<h2>Características</h2>
		<p>&#9210 <?php echo htmlspecialchars($imovel['categoria_imovel']); ?> - <?php echo htmlspecialchars($imovel['area']); ?> m² &nbsp; - &nbsp;
		<strong>Data de Construção:</strong> <?php echo $data_ajustada2->format('d/m/Y'); ?> &nbsp; - &nbsp;
		&#128178<strong> Valor do Aluguel:</strong> R$ <?php echo htmlspecialchars($imovel['valor_aluguel']); ?></p>
       <br>
        <!-- Endereço -->
        <p>&#127969; <strong>Endereço:</strong> <?php echo htmlspecialchars($imovel['logradouro']); ?>, <?php echo htmlspecialchars($imovel['numero']); ?>, 
		<?php echo htmlspecialchars($imovel['bairro']); ?>, <?php echo htmlspecialchars($imovel['cidade']); ?> - <?php echo htmlspecialchars($imovel['estado']); ?> - 
		<?php echo htmlspecialchars($imovel['cep']); ?></p>    
        <p><strong>Complemento:</strong> <?php echo htmlspecialchars($imovel['complemento']); ?></p>
		<br>

        <!-- Detalhes adicionais dependendo da categoria -->
        <?php if ($imovel['id_categoria'] == 2): ?>
            <!-- Detalhes de uma casa -->
            <p>&#128716 <strong>Quartos:</strong> <?php echo htmlspecialchars($detalhes_imovel['qtd_quartos']); ?> &nbsp;- &nbsp;
			<strong>Suítes:</strong> <?php echo htmlspecialchars($detalhes_imovel['qtd_suites']); ?> &nbsp;- &nbsp;
			<strong>Sala de estar:</strong> <?php echo htmlspecialchars($detalhes_imovel['qtd_salas']); ?> &nbsp;- &nbsp;
			<strong>Armários embutidos:</strong> <?php echo htmlspecialchars($detalhes_imovel['armarios_embutidos']); ?></p> <br>
            <p>&#128663 <strong>Vagas de garagem:</strong> <?php echo htmlspecialchars($detalhes_imovel['num_vagas']); ?></p> <br>
            <p>&#128205 <?php echo htmlspecialchars($detalhes_imovel['descricao']); ?></p> <br>
        
		<?php elseif ($imovel['id_categoria'] == 1): ?>
            <!-- Detalhes de um apartamento -->
            <p>&#128716 <strong>Quartos:</strong> <?php echo htmlspecialchars($detalhes_imovel['qtd_quartos']); ?> &nbsp;- &nbsp;
			<strong>Suítes:</strong> <?php echo htmlspecialchars($detalhes_imovel['qtd_suites']); ?> &nbsp;- &nbsp;
            <strong>Sala de estar:</strong> <?php echo htmlspecialchars($detalhes_imovel['qtd_sala_estar']); ?> &nbsp;- &nbsp;
            <strong>Sala de jantar:</strong> <?php echo htmlspecialchars($detalhes_imovel['qtd_sala_jantar']); ?> &nbsp;- &nbsp;
			<strong>Armários Embutidos:</strong> <?php echo htmlspecialchars($detalhes_imovel['armarios_embutidos']); ?></p> <br>
            
			
			<p>&#128663 <strong>Vagas de garagem:</strong> <?php echo htmlspecialchars($detalhes_imovel['num_vagas']); ?></p> <br>
            <p></p>
            <p>&#127980 <strong>Andar:</strong> <?php echo htmlspecialchars($detalhes_imovel['andar']); ?> º &nbsp;- &nbsp;
			<strong>Portaria 24h:</strong> <?php echo htmlspecialchars($detalhes_imovel['portaria']); ?> &nbsp;- &nbsp;
            &#128178 <strong>Valor do Condomínio:</strong> <?php echo htmlspecialchars($detalhes_imovel['valor_condominio']); ?></p><br>
            <p>&#128205 <?php echo htmlspecialchars($detalhes_imovel['descricao']); ?></p> <br>
        <?php endif; ?>
		
		<!-- Botão para abrir o modal -->
		<button type="button" id="botao" onclick="abrirModal()">Agendar Visita</button>

		<!-- Modal -->
		<div id="modal" class="modal">
			<div class="modal-content">
				<span class="close" onclick="fecharModal()">&times;</span>
				<h2>Agendar Visita</h2>
				<form id="formAgendamento" method="POST" action="processa_agendamento.php">
					<label>Nome:</label>
					<input type="text" name="nome" required>

					<label>CPF:</label>
					<input type="text" name="cpf" required>

					<label>Sexo:</label>
					<select name="sexo" required>
						<option value="Masculino">Masculino</option>
						<option value="Feminino">Feminino</option>
					</select><br>

					<label>Telefone:</label>
					<input type="text" name="telefone" required>
					
					<label>Email:</label>
					<input type="email" name="email" required> <br>

					<label>Data da Visita:</label>
					<input type="date" name="data" id="data_visita" required onchange="carregarHorarios()">

					<label>Horário:</label>
					<select name="hora" id="horario" required>
						<option value="">Selecione um horário</option>
						<!-- Opções serão preenchidas via AJAX -->
					</select>

					<input type="hidden" name="id_imovel" id="id_imovel" value=""> <!-- ID do imóvel dinâmico -->

					<button type="submit">Agendar</button>
				</form>
			</div>
		</div>
        
    </div>

    <script>
    // Variáveis para controle do carrossel
    let currentIndex = 0;
    const slides = document.querySelectorAll('.carousel-item');
    const totalSlides = slides.length;

    // Função para mover os slides
    function moveSlide(direction) {
        currentIndex += direction;
        
        if (currentIndex >= totalSlides) {
            currentIndex = 0;
        }
        if (currentIndex < 0) {
            currentIndex = totalSlides - 1;
        }

        // Atualiza a posição do carrossel
        const carouselInner = document.querySelector('.carousel-inner');
        carouselInner.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    // Configura o carrossel para iniciar na primeira imagem
    moveSlide(0);
</script>

<!-- JavaScript para abrir e fechar o modal -->
<script>

 // Obtém o ID do imóvel da URL
    function getIdImovelFromURL() {
        let params = new URLSearchParams(window.location.search);
        return params.get("id") || ""; // Retorna o ID ou uma string vazia se não existir
    }

    // Define o ID do imóvel no campo oculto
    document.getElementById("id_imovel").value = getIdImovelFromURL();

    function abrirModal() {
        document.getElementById("modal").style.display = "block";
    }

    function fecharModal() {
        document.getElementById("modal").style.display = "none";
    }

    function carregarHorarios() {
		let data = document.getElementById("data_visita").value;
		let id_imovel = document.querySelector('input[name="id_imovel"]').value;  // Obtendo o id do imóvel dinamicamente

		fetch("carregar_horarios.php?data=" + data + "&id_imovel=" + id_imovel)
			.then(response => response.json())
			.then(data => {
				let select = document.getElementById("horario");
				select.innerHTML = '<option value="">Selecione um horário</option>';
				data.forEach(horario => {
					let option = document.createElement("option");
					option.value = horario;
					option.textContent = horario;
					select.appendChild(option);
				});
			});
	}
</script>

</body>

</html>

<?php include "./footer/footer.html"; ?>
