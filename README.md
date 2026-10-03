# Projeto Loja Virtual
O Projeto Loja Virtual é composto de um sistema monolítico, onde todos os componentes acessam as tabelas de uma única base de dados.<br><br>O funcionamento da loja virtual, basicamente resume-se de um usuário administrador (o lojista) cadastrar os produtos e classificando em categorias e subcategorias.<br><br>Para os usuários poderem efetuar compras desses produtos, eles precisam se cadastrar como clientes (consumidores), e adicionar o(s) produto(s) ao seu carrinho de compras. Para que cada cliente possa finalizar uma compra, ele deve cadastrar o seu telefone e endereço para entrega dos produtos.<br><br>No painel principal da loja virtual, será exibido todos os produtos e suas unidades adicionadas no carrinho de compras. Quando o cliente clica no botão Comprar, ele será redirecionado à página de checkout, onde ele poderá escolher o endereço de entrega previamente cadastrado por ele. Ainda nessa mesma página de checkout, o cliente também poderá escolher o tipo de frete, e as opções de pagamento (cartão de crédito, boleto bancário ou PIX). Após essas etapas do checkout, o cliente poderá rever os detalhes do seu pedido, em seguida finalizando a compra clicando no botão FINALIZAR PAGAMENTO. O cliente recebe uma confirmação de compra realizada, e o carrinho vira um pedido, esvaziando o carrinho. O cliente poderá acompanhar o(s) seu(s) pedido(s) (sua(s) compra(s) realizada(s) na seção Meus Pedidos).<br><br>Assim como o cliente, o usuário administrador da loja virtual, pode realizar uma busca de pedidos gerado pelos clientes nos dias pesquisados.<br><br>Esse projeto da loja virtual envolveu diferentes tipos de comunicação do backend ao frontend como atualização de informação de partes específicas, sem que a página fosse devidamente recarregada perdendo as outras informações, e economizando consultas desnecessárias ao banco de dados graças ao Ajax. Também foi explorada a interação de dados retornados em PHP e expostas por propriedades em HTML para ser capturado em JavaScript/Jquery.

## Tecnologias utilizadas:
- Frontend:
   - HTML
   - CSS
   - Bootstrap (framework baseado em CSS/JavaScript para criar sites e aplicações web responsivas)
   - JavaScript
   - jQuery (biblioteca JavaScript para manipulação do DOM, e envio/recebimento de requisições HTTP via Ajax)
   - Fontawesome (biblioteca de ícones vetoriais baseada em CSS e LESS)
   - Maskedinput.js (biblioteca JavaScript para a criação de máscaras para campos de formulário)
- Backend:
   - PHP
   - Banco de Dados MySQL
-----------------------------------------------------------------------------
## Padrões de programação:
   - MVC
      - Backend:
         - Camada dos Controladores (Controllers)
            - Classes responsáveis por receber/retornar os dados contidos nas requisições HTTP ao frontend (e também via Ajax); encaminhar essas requisições para as Camadas dos Serviços (Services).
         - Camada dos Serviços (Services)
            - Classes responsáveis do acesso ao banco de dados MySQL Relacional
         - Camada dos Modelos (Models)
            - Classes responsáveis em manipular os dados das entidades (representadas por tabelas) do banco de dados MySQL Relacional
      - Frontend:
         - Camada das Views
            - Partes e fragmentos das páginas HTML que compõe o visual da Loja Virtual
--------------------------------------------------------------------------------
# Instruções de Uso
## Windows:
## 2) Baixar o Projeto Loja Virtual do Git Hub
- Abrir o prompt de comando como <b>Modo Administrador</b>
- acessar a raiz da unidade C: digitando <b>`cd c:\`</b>
- clonar o Projeto da Loja Virtual digitando:<br><b>`git clone https://github.com/medrina/loja-virtual.git`</b>

## 1) Criar o Banco de Dados e as Tabelas que compõem o funcionamento do Sistema:
- O Projeto da Loja Virtual armazena os dados em geral através de um sistema de Banco de Dados MySQL Relacional
- A Loja Virtual é compatível com os bancos MySQL Server e MariaDB

<b>NOTA 1: </b> Execução Manual:
- No Projeto loja-virtual, abra o arquivo <b>c:\loja-virtual\docs\Banco-de-Dados>loja - Tabelas.txt</b>
- Abrir a ferramenta de interface gráfica (como o <b>MySQL Workbench</b>) que acesse o banco de dados instalado no seu computador
- Dentro do arquivo <b>loja - Tabelas.txt</b> , copie todas as instruções de comandos para criar o banco de dados, todas as tabelas, e referências das chaves primárias com as chaves estrangeiras, cole e execute na ferramenta de interface gráfica.
- ao final da execução de todos os comandos desse arquivo <b>loja - Tabelas.txt</b> , visualize todas as tabelas, digite: `show tables;` e execute.<br>
- a ferramenta de interface gráfica irá listar as 24 tabelas

<b>NOTA 2: </b>se o seu banco de dados estiver configurado nas variáveis de ambiente, você pode executar o script contendo instruções de comandos em SQL para 
criar o banco de dados, todas as tabelas, e referências das chaves primárias com as chaves estrangeiras;
- Abrir o prompt de comando
- digitar e executar `cd c:\`
- Caso o banco de dados seja o MySQL Server, digitar e executar:<br>
`mysql -u root -p < c:\loja-virtual\docs\Banco-de-Dados\tabelas.sql`<br>
O MySQL vai pedir a senha de root, e você deve informar. Após isso, o MySQL irá criar o banco, as tabelas e as referências das chaves primárias com as chaves estrangeiras.

- Caso o banco de dados seja o MariaDB, digitar e executar:<br>
`mariadb -u root -p < c:\loja-virtual\docs\Banco-de-Dados\tabelas.sql`<br>
O MariaDB vai pedir a senha de root, e você deve informar. Após isso, o MariaDB irá criar o banco, as tabelas e as referências das chaves primárias com as chaves estrangeiras.<br>

<b>NOTA 3: </b>se o seu banco de dados não estiver configurado nas variáveis de ambiente, você deve localizar onde o Windows instalou o banco de dados.

<b>MySQL Server:</b>
- Estando no prompt de comando, acesse a pasta bin, digite: `cd c:\Program Files\MySQL\MySQL Server X.X\bin\` e execute.<br> <i>(substitua a inscrição X.X pelo Nº da versão do banco de dados)</i>
- dentro da pasta bin, digite e execute: `mysql -u root -p < c:\loja-virtual\docs\Banco-de-Dados\tabelas.sql`<br>
O MySQL vai pedir a senha de root, e você deve informar. Após isso, o MySQL irá criar o banco, as tabelas e as referências das chaves primárias com as chaves estrangeiras.<br>
- para verificar se o MySQL criou as tabelas, digitar e executar:
`mysql -u root -p` informar a senha de root<br>
- selecionar o banco de dados da loja virtual <b>loja</b> dentro do console do MySQL, digite: `use loja;` e execute<br>
- visualizar todas as tabelas do banco de dados <b>loja</b>, digite: `show databases;` e execute<br>
- O MySQL deverá listar as 24 tabelas do banco de dados loja.
- para voltar ao prompt do windows, digitar: `exit`

<b>MariaDB:</b>
- No prompt de comando, acesse a pasta bin, digite: `cd c:\Program Files\MariaDB X.X\bin\` e execute.<br> <i>(substitua a inscrição X.X pelo Nº da versão do banco de dados)</i>
- dentro da pasta bin, digite e execute: `mariadb -u root -p < c:\loja-virtual\docs\Banco-de-Dados\tabelas.sql`<br>
O MariaDB vai pedir a senha de root, e você deve informar. Após isso, o MariaDB irá criar o banco, as tabelas e as referências das chaves primárias com as chaves estrangeiras.<br>
- para verificar se o MariaDB criou as tabelas, digitar e executar:
`mariadb -u root -p` informar a senha de root<br>
- selecionar o banco de dados da loja virtual <b>loja</b>dentro do console do MariaDB, digite: `use loja;` e execute<br>
- visualizar todas as tabelas do banco de dados <b>loja</b>, digite: `show databases;` e execute<br>
- O MariaDB deverá listar as 24 tabelas do banco de dados loja.
- para voltar ao prompt do windows, digitar: `exit`
-----------------------------------------------------------------------------------
## 3) Configurar informações do Banco de Dados MySQL no arquivo de configuração do Projeto Loja Virtual
- No Projeto loja-virtual, abra o arquivo <b>`\loja-virtual\config\Connection.php`</b>
- Dentro do arquivo "\loja-virtual\config\Connection.php" , preencha o nome de usuário e senha nos atributos <b>`$usuario`</b> e <b>`$senha`</b> da Classe Connection
- o nome de usuário e a senha, deverão ser informados no formato string

Exemplos de como deve ficar a configuração:<br><br>
Ex1:<br>
No Banco de Dados MySQL / MariaDB:<br>
nome de usuário = admin<br>senha do usuário = admin<br><br>
No arquivo Connection.php<br>
private $dsn = 'mysql:host=localhost;dbname=loja';<br>
private $usuario = 'admin';<br>
private $senha = 'admin';<hr>
Ex2:<br>
No Banco de Dados MySQL / MariaDB:<br>
nome de usuário = root<br>senha do usuário = 12345<br><br>
No arquivo Connection.php<br>
private $dsn = 'mysql:host=localhost;dbname=loja';<br>
private $usuario = 'root';<br>
private $senha = '12345';<br>

- após a digitação nos atributos <b>`$usuario`</b> e <b>`$senha`</b> , salve o arquivo Connection.php e feche-o
------------------------------------------------------------------------------------
## 4) Iniciar o Servidor PHP
- abrir o prompt de comando no Windows
- acessar e entrar dentro da pasta "public" digitando: <b>`c:\loja-virtual\public`</b>
- estando dentro da pasta public da Loja Virtual, digitar: <b>php -S localhost:8000</b>

<b>NOTA:</b> certifique-se que a porta 8000 não esteja em uso por outro programa no momento. Se porventura, a porta 8000 estiver em uso, você necessitará usar outra porta
- Para ver a lista de portas em que o sist. operacional Windows não esteja utilizando, digitar dentro do prompt de comando: <b>`netstat -ano`</b>. No resultado do comando mencionado, será exibida uma lista de portas em que o sist. operacional Windows está usando. As portas em que estiverem sendo usadas, a coluna Estado estará com o valor: <b>`LISTENING`</b>. As portas que não estiverem sendo utilizadas pelo sist. operacional Windows, não estarão listadas no resultado.<br><br>Portanto, você poderá utilizar a porta que não esteja aparecendo no resultado do comando `netstat -ano` na execução da Loja Virtual.
-----------------------------------------------------------------------------------
## 5) Iniciar a Loja Virtual
- após iniciar o servidor PHP pelo prompt, abra o navegador de sua preferência, e digite na url: http://localhost:8000 e aperte a tecla enter
- será apresentada a página home da Loja Virtual
- para encerrar a conexão do servidor PHP no prompt de comando do Projeto Loja Virtual, você deverá pressionar uma combinação de teclas no prompt: <b>`Ctrl + C`</b>  , e em seguida, o servidor PHP é 
encerrado e o prompt de comando é liberado.
-----------------------------------------------------------------------------------

## Linux Debian/Ubuntu
## 1) Abrir o terminal
## 2) clonar o Projeto da Loja Virtual
- acessar o diretório do seu usuário digitando:<br><b>`cd /home/$USER`</b>
- clonar o Projeto da Loja Virtual digitando:<br><b>`sudo git clone https://github.com/medrina/loja-virtual.git`</b>
- será gerado o diretório: loja-virtual
## 3) Instalação do banco de dados
- no terminal, para baixar e instalar o banco de dados MariaDB, digitar:<br><b>`sudo apt install mariadb-server -y`</b>
- acessar o console do banco de dados MariaDB, digitando:<br><b>`sudo mariadb`</b>
- definir senha de usuário root do MariaDB gerada por você digitando:<br><b>`ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD('NOVA_SENHA');`</b>
<br><b>- NOTA: você deve digitar a sua NOVA_SENHA dentro dos parênteses com aspas simples, (ver exemplos abaixo)</b><br><hr>
Exemplo 1: se a senha definida for root, então a sintaxe do comando fica assim:<br><b>`ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD('root');`</b><br><br>Exemplo 2: se a senha definida for admin, então a sintaxe do comando fica assim:<br><b>`ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD('admin');`</b><br><br>Exemplo 3: se a senha definida for 12345, então a sintaxe do comando fica assim:<br><b>`ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD('12345');`</b><hr>
- aplicar a criação da <b>NOVA_SENHA</b> do root do banco de dados MariaDB digitando:<br><b>`FLUSH PRIVILEGES;`</b>
- sair do console do MariaDB digitando:<br><b>`exit`</b>
## 4) Criação das tabelas do banco de dados
- executar o script de criação do banco de dados loja e as tabelas, digitando:<br>
<b>`sudo mariadb -u root -p < /home/$USER/loja-virtual/docs/Banco-de-Dados/tabelas.sql`</b>
- <b>NOTA: o MariaDB irá pedir a senha de root que você definiu na etapa anterior</b>
-----------------------------------------------------------------------------------
## 5) Aplicar permissões ao Projeto da Loja Virtual
- conceder permissão do usuário local para todo diretório do projeto digitando:<br><b>`sudo chown -R $USER:$USER loja-virtual/`</b>
## 6) Configurando a conexão com o banco de dados MariaDB
- configurar o nome da base de dados, usuário e senha no arquivo de conexão ao MariaDB. Editar o arquivo `\loja-virtual\config\Connection.php` com o seu editor: vim, nano, VS Code,...
- Dentro do arquivo `\loja-virtual\config\Connection.php` , digite definindo o nome de usuário e senha (que você definiu no Banco de Dados MariaDB) nos atributos <b>`$usuario`</b> e <b>`$senha`</b> da Classe Connection
- <b>NOTA: o nome de `$usuário` e a `$senha`, deverão ser informados no formato string (dentro de aspas simples, como nos exemplos abaixo)</b>

Exemplos de como deve ficar a configuração:<br><br>
Exemplo 1:<br>
No Banco de Dados MariaDB:<br>
nome de usuário = root<br>senha do usuário = root<br><br>
No arquivo Connection.php<br>
`private $dsn = 'mysql:host=localhost;dbname=loja';`<br>
`private $usuario = 'root';`<br>
`private $senha = 'root';`<hr>
Exemplo 2:<br>
No Banco de Dados MariaDB:<br>
nome de usuário = root<br>senha do usuário = admin<br><br>
No arquivo Connection.php<br>
`private $dsn = 'mysql:host=localhost;dbname=loja';`<br>
`private $usuario = 'root';`<br>
`private  $senha = 'admin';`<hr>

Exemplo 3:<br>
No Banco de Dados MariaDB:<br>
nome de usuário = root<br>senha do usuário = 12345<br><br>
No arquivo Connection.php<br>
`private $dsn = 'mysql:host=localhost;dbname=loja';`<br>
`private $usuario = 'root';`<br>
`private  $senha = '12345';`<br>

- após a digitação nos atributos `$usuario` e `$senha`, salve o arquivo `Connection.php` e feche-o
- acessar a pasta public da loja-virtual digitando:<br><b>`cd /home/$USER/loja-virtual/public`</b>
- inicializar o servidor PHP digitando:<br><b>`sudo php -S localhost:8000`</b>
- acessar no navegador digitando na URL:<br><b>localhost:8000</b>
- deverá mostrar a página home apenas com o botão de Login
-----------------------------------------------------------------------------------
# Informações Complementares:
## Informações Técnicas:
A Loja Virtual consiste em 2 partes: Home e o Painel.
- Home: O Home consiste na página inicial da Loja Virtual, exibindo os produtos, e também, detalhando-os
- Painel: O Painel corresponde as interações internas e recursos específicos da Loja Virtual, dentre elas: a possibilidade de efetuar compras de produtos pelos Usuários Clientes; e cadastrar produtos pelo Usuário Administrador.
- OBS.: Para acessar o Painel, os Usuários Clientes, precisam inicialmente, realizar o seu cadastro na tela de Cadastro, e efetuar o seu login na tela de Login. O Usuário Administrador, quando acessar pela primeira vez a tela de Login, será exibido um modal contendo o formulário de cadastro, e, posteriormente, realizando o seu login na tela de Login.

- A Loja Virtual aceita 2 tipos de usuários: Administrador e Cliente. Após você ter criado o banco de dados juntamente com as tabelas, você inicializará a aplicação da Loja Virtual no seu navegador. Ao acessar a tela de Login pela 1º vez, será exibido um formulário de cadastro do Administrador. Esse 1º cadastro está reservado para o Usuário Administrador. Porque o Sistema está configurado em que o Administrador deve ser o <b>1º registro</b> a ser gravado na tabela cliente do banco de dados. À partir desse 1º registro do Administrador, todos os próximos cadastros a serem efetuados, serão do tipo Usuário Cliente.
- Inicialmente, a Loja Virtual não exibirá nenhum produto na página home. Para o sistema buscar algum produto, o Administrador precisa se cadastrar, e após se logar na Loja Virtual, no painel do Administrador, precisará cadastrar categorias juntamente com suas subcategorias, e cadastrar produtos a essas subcategorias (já) cadastradas.

## Diagramas
Os Diagramas abaixo descrevem as tabelas do banco de dados, mostrando os relacionamentos entre elas, e as chaves primárias (PKs) com as chaves estrangeiras (FKs).<br><br>
<img src="./docs/Banco-de-Dados/loja - Modelo Conceitual.jpg" alt="DER - Modelo Conceitual">
<hr>
<img src="./docs/Banco-de-Dados/loja - Modelo Lógico.jpg" alt="DER - Modelo Lógico">

## Contato
Caso necessite de mais esclarecimentos sobre o Projeto Loja Virtual, por favor, mande-me um e-mail: medrina@gmail.com<br>
att: Rafael Martins
