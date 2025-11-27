<?php
    class ItensCarrinhoController {
        
        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // adiciona produto ao carrinho
        public static function adicionarProdutoCarrinho() {
            $conexao = new Connection();
            require '../classes_aux/ItensCarrinhoAux.php';
            $itens_carrinho = new ItensCarrinhoAux($conexao);
            $resultado = $itens_carrinho->adicionarProduto();
            echo $resultado;
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // encaminha solicitação de remoção de produto do carrinho do cliente para a classe de Serviço de ItensCarrinhoAux do usuário cliente
        public static function removerProdutoCarrinho() {
            $conexao = new Connection();
            require '../classes_aux/ItensCarrinhoAux.php';
            $itens_carrinho = new ItensCarrinhoAux($conexao);
            $resultado = $itens_carrinho->removerProdutoCarrinho($_POST['id']);
            echo $resultado;
        }
    }