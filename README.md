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
## 1) Criar o Banco de Dados e as Tabelas que compõem o funcionamento do Sistema:
- O Projeto da Loja Virtual armazena os dados em geral através de um sistema de banco de dados MySQL relacional
- Você deve criar o banco de dados, as tabelas inserindo alguns dados iniciais
- No Projeto loja-virtual, abra o arquivo <b>\loja-virtual\docs\Banco de Dados\loja - Tabelas.txt</b>
- Dentro do arquivo <b>loja - Tabelas.txt</b> , execute as instruções de comandos para criar o banco de dados, todas as tabelas, pré-inserções de dados em algumas tabelas e referências das chaves primárias com as chaves estrangeiras.
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
------------------------------------------------------------------------------------
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
