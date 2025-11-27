<?php
    class CarrinhoService extends Cliente {
        private $conexao;
        private Carrinho $carrinho;

        public function __construct(Connection $conexao, Carrinho $carrinho) {
            $this->conexao = $conexao->conectar();
            $this->carrinho = $carrinho;
        }

        // criar e salvar o carrinho pertencente ao cliente que se loga pela 1ª vez no sistema
        public function carrinho(): void {
            try {
                $id = $this->carrinho->__get('cliente')->getID();
                $query = "SELECT count(*) as 'quant' FROM carrinho where id_cliente = $id;";
                $stmt = $this->conexao->query($query);
                $stmt->execute();
                $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }

            // se não houver registro do id do cliente salva na tabela carrinho, significa que esse é o 1º login desse cliente
            if($consulta['quant'] == 0) {

                // criar o carrinho vinculado com o id do cliente (FK) na tabela carrinho do banco de dados
                try {
                    $query = "INSERT INTO carrinho(id_cliente) VALUES($id);";
                    $stmt = $this->conexao->query($query);
                    $_SESSION['status_carrinho'] = false;
                    $_SESSION['id_carrinho'] = $this->conexao->lastInsertId();
                    $conexao = new Connection();
                    $clienteAsaasService = new ClienteAsaasService($conexao, new ClienteAsaas());
                    $clienteAsaasService->criarCadastro();
                    include __DIR__ .'/../views/painel/painel.phtml';
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }
            }

            // se já houver registro do id do cliente salvo na tabela carrinho, significa que esse cliente já fez o processo de login
            else {

                // consulta e preenche o objeto carrinho, de acordo com o id do cliente
                try {
                    $query = "SELECT id FROM carrinho WHERE id_cliente = $id;";
                    $stmt = $this->conexao->query($query);
                    $stmt->execute();
                    $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }
                $this->carrinho->__set('id', $consulta['id']);
                $_SESSION['id_carrinho'] = $this->carrinho->__get('id');

                // obtém todos os produtos que esse cliente já possui no seu carrinho do banco
                require '../classes_aux/ItensCarrinhoAux.php';
                $conexao = new Connection();
                $itens_carrinho = new ItensCarrinhoAux($conexao);
                $listaProdutos = $itens_carrinho->getProdutos($this->carrinho->__get('id'));
            }
        }

    }