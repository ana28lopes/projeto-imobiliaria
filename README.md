# Imobiliária Loc Express: Exemplo Prático de Aplicação Web

Este é um projeto de site de uma imobiliária, desenvolvido para fins acadêmicos. O objetivo é criar uma aplicação Web que contenha um formulário para cadastramento dos imóveis disponíveis para locação de uma imobiliária, com funcionalidades de filtro, exibição dos resultados e agendamento de visitas. 

Este é um trabalho apresentado à disciplina de Laboratório de Engenharia de Software I, do curso de graduação em Engenharia de Computação do Centro Federal de Educação Tecnológica de Minas Gerais - Campus Nova Gameleira, como requisito para obtenção de nota. 

**Alunos**: Ana Clara (20193010017), Custodio Junior (20213012900), Thiago Ribeiro (20203001484)

## Tecnologias Utilizadas
- **PHP**: Linguagem de programação para o back-end.
- **MySQL**: Banco de dados relacional para armazenar as informações.
- **HTML**: Linguagem de marcação para a estrutura das páginas.
- **CSS**: Estilos visuais para a interface do site.
- **JavaScript**: Para validação de formulários e interatividade.
- **WampServer**: Para criar um ambiente de desenvolvimento local para aplicações web.

## Funcionalidades
- **Cadastro de Imóveis**: Permite que os locadores cadastrem imóveis com informações como tipo do imóvel (casa ou apartamento), valor do aluguel, localização, descrição e etc.
- **Busca de Imóveis**: Os usuários podem filtrar imóveis por tipo (casa ou apartamento), cidade, e bairro.
- **Exibição de Detalhes**: Os imóveis encontrados são exibidos com suas informações detalhadas, como imagens, descrição, preço, localização e etc.
- **Agendamento de Visitas**: Os locatários podem agendar visitas referente aos imóveis que desejam visitar.

O sistema é dividido em várias partes, cada uma com sua funcionalidade específica. Abaixo estão algumas imagens ilustrando o fluxo de uso.

- ### 01 - Página Inicial
A página inicial do sistema apresenta o header com o menu e um filtro para selecionar o tipo de imóvel. O layout é simples e intuitivo.

![image](https://github.com/user-attachments/assets/1472a62d-ae85-4d0d-ad94-f9bc1a4035b7)

- ### 02 - Cadastrar Imóvel
Na tela de anunciar o imóvel, o locador preenche algumas informações, incluindo tipo do imóvel, endereço, informações adicionais sobre o imóvel, fotos e etc.

![image](https://github.com/user-attachments/assets/ca0281d5-a9db-4210-8b6b-480d514be829)

- ### 03 - Buscar Imóvel
Na tela de busca, o usuário pode selecionar o tipo de imóvel, cidade e bairro. Isso ajuda a refinar a busca para encontrar o imóvel ideal.

![image](https://github.com/user-attachments/assets/b1235b34-7508-401f-92bb-84d22d6fdc79)

- ### 04 - Detalhes do Imóvel
Quando o usuário clica em um imóvel, ele é redirecionado para uma página com detalhes completos, incluindo imagens, descrição, preço, localização e etc.

![image](https://github.com/user-attachments/assets/375f8cba-0734-4944-a049-715b4557d569)

- ### 05 - Agendar Visita
Quando o locatário clica em agendar uma visita em um determinado imóvel, abre-se uma tela para que ele possa preencher algumas informações, selecionar data e hora.

![image](https://github.com/user-attachments/assets/94ded3c4-2f54-4ede-97f2-70320cb7dc6c)

## Executando o Sistema
Para rodar este projeto localmente, siga os passos abaixo:

1. **Instale o WampServer**:
   - Baixe e instale o [WampServer](https://www.wampserver.com/), que inclui o Apache, PHP e MySQL.

2. **Clone este repositório**:
    ```bash
    git clone https://github.com/ana28lopes/projeto-imobiliaria.git
    ```

3. **Configure o Banco de Dados**:
   - Abra o **phpMyAdmin** no seu navegador, acessando `http://localhost/phpmyadmin/`.
   - Crie um novo banco de dados no phpMyAdmin (create database `imobiliaria`;).
   - Crie as tabelas e os atributos necessários.

4. **Configuração do Banco de Dados**:
   - Edite o arquivo de configuração `conexao.php` para incluir suas credenciais de banco de dados.

5. **Rodando o Projeto**:
   - Coloque os arquivos do projeto na pasta `www` do WampServer (geralmente localizada em `C:\wamp64\www`).
   - Acesse a página principal através do navegador: `http://localhost/imobiliaria/index.php`.

6. **Acesse o Sistema**:
   - Após configurar o banco de dados e os arquivos, você pode acessar o sistema localmente e começar a usar.

## Implementações Futuras - Sugeridas

### 1. **Controle da Imobiliária (Aprovação de Anúncios)**
A imobiliária poderá ter um login no sistema para aprovar ou reprovar os anúncios de imóveis inseridos pelos locadores. Isso permitirá que os administradores validem os imóveis antes de eles serem exibidos para os usuários, garantindo maior controle sobre o conteúdo do site.
  
### 2. **Ativar ou Desativar Imóveis Alugados**
Será possível ativar ou desativar os imóveis que já foram alugados. Imóveis alugados aparecerão como "indisponíveis", ajudando a evitar a exibição de imóveis que não estão mais disponíveis para aluguel.

### 3. **Login para Locatários e Locadores**
Tanto os locatários quanto os locadores terão login e área restrita para controle das suas informações. Isso permitirá que cada usuário gerencie os seus anúncios, consulte o histórico de locações e edite suas informações pessoais. <br>

Essas funcionalidades são sugestões de futuras implementações que podem ser feitas, visando melhorar a experiência dos usuários e dar maior controle à imobiliária sobre os imóveis e usuários cadastrados. 

## Comentários Finais
Este projeto tem como objetivo a criação de um sistema simples para uma imobiliária, permitindo a gestão de imóveis e a interação com locatários e locadores. Durante o desenvolvimento, foi possível aplicar conceitos fundamentais de programação web, como o uso de PHP para o back-end, MySQL para o banco de dados, HTML e CSS para a construção da interface do usuário.

O trabalho contribui para o aprendizado prático de como construir um sistema real, utilizando ferramentas populares como o WampServer e phpMyAdmin. Além disso, este projeto permite um melhor entendimento sobre a implementação de funcionalidades que podem ser aplicadas em sistemas reais de mercado, como o controle de anúncios, gestão de imóveis, cadastros e o sistema de login de usuários.

Com as implementações futuras propostas, o sistema tem grande potencial para se tornar uma plataforma mais robusta e eficiente para o gerenciamento de imóveis.

## Créditos
Este exercício prático, incluindo o seu código, foi elaborado por Ana Clara, Custodio e Thiago, alunos de graduação do CEFET-MG, como parte das suas atividades na disciplina de Laboratório de Engenharia de Software, sob orientação do Prof. Eduardo Campos.

O código deste repositório possui uma licença MIT.
