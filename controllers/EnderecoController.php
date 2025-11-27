<?php
    class EnderecoController {
        private EnderecoService $enderecoService;

        public function __construct(EnderecoService $enderecoService) {
            $this->enderecoService = $enderecoService;
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que recupera e lista todos os endereços cadastrados/registrados na conta do Usuário Cliente
        public static function getEnderecos() {
            require_once './../helper/funcoes_adicionais.php';

            // verifica se há algum Usuário logado
            if(testarSessao()) {
                $conexao = new Connection();
                $cliente = new Cliente();
                $cliente->setId($_SESSION['id']);
                $endereco = new Endereco();
                $endereco->__set('cliente', $cliente);
                $enderecoService = new EnderecoService($conexao, $endereco);
                $listaEnderecos = $enderecoService->getListaEnderecos();
                if(!$listaEnderecos) $listaEnderecos = 0;
                include __DIR__ .'/../views/painel/lista_enderecos.phtml';
            }
            else header('Location: /?erro=3');
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que exibe a tela de Adicionar Endereço
        public static function exibirTelaAdicionarEndereco() {
            require_once './../helper/funcoes_adicionais.php';

            // verifica se há algum Usuário logado
            if(testarSessao()) {
                
                // gerando o token csrf, e atribuindo nas variáveis: sessão e $csrf
                $csrf = $_SESSION['csrf_adicionar_endereco'] = bin2hex(random_bytes(32));

                include __DIR__ .'/../views/painel/adicionar_endereco.phtml';
            }
            else header('Location: /?erro=3');
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que recebe os dados da requisição POST, preenche no objeto Endereco, e cadastra na conta do usuário (salva os dados no banco)
        public static function criarEnderecoCliente() {
            
            // verifica se a requisição recebida é do tipo POST
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                // inicia sessão de usuário
                session_start();

                // compara o token csrf do formulário de Adicionar Endereço do Usuário Cliente com o token csrf gerado e armazenado na sessão de usuário
                // resultado das comparações dos tokens com falha!
                if(!hash_equals($_SESSION['csrf_adicionar_endereco'], $_POST['csrf_adicionar_endereco'])) {
                    session_destroy();
                    header('Location: /?erro=3');
                }
                
                // resultado das comparações dos tokens csrf com sucesso!
                else {
            
                    require_once './../helper/funcoes_adicionais.php';

                    // verifica se há algum Usuário logado
                    if(!testarLogin2()) header('Location: /?erro=3');

                    // usuário está logado
                    else {

                        // verifica se há algum dado vazio na requisição POST
                        if(empty($_POST['numero']) ||
                            empty($_POST['cep']) ||
                            empty($_POST['logradouro']) ||
                            empty($_POST['bairro']) ||
                            empty($_POST['cidade']) ||
                            empty($_POST['uf'])) {
                                header(('Location: /cliente/painel/add-endereco?erro=1'));
                        }
                        else {
                            $cliente = new Cliente();
                            $cliente->setId($_SESSION['id']);
                            $cidade = new Cidade($_POST['cidade'], $_POST['uf']);
                            $endereco = new Endereco();
                            $endereco->__set('logradouro', $_POST['logradouro']);
                            $endereco->__set('numero', $_POST['numero']);
                            $endereco->__set('complemento', $_POST['complemento']);
                            $endereco->__set('bairro', $_POST['bairro']);
                            $endereco->__set('cep', $_POST['cep']);
                            $endereco->__set('cliente', $cliente);
                            $endereco->__set('cidade', $cidade);
                            $conexao = new Connection();
                            $enderecoService = new EnderecoService($conexao, $endereco);
                            $resultado = $enderecoService->criarEndereco();
                            if($resultado) header('Location: /cliente/painel/add-endereco?msg=1');
                            else header('Location: /cliente/painel/add-endereco?msg=0');
                        }
                    }
                }
            }
        }
        
        // método que exibe tela de formulário para atualizar o endereço, recebendo por parâmetro na requisição GET, o id do endereço selecionado pelo Usuário Cliente
        public function editarEndereco($id) {
            session_start();

            // verifica se há algum Usuário logado
            if(!isset($_SESSION) || empty($_SESSION)) {
                header('Location: /?erro=3');
            }
            else {

                // realiza a consulta no banco, e obtém os dados do endereço escolhido pelo Usuário Cliente através do id
                $endereco = $this->enderecoService->getEnderecoById($id);

                // gerando o token csrf, e atribuindo nas variáveis: sessão e $csrf
                $csrf = $_SESSION['csrf_editar_endereco'] = bin2hex(random_bytes(32));

                // exibe a tela contendo o formulário de editar/atualizar os dados do eendereço do Usuário Cliente
                include __DIR__ . '/../views/painel/editar_endereco.phtml';
            }
        }

        // método que edita/atualiza os dados do endereço do Usuário Cliente
        public function editarEnderecoBanco() {

            // verifica se a requisição recebida é do tipo POST
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                // inicia sessão de usuário
                session_start();

                // compara o token csrf do formulário de Editar/Atualizar Endereço do Usuário Cliente com o token csrf gerado e armazenado na sessão de usuário
                // resultado das comparações dos tokens com falha!
                if(!hash_equals($_SESSION['csrf_editar_endereco'], $_POST['csrf_editar_endereco'])) {
                    session_destroy();
                    header('Location: /?erro=3');
                }
                
                // resultado das comparações dos tokens com sucesso!
                else {

                    // fecha serviço de sessão de usuário
                    session_write_close();

                    // preenchimento do objeto Cidade
                    $cidade = new Cidade($_POST['cidade'], $_POST['uf']);

                    // preenchimento do objeto Endereco
                    $endereco = new Endereco();
                    $endereco->__set('numero', $_POST['numero']);
                    $endereco->__set('logradouro', $_POST['logradouro']);
                    $endereco->__set('complemento', $_POST['complemento']);
                    $endereco->__set('cep', $_POST['cep']);
                    $endereco->__set('bairro', $_POST['bairro']);
                    $endereco->__set('cidade', $cidade);

                    // objeto de conexão ao banco de dados (PDO)
                    $conexao = new Connection();

                    // instanciação e edição/atualização dos dados do Endereço do Usuário Cliente
                    $enderecoService = new EnderecoService($conexao, $endereco);
                    $resultado = $enderecoService->atualizarEndereco();

                    // possíveis retornos na requisição POST
                    if($resultado) echo 1;
                    else echo 0;
                }
            }
        }

        // método que remove o Endereço selecionado pelo Usuário Cliente
        public function apagarEnderecoBancoById() {
            $resp = $this->enderecoService->apagarEnderecoById($_POST['id']);
            echo $resp;
        }

        // método que elabora, serializa e armazena temporariamente os dados na variável de sessão referente ao frete selecionado pelo Usuário Cliente na tela de Checkout
        public function selecaoFrete() {
            $objetoFrete = new stdClass();
            $entrega = $_POST['dados'];
            $dadosFrete = explode('&', $entrega);
            $scanf = explode('=', $dadosFrete[0]);
            $objetoFrete->idFreteTransportadora = $scanf[1];
            $scanf = explode('=', $dadosFrete[1]);
            $objetoFrete->valorFrete = $scanf[1];
            $scanf = explode('=', $dadosFrete[2]);
            $objetoFrete->tempoEntrega = $scanf[1];
            $scanf = explode('=', $dadosFrete[3]);
            $objetoFrete->transportadoraNome = $scanf[1];
            $scanf = explode('=', $dadosFrete[4]);
            $objetoFrete->tipoEntrega = $scanf[1];
            $obj = serialize($objetoFrete);
            session_start();
            $_SESSION['frete'] = $obj;
            if($_SESSION['frete']) echo true;
            else false;
        }

        // método que obtém os dados do Endereço e os dados do Frete, converte para JSON, e retorna à requisição via Ajax para ser exibido dentro da tela de Checkout
        public function confirmarEnderecoFrete() {
            session_start();
            $_SESSION['id_endereco'] = $_POST['id_endereco'];
            $dadosFrete = $_SESSION['frete'];
            $dados = unserialize($dadosFrete);
            $array = array();
            $_SESSION['id_endereco'] = $_POST['id_endereco'];
            $endereco = $this->enderecoService->getEnderecoById($_SESSION['id_endereco']);
            $array = [
                'endereco' => $endereco,
                'frete' => $dados
            ];
            $array = json_encode($array);
            echo $array;
        }

    }