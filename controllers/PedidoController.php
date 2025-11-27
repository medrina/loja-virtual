<?php
    class PedidoController {

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que recupera os dados necessários do usuário Cliente preenchendo na tela de Checkout, para o usuário Cliente poder realizar a compra do(s) produtos(s) do seu carrinho de compras
        public static function gerarPedido() {

            // verifica se há algum Usuário Cliente/Administrador logado
            session_start();
            if(empty($_SESSION)) {
                header('Location: /?erro=3');
            }

            // verifica se a tela de checkout foi redirecionada através de uma requisição POST (ou seja, para que a compra seja devidamente efetuada, 
            // o Usuário Cliente deverá selecionar a opção de "Comprar" na tela do Painel interno, na seção do "Carrinho de Compras", e ser redirecionado para a tela de Checkout)
            else if(array_keys($_POST) == null && isset($_SESSION)) {

                // havendo alguma inconsistência no teste da requisição POST, o sistema redireciona o Usuário Cliente para a tela do Painel interno
                header('Location: /cliente/painel?erro=3');
            }

            // continua o fluxo normal para a tela de Checkout, com o sistema abaixo juntando os dados registrados, para que esse Usuário Cliente possa selecionar as etapas na tela de Checkout
            else {

                // objeto de conexão com o banco de dados (objeto PDO)
                $conexao = new Connection();

                // dados sobre esse Usuário Cliente
                $clienteService = new ClienteService($conexao, new Cliente());
                $clienteService->getClienteByID($_SESSION['id']);
                $clienteAsaasService = new ClienteAsaasService($conexao, new ClienteAsaas());
                $clienteAsaas = $clienteAsaasService->getClienteAsaas();
                
                // obter a lista de todos os endereços cadastrados que estejam vinculados com a conta do Usuário Cliente
                $enderecoService = new EnderecoService($conexao, new Endereco());
                $listaEnderecosCliente = $enderecoService->getEnderecoByCliente2($clienteService->__get('cliente'));
                foreach($listaEnderecosCliente as $indice => $valor) {
                    $id_cidade = $listaEnderecosCliente[$indice]['endereco']->__get('id_cidade');
                    $cidade = $enderecoService->getCidadeByID_2($id_cidade);
                    $listaEnderecosCliente[$indice]['endereco']->__set('cidade', $cidade);
                }

                // obter a lista de todos os produtos adicionados no carrinho de compras do Usuário Cliente 
                require '../classes_aux/ItensCarrinhoAux.php';
                $itens_carrinho = new ItensCarrinhoAux($conexao);
                $listaProdutosCarrinho = $itens_carrinho->getProdutosCarrinhoCliente($_SESSION['id_carrinho']);

                // adição da lista de produtos do carrinho de compras ao Pedido de Compra desse Usuário Cliente
                $pedidoService = new PedidoService($conexao, new Pedido($_POST['valor_total'], $listaProdutosCarrinho));

                // gerando o token csrf, e atribuindo nas variáveis: sessão e $csrf
                $csrf = $_SESSION['csrf_pgto'] = bin2hex(random_bytes(32));

                // Usuário Cliente é redirecionado para a tela de Checkout
                include __DIR__ .'/../views/painel/checkout.phtml';
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que recupera todos os pedidos/compras realizadas pelo usuário Cliente (cliente individual)
        public static function getPedidosCliente() {

            // verifica se há algum Usuário Cliente/Administrador logado
            session_start();
            if(!isset($_SESSION) || empty($_SESSION)) {
                header('Location: /?erro=3');
            }

            // recuperação de todos os Pedidos (compras finalizadas) por esse Usuário Cliente
            else {
                $conexao = new Connection();
                $pedidoService = new PedidoService($conexao, new Pedido(0, []));
                $listaPedidos = $pedidoService->getPedidos();

                // Usuário Cliente é redirecionado para a tela de Pedidos (Minhas Compras)
                include '../views/painel/pedidos.phtml';
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que recupera apenas um pedido/compra escolhido pelo usuário Cliente 
        public static function getPedidoCliente() {

            // verifica se há algum Usuário Cliente/Administrador logado
            session_start();
            if(!isset($_SESSION) || empty($_SESSION)) {
                header('Location: /?erro=3');
            }

            // recupera apenas um pedido (compra realizada) selecionada pelo Usuário Cliente
            else {
                $id_pedido = $_GET['id_pedido'];
                $conexao = new Connection();
                $pedidoService = new PedidoService($conexao, new Pedido(0, []));
                $resultado = $pedidoService->getPedidoPorID($id_pedido);

                // Usuário Cliente é redirecionado para a tela de Pedido
                include '../views/painel/pedido.phtml';
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que recupera todos os pedidos/compras realizadas por todos os usuários Clientes por dia/mês/ano definido pelo usuário Administrador (elemento calendário)
        public static function getPedidosAdministrador() {
            $conexao = new Connection();
            $pedidoService = new PedidoService($conexao, new Pedido(0, []));
            $listaPedidosPorData = $pedidoService->getPedidosPorDiaMesAno();
            if($listaPedidosPorData) echo json_encode($listaPedidosPorData);
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que recupera apenas um pedido/compra por usuário Cliente (cliente individual) pelo usuário Administrador 
        public static function getPedidoAdministrador() {

            // verifica se o Usuário Administrador está logado
            session_start();
            if($_SESSION['id'] != 1) header('Location: /?erro=4');

            // recupera apenas um pedido selecionado pelo Usuário Administrador
            else {
                $conexao = new Connection();
                $pedidoService = new PedidoService($conexao, new Pedido(0, []));
                $resultado = $pedidoService->getPedidoPorID($_GET['id']);

                // Usuário Administrador é redirecionado para a tela de Pedido do Administrador
                include '../views/painel/pedido_adm.phtml';
            }
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que altera o status de entrega no pedido do usuário Cliente pelo usuário Administrador
        public static function alterarStatusPedidoEntrega() {
            $statusEntrega = $_POST['alterar-status-entrega'];
            $status = '';
            $conexao = new Connection();
            $pedidoService = new PedidoService($conexao, new Pedido(0, []));

            // alteração de status de entrega no envio dos produtos ao destinatário do Usuário Cliente
            switch($statusEntrega) {
                case 1: $status = 'PENDENTE';
                        break;
                case 2: $status = 'EM TRÂNSITO';
                        break;
                case 3: $status = 'ENVIADO';
                        break;
                case 4: $status = 'ENTREGUE';
                        break;
                case 0: $status = 'CANCELADO';
                        break;
                default: $status = 'INVÁLIDO';
            }
            $resultado = $pedidoService->atualizarStatusPedidoEntrega($status, $_POST['id_pedido']);
            echo $resultado;
        }

    }