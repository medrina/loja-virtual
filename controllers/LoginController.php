<?php
    class LoginController {

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // exibe a tela de login
        public static function exibirTelalogin() {
            $conexao = new Connection();
            $clienteService = new ClienteService($conexao, new Cliente());

            // recupera quantos registros existem na tabela cliente
            $consulta = $clienteService->verificarRegistrosClientes();

            session_start();
            if(isset($_SESSION['id'])) header('Location: /cliente/painel');
            else include __DIR__ .'/../views/layouts/login.phtml';
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método de validação de login, e autenticação de usuário
        public static function validarLogin() {
            session_start();
            if(!empty($_SESSION) && isset($_SESSION)) header('Location: /cliente/painel');
            else if(empty($_POST)) header('Location: /login?erro=3');
            else {
                session_write_close();
                $cliente = new Cliente();
                $cliente->setEmail($_POST['email']);
                $cliente->setSenha($_POST['senha']);
                $conexao = new Connection();
                $verificacaoLogin = new ClienteService($conexao, $cliente);
                $verificacaoLogin = $verificacaoLogin->getCliente();
                if(!$verificacaoLogin) {
                    session_start();
                    session_destroy();
                    header('Location: /login?erro=2');
                }
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método de validação do modal login, e autenticação de usuário
        public static function validarLoginModal() {
            $cliente = new Cliente();
            $cliente->setEmail($_POST['email']);
            $cliente->setSenha($_POST['senha']);
            $conexao = new Connection();
            $verificacaoLogin = new ClienteService($conexao, $cliente);
            $verificacaoLogin = $verificacaoLogin->getCliente();
            if(!$verificacaoLogin) {
                session_start();
                session_destroy();
                echo 'erro';
            }
            else  echo 'OK';
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que vai criar um cadastro de usuário cliente
        public static function criarCadastro() {
            session_start();
            require_once './../helper/funcoes_adicionais.php';
            if(testarLogin2()) header('Location: /cliente/painel');
            else {
                if(empty($_POST['nome']) || 
                    empty($_POST['email']) || 
                    empty($_POST['senha'])) {
                        header('Location: /login/criar-login?erro=1');
                    }
                else {
                    require_once './../helper/funcoes_adicionais.php';
                    $senhaEncriptada = encriptarSenha($_POST['senha']);
                    $cliente = new Cliente();
                    $cliente->setNome($_POST['nome']);
                    $cliente->setEmail($_POST['email']);
                    $cliente->setSenha($senhaEncriptada);
                    $conexao = new Connection();
                    $clienteService = new ClienteService($conexao, $cliente);
                    $resultado = $clienteService->salvarCliente();
                    if($resultado) header('Location: /login?msg=1');
                    else header('Location: /login/criar-login?err=2');
                }
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que encerra a autenticação de usuário, redirecionando para a tela inicial (home)
        public static function logoff() {
            session_start();
            if(!isset($_SESSION['id']) || empty($_SESSION['id'])) header('Location: /');
            else {
                session_destroy();
                header('Location: /');
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que chama a tela de criar cadastro de usuário
        public static function exibirTelaCadastro() {
            session_start();
            require_once './../helper/funcoes_adicionais.php';
            if(testarLogin2()) header('Location: /cliente/painel');
            else include __DIR__ .'/../views/layouts/cadastro.phtml';
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que testa se há algum Usuário logado no sistema (modal )
        public static function loginStatus() {
            session_start();
            if(!empty($_SESSION)) echo '1';
            else echo '0';
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que testa se o Usuário Administrador está logado no sistema
        public static function loginStatusAdmin() {
            session_start();
            if(isset($_SESSION) && !empty($_SESSION)) {
                if($_SESSION['id'] == 1) echo '1';
                else echo '0';
            }
            else echo '0';
        }
    }