# Projeto Loja Virtual
O Projeto Loja Virtual foi desenvolvido com objetivos de aplicar os conhecimentos adquiridos por mim, durante o período da graduação do curso Superior de Tecnologia em Análise e Desenvolvimento de Sistemas, do Centro Universitário Senac, iniciado no mês de Julho de 2022, e concluído no mês de Dezembro de 2024.

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
   - PHP 8.2.12
   - Banco de Dados MySQL Server 8.0
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
-------------------------------------------------------------------------------
## Informações Técnicas:
A Loja Virtual consiste em 2 partes: Home e o Painel.
- Home: O Home consiste na página inicial da Loja Virtual, exibindo os produtos, e também, detalhando-os
- Painel: O Painel corresponde as interações internas e recursos específicos da Loja Virtual, dentre elas: a possibilidade de efetuar compras de produtos pelos Usuários Clientes; e cadastrar produtos pelo Usuário Administrador.
- OBS.: Para acessar o Painel, os Usuários Clientes, precisam inicialmente, realizar o seu cadastro na tela de Cadastro, e efetuar o seu login na tela de Login. O Usuário Administrador, quando acessar pela primeira vez a tela de Login, será exibido um modal contendo o formulário de cadastro, e, posteriormente, realizando o seu login na tela de Login.
--------------------------------------------------------------------------------
## Instruções de Uso
## Windows:
## 1) Criar o Banco de Dados e as Tabelas que compõem o funcionamento do Sistema:
- O Projeto da Loja Virtual armazena os dados em geral através de um sistema de Banco de Dados MySQL Relacional
- Você deve criar o banco de dados com as tabelas através do software de SGBD de Banco de Dados Relacional MySQL no seu computador
- No Projeto loja-virtual, abra o arquivo <b>loja - Tabelas.txt</b> que está dentro da pasta <b>docs</b>
- Dentro do arquivo <b>loja - Tabelas.txt</b> , execute as instruções de comandos para criar o banco de dados, todas as tabelas, e referências das chaves primárias com as chaves estrangeiras.
-----------------------------------------------------------------------------------
## 2) Baixar o Projeto Loja Virtual do Git Hub
- Baixar e extraír a Loja Virtual no seu computador
-----------------------------------------------------------------------------------
## 3) Configurar informações do Banco de Dados MySQL no arquivo de configuração do Projeto Loja Virtual
- Acessar o SGBD de Banco de Dados <b>instalado</b> no seu computador, anotar o nome de <b>usuário</b> e <b>senha</b>.
- No Projeto loja-virtual, abra o arquivo "\loja-virtual\config\Connection.php"
- Dentro do arquivo "\loja-virtual\config\Connection.php" , digite definindo o nome de usuário e senha (que você anotou do SGBD do seu Banco de Dados) nos atributos <b>$usuario</b> e <b>$senha</b> da Classe Connection
- o nome de usuário e a senha, deverão ser informados no formato string

Exemplos de como deve ficar a configuração:<br><br>
Ex1:<br>
No SGBD:<br>
nome de usuário = admin<br>senha do usuário = admin<br><br>
No arquivo Connection.php<br>
private $dsn = 'mysql:host=localhost;dbname=loja';<br>
private $usuario = 'admin';<br>
private $senha = 'admin';<hr>
Ex2:<br>
No SGBD:<br>
nome de usuário = root<br>senha do usuário = 12345<br><br>
No arquivo Connection.php<br>
private $dsn = 'mysql:host=localhost;dbname=loja';<br>
private $usuario = 'root';<br>
private  $senha = '12345';<br>

- após a digitação nos atributos $usuario e $senha, salve o arquivo Connection.php e feche-o
------------------------------------------------------------------------------------
## 5) Iniciar o Servidor PHP
- abrir o prompt de comando no Windows
- navegar até a pasta onde você extraiu e colocou os arquivos e pastas da Loja Virtual utilizando o comando "cd"
- acessar e entrar dentro da pasta "public" utilizando o comando cd
- estando dentro da pasta public da Loja Virtual, digitar: <b>php -S localhost:8000</b>

### Exemplo no Windows:
- assumindo que o Projeto Loja Virtual foi baixado, extraído e colocado dentro do diretório raiz do Windows (C:\loja-virtual)
- abra o prompt de comando do Windows
- digitar o comando seguido do caminho absoluto para selecionar a pasta pública pelo prompt:  <b>cd c:\loja-virtual\public</b>  sendo mostrado no próprio prompt a esquerda:  <b>C:\loja-virtual\public</b>
- estando dentro da pasta public da Loja Virtual, digitar: <b>php -S localhost:8000</b>

<b>NOTA:</b> certifique-se que a porta 8000 não esteja em uso por outro programa no momento. Se porventura, a porta 8000 estiver em uso, você necessitará usar outra porta
- No Windows: para ver a lista de portas em que o sist. operacional Windows não esteja utilizando, digitar dentro do prompt de comando: <b>netstat -ano</b>
No resultado do comando mencionado, será exibido uma lista de portas em que o sist. operacional Windows está usando. As portas em que estiverem sendo usadas, a 
coluna Estado estará com o valor: <b>LISTENING</b>. As portas que não estiverem sendo utilizadas pelo sist. operacional Windows, não estarão listadas no resultado. Portanto, você poderá 
utilizar a porta que não esteja aparecendo no resultado do comando netstat -ano na execução da Loja Virtual.
-----------------------------------------------------------------------------------
## 6) Iniciar a Loja Virtual
- após iniciar o servidor PHP pelo prompt, abra o navegador de sua preferência, e digite na url:   http://localhost:8000  e aperte a tecla enter
- será apresentada a página home da Loja Virtual
- para encerrar a conexão do servidor PHP no prompt de comando do Projeto Loja Virtual, você deverá pressionar uma combinação de teclas no prompt: <b>Ctrl + C</b>  , e em seguida, o servidor PHP é 
encerrado e o prompt de comando é liberado.
-----------------------------------------------------------------------------------
## Linux Debian/Ubuntu
## 1) Atualizar o sist. operac.
- abrir o terminal, e atualizar o sistema operacional digitando:<br><b>sudo apt update && apt upgrade -y</b>
## 2) clonar o Projeto da Loja Virtual
- precisa instalar o comando git para poder clonar o Projeto da Loja Virtual, digitando:<br>
<b>sudo apt install git -y</b>
- acessar o diretório do seu usuário digitando:<br><b>cd /home/NOME_SEU_USUÁRIO</b>
- clonar o Projeto da Loja Virtual digitando:<br><b>sudo git clone https://github.com/medrina/loja-virtual.git</b>
- será gerado o diretório: loja-virtual
## 2) Instalação do PHP e extensões
- esse projeto da Loja Virtual é compatível com a versão 8.4 do PHP.
- ===============
-  instalar dependências necessárias digitando:<br>
<b>sudo apt install -y lsb-release ca-certificates apt-transport-https software-properties-common gnupg2</b>
- baixar a chave de segurança do repositório, digitando:<br>
<b>sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://sury.org</b>
- adicionar esse repositório na lista de fontes digitando:<br>
<b>echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.y/php.list</b>
- atualizar o sistema digitando:<br>
<b>sudo apt update -y</b>
- ================
- instalar a versão 8.4 do PHP e suas extensões compatíveis, digite no terminal:<br><b>sudo apt install php8.4 php8.4-cli php8.4-common php8.4-mysql php8.4-xml php8.4-curl php8.4-mbstring php8.4-zip -y</b>
- verificar se o PHP foi instalado com êxito, digitando:<br>
<b>sudo php --version</b>
- a saída do comando acima, exibirá a versão do PHP que foi instalado com sucesso
## 3) Instalação do banco de dados
- optei por utilizar o banco de dados MariaDB por ser leve e padrão que vem nas versões do programa XAMPP
- no terminal, para baixar e instalar o banco de dados MariaDB, digitar:<br><b>sudo apt install mariadb-server -y</b>
- acessar o console do banco de dados MariaDB, digitando:<br><b>sudo mariadb</b>
- criar usuário root do MariaDB junto com a senha de root gerada por você digitando:<br><b>ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD('NOVA_SENHA');</b>
<br><b>- NOTA: você deve digitar a sua NOVA_SENHA dentro dos parênteses com aspas simples, igual ao modelo do comando acima</b><br><hr>
Exemplo 1: se a senha definida for root, então a sintaxe do comando fica assim:<br><b>ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD('root');</b><br><br>Exemplo 2: se a senha definida for admin, então a sintaxe do comando fica assim:<br><b>ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD('admin');</b><hr>
- aplicar a criação da <b>NOVA_SENHA</b> do root do banco de dados MariaDB digitando:<br><b>FLUSH PRIVILEGES;</b>
- sair do console do MariaDB digitando:<br><b>exit</b>
## 4) Criação das tabelas do banco de dados
- acessar o console do banco de dados MariaDB com a sua NOVA_SENHA que você digitou e cadastrou no root, digitando:<br><b>sudo mariadb -u root -p</b><br>em seguida, o MariaDB irá pedir a sua NOVA_SENHA que você cadastrou no root
- ocorrendo sucesso, o MariaDB habilitará o console para poder digitar os comandos MySQL
- criar o database da loja virtual digitando:<br><b>create database loja;</b>
- <b>NOTA: o nome desse database será <i>loja</i>, mas se você quiser alterar para outro nome, você pode fazer, desde que o nome do database seja informado dentro do arquivo de conexão da Loja Virtual (veremos essa configuração mais adiante). Mas por padrão, o nome do database dentro do arquivo de conexão está definido como loja</b>
- em seguida, após a criação do database, digitar:<br><b>use loja;</b><br> para selecionar o database loja.
- criar as tabelas desse database da loja virtual. Há um arquivo em txt que possui os comandos de criação das tabelas com as suas colunas. Esse arquivo está no path: <b>loja-virtual\docs\Banco de Dados\loja - Tabelas</b>. Precisa criar as 23 tabelas descritas dentro desse arquivo.
-----------------------------------------------------------------------------------
## 5) Aplicar permissões ao Projeto da Loja Virtual
- conceder permissão do usuário local para todo diretório do projeto digitando:<br><b>sudo chown -R NOME_USUÁRIO:NOME_USUÁRIO loja-virtual/</b>
- <b>NOTA: você deve informar o nome do seu usuário que você utiliza para se logar no sist. operac. (não é o usuário root, mas o nome do seu usuário que você se loga para inicializar o sistema)</b>
- dar permissão de escrita no diretório de upload das imagens dos produtos digitando:<br><b>sudo chmod 755 /home/NOME_SEU_USUÁRIO/loja-virtual/public/assets/img</b>
## 6) Inicializando a Loja Virtual
- configurar o nome da base de dados, usuário e senha no arquivo de conexão ao MariaDB. Editar o arquivo "\loja-virtual\config\Connection.php" com o seu editor: vim, nano, VS Code,...
- Dentro do arquivo "\loja-virtual\config\Connection.php" , digite definindo o nome de usuário e senha (que você anotou do SGBD do seu Banco de Dados) nos atributos <b>$usuario</b> e <b>$senha</b> da Classe Connection
- o nome de usuário e a senha, deverão ser informados no formato string

Exemplos de como deve ficar a configuração:<br><br>
Ex1:<br>
No SGBD:<br>
nome de usuário = admin<br>senha do usuário = admin<br><br>
No arquivo Connection.php<br>
private $dsn = 'mysql:host=localhost;dbname=loja';<br>
private $usuario = 'admin';<br>
private $senha = 'admin';<hr>
Ex2:<br>
No SGBD:<br>
nome de usuário = root<br>senha do usuário = 12345<br><br>
No arquivo Connection.php<br>
private $dsn = 'mysql:host=localhost;dbname=loja';<br>
private $usuario = 'root';<br>
private  $senha = '12345';<br>

- após a digitação nos atributos $usuario e $senha, salve o arquivo Connection.php e feche-o

- acessar a pasta public da loja-virtual digitando:<br><b>cd loja-virtual/public</b>
- inicializar o servidor PHP embutido digitando:<br><b>sudo php -S localhost:8000</b>
- acessar no navegador digitando na URL:<br><b>localhost:8000</b>
- deverá mostrar a página home apenas com o botão de Login
-----------------------------------------------------------------------------------
## Informações Complementares:
<b>NOTA 1:</b> A Loja Virtual aceita 2 tipos de usuários: Administrador e Cliente. Após você ter criado o banco de dados juntamente com as tabelas, você inicializará a aplicação da Loja Virtual no seu navegador. Ao acessar a tela de Login pela 1º vez, será exibido um formulário de cadastro do Administrador. Esse 1º cadastro está reservado para o Usuário Administrador. Porque o Sistema está configurado em que o Administrador deve ser o <b>1º registro</b> a ser gravado na tabela cliente do banco de dados. À partir desse 1º registro do Administrador, todos os próximos cadastros a serem efetuados, serão do tipo Usuário Cliente.

<b>NOTA 2:</b> Inicialmente, a Loja Virtual não exibirá nenhum produto na página home. Para o sistema buscar algum produto, o Administrador precisa se cadastrar, e após se logar na Loja Virtual, no painel do Administrador, precisará cadastrar categorias juntamente com suas subcategorias, e cadastrar produtos a essas subcategorias (já) cadastradas.

<b>NOTA 3</b> Dentro do Projeto da Loja Virtual, há uma pasta chamada <b>docs</b>. Dentro dessa pasta docs, há 2 pastas: Banco de Dados e Diagramas<br><br>
Na pasta Banco de Dados, há 3 anexos:
- Diagrama Entidade Relacionamento - Modelo Lógico: loja - Modelo Lógico.jpg
- Diagrama Entidade Relacionamento - Modelo Conceitual: loja - Modelo Conceitual.jpg<br>
Esses Diagramas descrevem as tabelas do banco de dados, mostrando os relacionamentos entre elas, e as chaves primárias (PKs) com as chaves estrangeiras (FKs)<br><br>
Na pasta Diagramas, há 2 anexos
- Diagrama de Casos de Uso: Casos de Uso.pdf
- Diagrama de Classes: Diagrama de Classes.jpg<br>

O anexo do Casos de Uso, descreve as funcionalidades da Loja Virtual, juntamente com a Análise de Requisitos de cada Caso de Uso, em que os Usuários Administrador e Cliente poderão executar.<br>
O anexo do Diagrama de Classes, descrevem o Projeto da Loja Virtual, em um sistema estruturado orientado a objetos, mostrando as classes, atributos, e os relacionamentos entre as classes.
<hr>
Caso necessite de mais esclarecimentos sobre o Projeto Loja Virtual, por favor, mande-me um e-mail: medrina@gmail.com<br>
att: Rafael Martins
