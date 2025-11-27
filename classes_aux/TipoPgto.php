<?php
    // essa classe irá definir qual o tipo de pagamento que o cliente escolheu
    class TipoPgto {
        private $tipoPgto;

        public function __construct(array $tipoPgto) {
            $this->tipoPgto = $tipoPgto;
        }

        // método que vai instanciar os objetos de pagamento, transformar para objeto dinâmico, converter para formato JSON e armazenar na variável de sessão de usuário
        public function pagar(): bool {

            // verifica se a requisição recebida é do tipo POST
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                // inicia sessão de usuário
                session_start();

                // compara o token csrf do formulário de preenchimento da seção "Pagamento - Escolha a Forma de Pagamento" da Tela de Checkout, com o token csrf gerado e armazenado na sessão de usuário
                // resultado das comparações dos tokens csrf diferentes!
                if(!hash_equals($_SESSION['csrf_pgto'], $_POST['csrf_pgto'])) {
                    session_destroy();
                    return false;
                }

                // resultado das comparações dos tokens csrf iguais!
                // continua o fluxo dos dados do Pagamento
                else {

                    // fecha serviço de sessão de usuário
                    session_write_close();

                    $objDinamico = null;
                    switch($this->tipoPgto['modalidade']) {

                        // Usuário Cliente seleciona pagamento com cartão de crédito
                        case 1: 
                                

                                        $cartaoCredito = new CartaoCredito(
                                                                        $this->tipoPgto['valor-parcela-checkout'],
                                                                        $this->tipoPgto['parcelas-cartao-credito-checkout'],
                                                                        $this->tipoPgto['validade-cartao-credito-checkout'],
                                                                        $this->tipoPgto['id_bandeira_checkout'],
                                                                        $this->tipoPgto['valor-total-sem-frete-checkout'],
                                                                        $this->tipoPgto['valor-total-com-frete-checkout'],
                                                                        $this->tipoPgto['modalidade']
                                        );
                                    
                                        // o $objDinamico abaixo, refere-se a um objeto do tipo stdClass 
                                        $objDinamico = $cartaoCredito->obterObjetoDinamico();
                                        break;
                                    //}
                                //}

                        // Usuário Cliente seleciona pagamento com boleto bancário
                        case 2: $boleto = new Boleto(
                                                    $this->tipoPgto['vencimento-boleto-checkout'],
                                                    $this->tipoPgto['valor-total-sem-frete-checkout'],
                                                    $this->tipoPgto['boleto-checkout'],
                                                    $this->tipoPgto['modalidade']
                                                );
                                
                                // o $objDinamico abaixo, refere-se a um objeto do tipo stdClass 
                                $objDinamico = $boleto->obterObjetoDinamico();
                                break;

                        // Usuário Cliente seleciona pagamento com pix
                        case 3: $pix = new Pix(
                                                $this->tipoPgto['codigo-pix'],
                                                $this->tipoPgto['valor-total-sem-frete-checkout'],
                                                $this->tipoPgto['pix-checkout'],
                                                $this->tipoPgto['modalidade']
                                );

                                // o objDinamico abaixo, refere-se a um objeto do tipo stdClass 
                                $objDinamico = $pix->obterObjetoDinamico();
                                break;
                    }
                }
            }

            // o objDinamico contém os dados da forma de pagamento escolhido pelo cliente
            // o objDinamico é convertido no formato JSON, para ser armazenado temporariamente na variável de sessão
            $objPgto = json_encode($objDinamico);
            session_start();
            $_SESSION['forma_pgto'] = $objPgto;
            if($_SESSION['forma_pgto']) return true;
            else return false;
        }

    }