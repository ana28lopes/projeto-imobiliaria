<?php
include "./header/header.html";
include "conexao.php";

// Lógica para salvar os dados no banco de dados
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    // Iniciar transação
    $conexao->begin_transaction();

    // Inserir Endereço
    $sql1 = "INSERT INTO endereco (logradouro, numero, complemento, cep, id_bairro, id_cidade, id_estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt1 = $conexao->prepare($sql1);
    $stmt1->bind_param("sisiiii", $_POST['logradouro'], $_POST['numero'], $_POST['complemento'], $_POST['cep'], $_POST['bairro'], $_POST['cidade'], $_POST['estado']);
    $stmt1->execute();
    $id_endereco = $conexao->insert_id;

    // Inserir Pessoa
    $sql2 = "INSERT INTO pessoa (cpf, nome, sexo, telefone, id_endereco) VALUES (?, ?, ?, ?, ?)";
    $stmt2 = $conexao->prepare($sql2);
    $stmt2->bind_param("issii", $_POST['cpf'], $_POST['nome'], $_POST['sexo'], $_POST['telefone'], $id_endereco);
    $stmt2->execute();
    $id_pessoa = $conexao->insert_id;

    // Inserir Locador
    $sql3 = "INSERT INTO locador (email, cnpj, data_cadastro, cpf_pessoa, id_pessoa) VALUES (?, ?, NOW(), ?, ?)";
    $stmt3 = $conexao->prepare($sql3);
    $stmt3->bind_param("siii", $_POST['email'], $_POST['cnpj'], $_POST['cpf'], $id_pessoa);
    $stmt3->execute();
    $id_locador = $conexao->insert_id;

	// INSERIR FOTOS ----------------------
    // Defina o diretório onde as fotos serão armazenadas
	$diretorio = "uploads/"; // Exemplo de diretório onde as fotos serão salvas

	// Certifique-se de que o diretório exista
	if (!is_dir($diretorio)) {
		mkdir($diretorio, 0777, true);
	}

	// Caminhos das fotos
	$fotos = [];
	for ($i = 1; $i <= 5; $i++) {
		if (isset($_FILES["fotos$i"]) && $_FILES["fotos$i"]["error"] === UPLOAD_ERR_OK) {
			$nomeArquivo = basename($_FILES["fotos$i"]["name"]);
			$caminhoArquivo = $diretorio . $nomeArquivo;

			// Move o arquivo para o diretório
			if (move_uploaded_file($_FILES["fotos$i"]["tmp_name"], $caminhoArquivo)) {
				$fotos[$i] = $caminhoArquivo; // Salva o caminho do arquivo
			} else {
				$fotos[$i] = null; // Caso o upload falhe, atribui null
			}
		} else {
			$fotos[$i] = null; // Caso o arquivo não tenha sido enviado
		}
	}
	// Preparar o SQL para inserção no banco de dados
	$sql4 = "INSERT INTO fotos_imovel (foto1, foto2, foto3, foto4, foto5) VALUES (?, ?, ?, ?, ?)";
	$stmt4 = $conexao->prepare($sql4);
	// Vincula os parâmetros
	$stmt4->bind_param("sssss", $fotos[1], $fotos[2], $fotos[3], $fotos[4], $fotos[5]);
	// Executa a inserção
	$stmt4->execute();
	$id_fotos = $conexao->insert_id;


    // Inserir Imóvel ----------------------------------
	$area = $_POST['area'];
	$data_construcao = date('Y-m-d', strtotime($_POST['data_construcao'])); // Formata para o formato de data
	$data_postagem = date('Y-m-d'); // Obtém a data atual no formato YYYY-MM-DD
	$status = 'ativo';
	$valor_aluguel = str_replace(["R$", ".", ","], ["", "", "."], $_POST['aluguel']); 
	$id_categoria = intval($_POST['categoria']); // Garante que seja um número inteiro


    $sql5 = "INSERT INTO imovel (area, data_construcao, data_postagem, status, valor_aluguel, id_locador, id_endereco, id_categoria, id_fotos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt5 = $conexao->prepare($sql5);
    $stmt5->bind_param("sssssiiii", $area, $data_construcao, $data_postagem, $status, $valor_aluguel, $id_locador, $id_endereco, $id_categoria, $id_fotos);
	$stmt5->execute();
    $id_imovel = $conexao->insert_id;

    // Inserir na Categoria Correta ------------------------------------
    if ($_POST['categoria'] == 1) { // Apartamento
        $sql6 = "INSERT INTO apartamento (qtd_quartos, qtd_suites, qtd_sala_estar, qtd_sala_jantar, num_vagas, armarios_embutidos, descricao, andar, valor_condominio, portaria, id_imovel) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt6 = $conexao->prepare($sql6);
		$stmt6->bind_param("iiiiississi", $_POST['quartos'], $_POST['suites'], $_POST['salaestar'], $_POST['salajantar'], $_POST['garagem'], $_POST['armario'], $_POST['descricao'], $_POST['andar'], $_POST['condominio'], $_POST['portaria'], $id_imovel);
		$stmt6->execute();

	} else { // Casa
        $sql6 = "INSERT INTO casa (qtd_quartos, qtd_suites, qtd_salas, num_vagas, armarios_embutidos, descricao, id_imovel) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$stmt6 = $conexao->prepare($sql6);
		$stmt6->bind_param("iiiissi", $_POST['quartos'], $_POST['suites'], $_POST['salaestar'], $_POST['garagem'], $_POST['armario'], $_POST['descricao'], $id_imovel);
		$stmt6->execute();
	}

    // **Se tudo der certo, confirmar (COMMIT)**
    $conexao->commit();

    echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='anunciar_imovel.php';</script>";
} catch (Exception $e) {
	
    // **Se der erro, cancelar tudo (ROLLBACK)**
    $conexao->rollback();
    //echo "Erro ao cadastrar: " . $e->getMessage();
}
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
            max-width: 1000px;
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


    </style>
</head>
<body>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
		<h2>Insira seus dados</h2>
            <div class="row">
                <div class="col">
                    <label for="nome">Nome completo</label>
                    <input type="text" name="nome" id="nome" required>
                </div>
				<div class="col">
                    <label for="cpf">Cpf</label>
                    <input type="text" name="cpf" id="cpf" required>
                </div>
				<div class="col">
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" id="telefone" required>
                </div>
            </div>
			<div class="row">
                <div class="col">
                    <label for="sexo">Sexo</label>
                    <select name="sexo" id="sexo" required>
						<option value="">Selecione</option>
                        <option value="feminino">Feminino</option>
						<option value="masculino">Masculino</option>
                    </select>
                </div>			
				<div class="col">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required>
                </div>
				<div class="col">
                    <label for="cnpj">Cnpj (Não obrigatório)</label>
                    <input type="text" name="cnpj" id="cnpj">
                </div>
            </div>
		
		
            <h2>Qual tipo de imóvel?</h2>
            <div class="row">
                <div class="col">
                    <label for="categoria">Categoria</label>
                    <select name="categoria" id="categoria" required>
                        <option value="">Selecione</option>
                        <?php
                        $result = $conexao->query("SELECT id_categoria, descricao FROM categoria_imovel");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id_categoria']}'>{$row['descricao']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <h2>Onde ele está localizado?</h2>
            <div class="row">
                <div class="col">
                    <label for="cep">CEP</label>
                    <input type="text" name="cep" id="cep" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="logradouro">Logradouro</label>
                    <input type="text" name="logradouro" id="logradouro" required>
                </div>
                <div class="col">
                    <label for="numero">Número</label>
                    <input type="text" name="numero" id="numero" required>
                </div>
                <div class="col">
                    <label for="complemento">Complemento</label>
                    <input type="text" name="complemento" id="complemento">
                </div>
            </div>
			<div class="row">
			<div class="col">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" required>
                        <option value="">Selecione</option>
						<?php
                        $result = $conexao->query("SELECT id_estado, descricao FROM estado");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id_estado']}'>{$row['descricao']}</option>";
                        }
                        ?>
                    </select>
                </div>
				<div class="col">
                    <label for="cidade">Cidade</label>
                    <select name="cidade" id="cidade" required>
                        <option value="">Selecione</option>
                    </select>
                </div>
                <div class="col">
                    <label for="bairro">Bairro</label>
                    <select name="bairro" id="bairro" required>
                        <option value="">Selecione</option>
						
                    </select>
                </div>
                
            </div>

					<script>
					// CONVERTE OS CAMPOS DE VALORES EM REAIS ------------------------------
						function formatarMoeda(campo) {
							let valor = campo.value;
							
							// Remove tudo que não for número ou ponto decimal
							valor = valor.replace(/\D/g, ""); 

							// Converte para número e formata com duas casas decimais
							valor = (parseFloat(valor) / 100).toFixed(2);

							// Substitui o ponto por vírgula e adiciona R$
							campo.value = "R$ " + valor.replace(".", ",");
						}
						</script>

            <h2>Informações sobre o imóvel</h2>
			<div class="row">
                <div class="col">
                    <label for="area">Área (em metros quadrados)</label>
                    <input type="text" name="area" id="area" required>
					<script>
						$(document).ready(function(){
						$('#area').mask('#.##0,00', {reverse: true});
						});
					</script>
                </div>
				<div class="col">
                    <label for="area">Data da construção</label>
                    <input type="date" name="data_construcao" required>
                </div>
                <div class="col">
                    <label for="aluguel">Valor do aluguel</label>
                    <input type="text" id="aluguel" name="aluguel" required onkeyup="formatarMoeda(this)">
						
                </div>
            </div>
			<div class="row">
                <div class="col">
                    <label for="quartos">Quantidade de quartos</label>
                    <input type="number" name="quartos" id="quartos" required>
                </div>
                <div class="col">
                    <label for="suites">Quantidade de suítes</label>
                    <input type="number" name="suites" id="suites" required>
                </div>
                <div class="col">
                    <label for="salaestar">Quantidade de salas de estar</label>
                    <input type="number" name="salaestar" id="salaestar" required>
                </div>
            </div>
			<div class="row">
                <div class="col">
                    <label for="garagem">Quantidade de vagas de garagem</label>
                    <input type="number" name="garagem" id="garagem" required>
                </div>
               <div class="col">
					<label for="armario">Possui armário embutido?</label>
					<select name="armario" id="armario" required>
						<option value="">Selecione</option>
                        <option value="sim">Sim</option>
						<option value="nao">Não</option>
                    </select>
				</div>
            </div>
			
			<h2>* Informações adicionais apenas para cadastro de apartamento</h2>
			<div class="row">
                <div class="col">
                    <label for="salajantar">Quantidade de salas de jantar</label>
                    <input type="number" name="salajantar" id="salajantar">
                </div>
                <div class="col">
                    <label for="andar">Em qual andar seu imóvel está</label>
                    <input type="number" name="andar" id="andar">
                </div>
                
				<div class="col">
					<label for="portaria">Possui portaria 24h?</label>
					<select name="portaria" id="portaria">
						<option value="">Selecione</option>
                        <option value="sim">Sim</option>
						<option value="nao">Não</option>
                    </select>
				</div>
            </div>
			<div class="row">
                <div class="col">
                    <label for="condominio">Valor do condomínio</label>
                    <input type="text" id="condominio" name="condominio" required onkeyup="formatarMoeda(this)">
                </div>
            </div>
			
			<h2>Insira fotos do imóvel</h2>
			<div class="row">
                <div class="col">
                    <label for="fotos">Carregar 5 fotos (obrigatório*):</label>
					<input type="file" id="fotos1" name="fotos1" required>
					<input type="file" id="fotos2" name="fotos2" required>
					<input type="file" id="fotos3" name="fotos3" required>
					<input type="file" id="fotos4" name="fotos4" required>
					<input type="file" id="fotos5" name="fotos5" required>
                </div>
            </div>
			<h2>Outras informações</h2>
			<div class="row">
				<label for="garagem">Descrição (algum detalhe a mais que deseja informar sobre o imóvel)</label>
				<textarea name="descricao" id="descricao" rows="3" required></textarea>
			</div>
            <button type="submit">Cadastrar Imóvel</button>
        </form>
    </div>

    <!-- Footer personalizado -->
    <footer>
        <?php
			include "./footer/footer.html";
		?>
    </footer>
	
	<!-- Adicionar jQuery para AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// ---------------------- CARREGA CIDADE E BAIRRO --------------------------------------------
$(document).ready(function() {
    // Quando o estado for alterado, carregar as cidades
    $('#estado').change(function() {
        var id_estado = $(this).val();
        $('#cidade').html('<option value="">Carregando...</option>');

        if (id_estado) {
            $.ajax({
                type: 'POST',
                url: 'get_cidades.php',
                data: {id_estado: id_estado},
                success: function(response) {
                    $('#cidade').html(response);
                    $('#bairro').html('<option value="">Selecione</option>'); // Resetar bairros
                }
            });
        } else {
            $('#cidade').html('<option value="">Selecione</option>');
            $('#bairro').html('<option value="">Selecione</option>');
        }
    });

    // Quando a cidade for alterada, carregar os bairros
    $('#cidade').change(function() {
        var id_cidade = $(this).val();
        $('#bairro').html('<option value="">Carregando...</option>');

        if (id_cidade) {
            $.ajax({
                type: 'POST',
                url: 'get_bairros.php',
                data: {id_cidade: id_cidade},
                success: function(response) {
                    $('#bairro').html(response);
                }
            });
        } else {
            $('#bairro').html('<option value="">Selecione</option>');
        }
    });
});

// -------------------- DESABILITA ALGUNS CAMPOS DE ACORDO COM A CATEGORIA --------------------------------
document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("categoria").addEventListener("change", function () {
                var categoria = this.value;

                // Obtém os campos
                var salajantar = document.getElementById("salajantar");
                var andar = document.getElementById("andar");
                var portaria = document.getElementById("portaria");
				var condominio = document.getElementById("condominio");

                // Ativa todos os campos primeiro
                salajantar.disabled = false;
                andar.disabled = false;
                portaria.disabled = false;
				condominio.disabled = false;

                // Se for "terreno", desativa "quartos" e "garagem"
                if (categoria === "2") {
                    salajantar.disabled = true;
                    andar.disabled = true;
					portaria.disabled = true;
					condominio.disabled = true;
                }

            });
        });
</script>
	
</body>
</html>
