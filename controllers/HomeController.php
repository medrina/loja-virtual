<?php
    use Connection as GlobalConnection;

    class HomeController {

        // método inicial que busca a lista das Categorias com as suas Subcategorias, para ser exibido no menu de dropdown na página home
        public function index() {
            $conexao = new GlobalConnection();
            require '../classes_aux/CategoriaSubcategoriaService.php';
            $cat_subService = new CategoriaSubcategoriaService($conexao, new Categoria(), new Subcategoria());
            $cat = $cat_subService->show();
            include __DIR__ .'/../views/layouts/home.phtml';
        }

        // método que exibe o painel de usuários Cliente e Administrador (somente quando usuários estiverem autenticados)
        public function painel() {
            session_start();
            if(empty($_SESSION)) {
                header('Location: /?erro=3');
            }
            else {
                $cliente = new Cliente();
                $cliente->setNome($_SESSION['nome']);
                $cliente->setId($_SESSION['id']);
                $cliente->setEmail($_SESSION['email']);
                $conexao = new GlobalConnection();
                $getProdutos = new ClienteService($conexao, $cliente);
                $getProdutos->getClienteModal();
            }
        }
        //-----------------------------------------------------------------------------------------------------------------------------------------
        // MÉTODOS DE REDIRECIONAMENTOS: métodos que apenas redirecionam as requisições HTTP para os Controllers corretos
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_exibirViewAddCategoriaSubcategoria() {
            CategoriaSubcategoriaController::exibirViewAddCategoriaSubcategoria();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_editarCategoriaSubcategoria() {
            CategoriaSubcategoriaController::editarCategoriaSubcategoria();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_login() {
            LoginController::exibirTelalogin();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_validarLogin() {
            LoginController::validarLogin();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_criarLogin() {
            LoginController::exibirTelaCadastro();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_criarCadastro() {
            LoginController::criarCadastro();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_validarLoginModal() {
            LoginController::validarLoginModal();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_logoff() {
            LoginController::logoff();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_adicionarProduto() {
            ItensCarrinhoController::adicionarProdutoCarrinho();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_removerProdutoCarrinho() {
            ItensCarrinhoController::removerProdutoCarrinho();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_gerarPedido() {
            PedidoController::gerarPedido();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_getListMarcas() {
            MarcaController::getMarcas();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_addMarca() {
            MarcaController::addMarca();
        }
        
        // método que redireciona a execução para o Controller correto
        public function redirecionar_listaEnderecos() {
            EnderecoController::getEnderecos();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_adicionarEndereco() {
            EnderecoController::exibirTelaAdicionarEndereco();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_addEndereco() {
            EnderecoController::criarEnderecoCliente();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_dadosPessoais() {
            ClienteController::getDadosPessoais();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_editarDadosPessoais() {
            ClienteController::editarDadosPessoaisCliente();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_salvarDadosPessoaisBanco() {
            ClienteController::salvarDadosPessoaisCliente();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_editarDadosAdmin() {
            ClienteController::editarDadosPessoaisAdministrador();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_salvarDadosPessoaisAdminBanco() {
            ClienteController::salvarDadosPessoaisAdministrador();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_pedidosCliente() {
            PedidoController::getPedidosCliente();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_getPedidoCliente() {
            PedidoController::getPedidoCliente();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_getPedidosAdm() {
            PedidoController::getPedidosAdministrador();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_getPedidoAdm() {
            PedidoController::getPedidoAdministrador();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_alterarStatusPgtoPedido() {
            PedidoController::alterarStatusPedidoEntrega();
        }

        // método que redireciona a execução para o Controller correto
        public function redirecionar_loginTest() {
            LoginController::loginStatus();
        }

        public function redirecionar_loginTestAdmin() {
            LoginController::loginStatusAdmin();
        }

    }