<?php
include "./header/header.html";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Imóvel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1,h2 {
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

    <div class="container">
		<h1>Quem nós somos</h1>
		<h3>Sobre a nossa empresa</h3><br>
		<p>A Imobiliária Loc Express é uma empresa dedicada a oferecer soluções completas e personalizadas para quem 
		busca alugar imóveis. Com mais de 10 anos de experiência no mercado, nossa missão é garantir que cada transação 
		imobiliária seja realizada de forma segura, transparente e satisfatória.</p><br>
		<p>Acreditamos que a compra ou locação de um imóvel é um passo importante na vida das pessoas e, por isso, 
		trabalhamos com afinco para tornar esse processo o mais simples e seguro possível. Nossa imobiliária oferece um 
		portfólio diversificado, com imóveis para todos os perfis e orçamentos, desde apartamentos aconchegantes até grandes 
		empreendimentos comerciais.</p><br>
		<p>Nosso time de profissionais altamente capacitados e experientes está comprometido em proporcionar o melhor
		atendimento, desde a escolha do imóvel ideal até a assinatura do contrato. Trabalhamos com uma ampla variedade
		de imóveis, que atendem as suas necessidades.</p><br>
		<p>Além disso, buscamos sempre estar atualizados com as tendências do mercado imobiliário, oferecendo aos
		nossos clientes as melhores oportunidades de negócio. Nosso compromisso com a qualidade de atendimento, a
		confiança e a ética nos diferencia como uma imobiliária de referência na região.</p><br>
		<p>A Imobiliária Loc Express está pronta para realizar seus sonhos e garantir a melhor negociação para você! :)</p><br><br>
		
	
	</div>

</body>

</html>


<?php include "./footer/footer.html"; ?>