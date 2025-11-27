<?php
    class ClienteService extends Cliente {
        private $conexao;
        private Cliente $cliente;

        public function __construct(Connection $conexao, Cliente $cliente) {
            $this->conexao = $conexao->conectar();
            $this->cliente = $cliente;
        }

        public function __get($name) {
            return $this->$name;
        }

        // recupera cliente pelo seu id
        public function getClienteByID($id): array {
            try {
                $query = 'select id, nome, email from cliente where id = :id';
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':id', $id);
                $stmt->execute();
                $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->cliente->setId($consulta['id']);
                $this->cliente->setNome($consulta['nome']);
                $this->cliente->setEmail($consulta['email']);
                if($consulta) return $consulta;
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }
        }

        // método que faz serviço de login, validando ou não o acesso do Usuário Cliente. Criação de sessão do Usuário Cliente
        public function getCliente() {

            // compara se o email (login) que o Usuário Cliente informou, existe na tabela cliente
            try {
                $query = 'select id, nome, email, senha from cliente where email = :email';
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':email', $this->cliente->getEmail());
                $stmt->execute();
                $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }

            // testa se a consulta retornou algum cliente compaado com o login(email) informado 
            if($consulta) {

                // compara se a senha informada pelo Usuário Cliente, é igual a senha armazenada no banco
                require_once './../helper/funcoes_adicionais.php';
                $senhaVerificada = desencriptarSenha($this->cliente->getSenha(), $consulta['senha']);

                // testa se a senha do Usuário Cliente é verdadeira!
                if($senhaVerificada) {

                    // inciação da variável de sessão do Usuário Cliente
                    session_start();
                    $nome = explode(' ', $consulta['nome']);
                    $_SESSION['nome'] =  $nome[0];
                    $_SESSION['id'] = $consulta['id'];
                    $_SESSION['email'] = $consulta['email'];
                    session_write_close();
                    $homeController = new HomeController();
                    $resultado = $homeController->painel();
                    return true;
                }
            }
        }

        // cadastra um novo Usuário Cliente
        public function salvarCliente(): bool {
            try {
                $query = "SELECT count(*) AS 'quant' FROM cliente WHERE email = :email";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(":email", $this->cliente->getEmail());
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                // verifica se já existe algum registro contendo o email informado pelo Usuário Cliente 
                if($resultado['quant'] > 0) return false;
            }
            catch(PDOException $e) {
                echo 'Erro: '. $e->getMessage();
            }

            // continua o fluxo para cadastrar novo Usuário Cliente
            try {
                $insert = "INSERT INTO cliente(nome, email, senha) VALUES(:nome, :email, :senha);";
                $stmt = $this->conexao->prepare($insert);
                $stmt->bindValue(':nome', $this->cliente->getNome());
                $stmt->bindValue(':email', $this->cliente->getEmail());
                $stmt->bindValue(':senha', $this->cliente->getSenha());
                if($stmt->execute()) return true;
            }
            catch(PDOException $e) {
                return false;
            }
        }

        // atualizar dados do Cliente
        public function atualizarDados(): bool {
            $query = '';
            $resultado = 0;
            $this->cliente->setEmail($_SESSION['email']);

            // se o Usuário Cliente quiser alterar os dados + a sua senha pessoal, então ele executa o if abaixo
            if(isset($_POST['senha'])) {

                // encriptar nova senha digitada pelo cliente
                require_once '../helper/funcoes_adicionais.php';
                $novaSenha = encriptarSenha($_POST['senha']);

                $this->cliente->setSenha($novaSenha);
                
                // atualização dos dados + senha do Usuário Cliente
                try {
                    $query = "UPDATE cliente SET nome = :nome, email = :email, senha = :senha WHERE id = :id_cliente;";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue(':nome', $this->cliente->getNome());
                    $stmt->bindValue(':email', $this->cliente->getEmail());
                    $stmt->bindValue(':senha', $this->cliente->getSenha());
                    $stmt->bindValue(':id_cliente', $_SESSION['id']);
                    $resultado = $stmt->execute();
                    $nome = explode(' ', $this->cliente->getNome());
                    $_SESSION['nome'] = $nome[0];
                    return $resultado;
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }
            }

            // se o Usuário Cliente quiser alterar apenas os dados, então ele executa o else abaixo
            else {

                // atualização dos dados do Usuário Cliente
                try {
                    $query = "UPDATE cliente SET nome = :nome, email = :email WHERE id = :id_cliente;";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue(':nome', $this->cliente->getNome());
                    $stmt->bindValue(':email', $this->cliente->getEmail());
                    $stmt->bindValue(':id_cliente', $_SESSION['id']);
                    $resultado = $stmt->execute();
                    $nome = explode(' ', $this->cliente->getNome());
                    $_SESSION['nome'] = $nome[0];
                    return $resultado;
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }
            }
        }

        // método responsável por mostrar os produtos do carrinho de compras do Usuário Cliente
        public function getClienteModal(): void {
            $carrinho = new Carrinho($this->cliente);
            $conexao = new Connection();
            $resultado = new CarrinhoService($conexao, $carrinho);
            $resultado->carrinho();
        }

        // recupera os dados do Usuário Administrador para ser exibido na seção "Editar Dados"
        public function getUserAdmin(): array {
            try {
                $query = "SELECT nome, email FROM cliente WHERE id = 1;";
                $stmt = $this->conexao->query($query);
                if($stmt->execute()) {
                    $lista = $stmt->fetch(PDO::FETCH_ASSOC);
                    return $lista;
                }
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }
        }

        // 
        public function atualizarDadosAdmin(): bool {
            session_start();
            $query = '';
            $resultado = 0;

            // se o Usuário Administrador quiser alterar os dados + a sua senha pessoal, então ele executa o if abaixo
            if(isset($_POST['senha-confirmar-admin'])) {

                // encriptar nova senha digitada pelo cliente
                require_once '../helper/funcoes_adicionais.php';
                $novaSenha = encriptarSenha($_POST['senha-confirmar-admin']);

                // atualização dos dados + senha do Usuário Administrador
                try {
                    $query = "UPDATE cliente SET nome = :nome, senha = :senha WHERE id = 1";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue(':nome', $this->cliente->getNome());
                    $stmt->bindValue(':senha', $novaSenha);
                    $resultado = $stmt->execute();
                    $_SESSION['nome'] = $this->cliente->getNome();
                    return $resultado;
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }
            }

            // se o Usuário Administrador quiser alterar apenas os dados, então ele executa o else abaixo
            else {

                // atualização dos dados do Usuário Administrador
                try {
                    $query = "UPDATE cliente SET nome = :nome WHERE id = 1";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue(':nome', $this->cliente->getNome());
                    $_SESSION['nome'] = $this->cliente->getNome();
                    $resultado = $stmt->execute();
                    return $resultado;
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }
            }
        }

        // recupera o total de registros da tabela cliente
        public function verificarRegistrosClientes(): bool {
            try {
                $query = "SELECT count(*) as 'quant' FROM cliente;";
                $stmt = $this->conexao->query($query);
                if($stmt->execute()) { 
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($resultado['quant'] == 0) return false;
                    else return true;
                }                
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }
        }

        // cadastrar o Administrador como 1º registro na tabela cliente
        public function cadastrarAdministrador(): bool {
            if(isset($_POST['senha'])) {
                require_once '../helper/funcoes_adicionais.php';
                $novaSenha = encriptarSenha($_POST['senha']);
                $this->cliente->setSenha($novaSenha);
                try {
                    $insert = "INSERT INTO cliente(nome, email, senha) VALUES(:nome, :email, :senha);";
                    $stmt = $this->conexao->prepare($insert);
                    $stmt->bindValue(':nome', $this->cliente->getNome());
                    $stmt->bindValue(':email', $this->cliente->getEmail());
                    $stmt->bindValue(':senha', $this->cliente->getSenha());
                    if($stmt->execute()) return true;
                    else return false;
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }
            }
        }

    }