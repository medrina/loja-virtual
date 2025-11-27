<?php
    class ClienteController {
        private ClienteService $clienteService;

        public function __construct(ClienteService $clienteService) {
            $this->clienteService = $clienteService;
        }

        // esse método irá retornar os dados pessoais do cliente, para ser mostrado dentro do Resumo do Pedido (tela de Checkout)
        public function dadosPessoaisCheckout() {

            require_once './../helper/funcoes_adicionais.php';
            session_start();
            
            // verifica se há algum usuário logado
            if(empty($_SESSION)) {
                echo 1;
            }
            else { 
                $array = [];
                if(isset($_POST)) {
                    $cliente = $this->clienteService->getClienteByID($_SESSION['id']);
                    $conexao = new Connection();
                    $clienteAsaasService = new ClienteAsaasService($conexao, new ClienteAsaas());
                    $clienteAsaas = $clienteAsaasService->getClienteAsaas();
                    $array = [

                        // dados principais do cliente
                        'cliente' => $cliente, 

                        // dados secundários do cliente
                        'cliente_asaas' => $clienteAsaas
                    ];

                    // converte o array associativo em formato JSON para ser retornado via requisição Ajax
                    $array = json_encode($array);
                    echo $array;
                }
                else echo 2;
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que salva os dados pessoais na conta do usuário cliente
        public static function salvarDadosPessoaisCliente() {
            
            // verifica se a requisição recebida é do tipo POST
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                session_start();

                // compara o token csrf do formulário de Editar Dados Pessoais do Usuário Cliente com o token csrf gerado e armazenado na sessão de usuário
                // resultado das comparações dos tokens com falha!
                if(!hash_equals($_SESSION['csrf_dados_pessoais'], $_POST['csrf_dados_pessoais'])) {
                    session_destroy();
                    header('Location: /?erro=3');
                }

                // resultado das comparações dos tokens csrf com sucesso!
                else {

                    $flag = true;
                    
                    // verifica se usuário escolheu a opção de alteração de senha
                    if(isset($_POST['senha']) && isset($_POST['senha-confirmar'])) {

                        // testa se as duas senhas informadas são diferentes
                        if($_POST['senha'] != $_POST['senha-confirmar']) {
                            $flag = false;
                            echo '0';
                        }

                        // resultado se as duas senhas estiverem iguais
                        else $flag = true;
                    }

                    // atualizar os dados de cadastro do Usuário Cliente
                    if($flag) {
                        $cliente = new Cliente();
                        $cliente->setNome($_POST['nome']);
                        $clienteAsaas = new ClienteAsaas();
                        $clienteAsaas->__set('telefone', $_POST['fone']);
                        $clienteAsaas->__set('cpf', $_POST['cpf']);
                        $clienteAsaas->__set('cliente', $cliente);
                        $conexao = new Connection();
                        $clienteAsaasService = new ClienteAsaasService($conexao, $clienteAsaas);
                        $resultadoClienteAsaas = $clienteAsaasService->atualizarDados();
                        if($resultadoClienteAsaas) {
                            $clienteService = new ClienteService($conexao, $cliente);
                            $resultadoCliente = $clienteService->atualizarDados();
                            echo $resultadoCliente;
                        }
                    }
                }
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que recupera os dados dos usuários cliente e administrador, chamando a tela de Dados Pessoais
        public static function getDadosPessoais() {
            session_start();

            // verifica se há algum Usuário Cliente/Administrador logado
            if(empty($_SESSION)) {
                header('Location: /?erro=3');
            }
            else {
                $cliente = new Cliente();
                $cliente->setId($_SESSION['id']);
                $conexao = new Connection();
                $clienteService = new ClienteService($conexao, $cliente);
                $dados = $clienteService->getClienteByID($_SESSION['id']);
                if($dados) {
                    $clienteService = new ClienteAsaasService($conexao, new ClienteAsaas());
                    $dadosClienteAsaas = $clienteService->getClienteAsaas();
                }
                $dadosSerializados = json_encode($dadosClienteAsaas);
                $_SESSION['dados_pessoais'] = $dadosSerializados;

                // exibe a tela de dados pessoais dentro do painel interno de Usuário Cliente
                include __DIR__ .'/../views/painel/dados_pessoais.phtml';
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que recupera os dados pessoais do usuário cliente, e exibe a tela de editar/atualizar os dados pessoais do usuario cliente
        public static function editarDadosPessoaisCliente() {
            session_start();

            // verifica se há algum Usuário Cliente/Administrador logado
            if(!isset($_SESSION) || empty($_SESSION)) {
                header('Location: /?erro=3');
            }
            else {
                $cliente = new Cliente();
                $cliente->setId($_SESSION['id']);
                $conexao = new Connection();
                $clienteService = new ClienteService($conexao, $cliente);
                $dados = $clienteService->getClienteByID($_SESSION['id']);
                if($dados) {
                    $clienteService = new ClienteAsaasService($conexao, new ClienteAsaas());
                    $dadosClienteAsaas = $clienteService->getClienteAsaas();
                }

                // gerando o token csrf, e atribuindo nas variáveis: sessão e $csrf
                $csrf = $_SESSION['csrf_dados_pessoais'] = bin2hex(random_bytes(32));
                
                // exibe a tela de editar dados pessoais dentro do painel interno de Usuário Cliente
                include __DIR__ .'/../views/painel/editar_dados_pessoais.phtml';
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que salva os dados do usuário Administrador
        public static function salvarDadosPessoaisAdministrador() {
            $flag = true;
            if(isset($_POST)) {
                $cliente = new Cliente();

                // verifica se Usuário Adminitrador escolheu a opção de alteração de senha
                if(isset($_POST['senha-alterar-admin']) && isset($_POST['senha-confirmar-admin'])) {

                    // testa se as duas senhas informadas são diferentes
                    if($_POST['senha-alterar-admin'] != $_POST['senha-confirmar-admin']) {
                        $flag = false;
                        echo '0';
                    }
                    else $flag = true;
                }
                if($flag) {
                    $cliente->setNome($_POST['nome']);
                    $conexao = new Connection();
                    $clienteService = new ClienteService($conexao, $cliente);
                    $resultadoCliente = $clienteService->atualizarDadosAdmin();
                    echo $resultadoCliente;
                }
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que recupera os dados pessoais do usuário Administrador, e exibe a tela de editar/atualizar os dados pessoais do usuario Administrador
        public static function editarDadosPessoaisAdministrador() {
            require_once './../helper/funcoes_adicionais.php';
            session_start();
            if(!testarLoginAdmin()) header('Location: /?erro=4');
            else {
                $conexao = new Connection();
                $clienteService = new ClienteService($conexao, new Cliente());
                $dados = $clienteService->getUserAdmin();
                include __DIR__ .'/../views/painel/editar_dados_pessoais_admin.phtml';
            }
        }

        // método que realiza o cadastro do Usuário Administrador
        // OBS.: A loja virtual está configurada para que o Usuário Administrador seja o 1º cadastro a ser efetuado
        public function cadastrarAdministrador() {
            if(isset($_POST)) {
                $conexao = new Connection();
                $cliente = new Cliente();
                $cliente->setNome($_POST['nome']);
                $cliente->setEmail($_POST['email']);
                $clienteService = new ClienteService($conexao, $cliente);
                $resultado = $clienteService->cadastrarAdministrador();
                if($resultado) echo true;
                else echo false;
            }
        }

    }