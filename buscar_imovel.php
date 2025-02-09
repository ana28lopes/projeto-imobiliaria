<?php
include "./header/header.html";
include "conexao.php";

// Captura os filtros de busca
$tipo_imovel = isset($_GET['tipo_imovel']) ? $_GET['tipo_imovel'] : '';
$cidade = isset($_GET['cidade']) ? $_GET['cidade'] : '';
$bairro = isset($_GET['bairro']) ? $_GET['bairro'] : '';

// Consulta para obter cidades e bairros
$cidades = mysqli_query($conexao, "SELECT id_cidade, descricao FROM cidade");
$bairros = mysqli_query($conexao, "SELECT id_bairro, descricao FROM bairro");
$categorias = mysqli_query($conexao, "SELECT id_categoria, descricao FROM categoria_imovel");

// Consulta para buscar imóveis
$query = "SELECT i.id_imovel, i.area, i.data_construcao, i.data_postagem, i.status, i.valor_aluguel, 
                 f.foto1, c.descricao as cidade, b.descricao as bairro, cat.descricao as categoria
          FROM imovel i
          JOIN endereco e ON i.id_endereco = e.id_endereco
          JOIN bairro b ON e.id_bairro = b.id_bairro
          JOIN cidade c ON e.id_cidade = c.id_cidade
          JOIN estado es ON e.id_estado = es.id_estado
          JOIN fotos_imovel f ON i.id_fotos = f.id_fotos
          JOIN categoria_imovel cat ON i.id_categoria = cat.id_categoria
          WHERE 1=1";

if (!empty($tipo_imovel)) {
    $query .= " AND cat.id_categoria = '" . mysqli_real_escape_string($conexao, $tipo_imovel) . "'";
}
if (!empty($cidade)) {
    $query .= " AND c.id_cidade = '" . mysqli_real_escape_string($conexao, $cidade) . "'";
}
if (!empty($bairro)) {
    $query .= " AND b.id_bairro = '" . mysqli_real_escape_string($conexao, $bairro) . "'";
}

$result = mysqli_query($conexao, $query);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loc Express</title>
    <style>
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
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #FF7E34;
            margin-bottom: 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .col {
            flex: 10;
            padding: 10px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
		
		#botao{
			
			position: relative;
			left: 1060px;
		}
		#botao2{
			
			position: relative;
			left: 240px;
		}

        button:hover {
            background-color: #E66A1F;
        }

        .mensagem {
            margin-bottom: 20px;
            font-weight: bold;
            color: green;
        }

        .erro {
            color: red;
        }
		
		
		.imoveis {
			display: grid;
			grid-template-columns: repeat(3, 1fr); /* 3 colunas */
			gap: 20px; /* Espaço entre os itens */
			padding: 20px;
		}

		.imovel {
			border: 1px solid #ddd;
			border-radius: 8px;
			padding: 15px;
			background: #f9f9f9;
			text-align: left;
			line-height: 1.8;
			font-size: 15px;
		}

		.imovel img {
			width: 100%;
			height: 300px;
			object-fit: cover;
			border-radius: 8px;
		}

		@media (max-width: 900px) {
			.imoveis {
				grid-template-columns: repeat(2, 1fr); /* 2 colunas em telas menores */
			}
		}

		@media (max-width: 600px) {
			.imoveis {
				grid-template-columns: repeat(1, 1fr); /* 1 coluna em telas pequenas */
			}
		}
    </style>
	
</head>

<body>

    <div class="container">
		<form method="GET" action="">
			<h2>Filtre sua busca</h2>
            <div class="row">
                <div class="col">
                    <label for="tipo_imovel">Tipo de Imóvel:</label>
					<select name="tipo_imovel" id="tipo_imovel">
						<option value="">Selecione</option>
						<?php while ($row = mysqli_fetch_assoc($categorias)) { ?>
							<option value="<?php echo $row['id_categoria']; ?>" <?php if ($tipo_imovel == $row['id_categoria']) echo 'selected'; ?>>
								<?php echo $row['descricao']; ?>
							</option>
						<?php } ?>
					</select>
                </div>
				<div class="col">
                    <label for="cidade">Cidade:</label>
					<select name="cidade" id="cidade">
						<option value="">Selecione</option>
						<?php while ($row = mysqli_fetch_assoc($cidades)) { ?>
							<option value="<?php echo $row['id_cidade']; ?>" <?php if ($cidade == $row['id_cidade']) echo 'selected'; ?>>
								<?php echo $row['descricao']; ?>
							</option>
						<?php } ?>
					</select>
                </div>
                <div class="col">
                    <label for="bairro">Bairro:</label>
					<select name="bairro" id="bairro">
						<option value="">Selecione</option>
						<?php while ($row = mysqli_fetch_assoc($bairros)) { ?>
							<option value="<?php echo $row['id_bairro']; ?>" <?php if ($bairro == $row['id_bairro']) echo 'selected'; ?>>
								<?php echo $row['descricao']; ?>
							</option>
						<?php } ?>
					</select>
                </div>
				
            </div>
			<button id="botao" type="button" onclick="limparFiltros()">Limpar</button>
			<button type="submit" id="botao">Buscar</button>
		
		</form>
		
		<script>
		// FUNÇÃO PARA LIMPAR OS FILTROS DA TELA ---------------------------
			function limparFiltros() {
				window.location.href = window.location.pathname; // Redireciona sem parâmetros GET
			}
		</script>
	
	</div>
	
<div class="container">
		<h2>Resultados da busca</h2>
    <div class="imoveis">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="imovel">
            <img src="<?php echo $row['foto1']; ?>" alt="Foto do imóvel">
			<p><strong>Imóvel: </strong><?php echo $row['categoria']; ?> - <?php echo $row['area']; ?> m²</p>
            <p><strong>Aluguel:</strong> R$ <?php echo $row['valor_aluguel']; ?></p>
            <p><strong>Local:</strong> <?php echo $row['bairro']; ?> - <?php echo $row['cidade']; ?></p><br>
			<form action="imovel.php" method="GET">
				<input type="hidden" name="id" value="<?php echo $row['id_imovel']; ?>">
				<button type="submit" id="botao2">Visualizar</button>
			</form>
			
        </div>
    <?php } ?>
</div>
	</div>
	

</body>

</html>


<?php include "./footer/footer.html"; ?>