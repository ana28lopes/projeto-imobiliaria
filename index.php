<?php
include "./header/header.html";
include "conexao.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imobiliária</title>
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

        /* Estilos do formulário */
        .search-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 30px;
            position: absolute;
            top: 45%;
            left: 32%;
            transform: translateX(-50%, -50%);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .search-container select {
            border: none;
            padding: 10px;
            border-radius: 20px;
            outline: none;
            width: 200px;
        }

        .search-container button {
            background-color: #FF7E34;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .search-container button:hover {
            background-color: #e96a20;
        }
    </style>
</head>

<body>

    <!-- Conteúdo principal -->
    <div id="main">
        <img src="./img/main.png" width="1900" height="980">

        <!-- Formulário de busca -->
        <form class="search-container" action="buscar_imovel.php" method="GET">

            <select name="tipo_imovel">
                <option value="">Tipo de imóvel</option>
					<?php
                        $result = $conexao->query("SELECT id_categoria, descricao FROM categoria_imovel");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id_categoria']}'>{$row['descricao']}</option>";
                        }
                    ?>
            </select>

            <select name="cidade" id="cidade">
                <option value="">Em qual cidade</option>
						<?php
                        $result = $conexao->query("SELECT id_cidade, descricao FROM cidade");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id_cidade']}'>{$row['descricao']}</option>";
                        }
                        ?>
            </select>

            <select name="bairro" id="bairro">
                <option value="">Em qual bairro</option>
                
            </select>

            <button type="submit">Buscar</button>
        </form>
		
    </div>

	<!-- Adicionar jQuery para AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// ---------------------- CARREGA CIDADE E BAIRRO --------------------------------------------
$(document).ready(function() {

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
            $('#bairro').html('<option value="">Em qual bairro</option>');
        }
    });
});
</script>

</body>

</html>


<?php include "./footer/footer.html"; ?>
