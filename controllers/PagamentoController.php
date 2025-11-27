<?php
    class PagamentoController {
        private PagamentoService $pagamentoService;

        public function __construct(PagamentoService $pagamentoService) {
            $this->pagamentoService = $pagamentoService;
        }

        // método que é executado quando o Usuário Cliente escolhe o método de Pagamento, trazendo via requisição POST
        public function selecionarPgto() {
            require '../classes_aux/TipoPgto.php';
            $tipoPgto = new TipoPgto($_POST);
            if($tipoPgto->pagar()) echo true;
            else echo false;
        }

        // método responsável por finalizar a compra do Usuário Cliente:
        // - fecha o carrinho do Usuário Cliente,
        // - gera o pedido,
        // - salva os dados da compra,
        // - gera a fatura
        public function pagar() {
            $dados = $_POST['dados'];
            $resposta = $this->pagamentoService->pagar($dados);
            echo $resposta;
        }

    }