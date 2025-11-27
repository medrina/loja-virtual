<?php

    // classe que representa o boleto bancário
    require '../abstracts/PgtoModelo.php';
    class Boleto extends PgtoModelo {
        private int $id;
        private float $valorCusto;
        private string $date;

        public function __construct(
            string $date,
            float $valorTotalSemFrete,
            float $valorTotalComFrete,
            int $id_modalidade,
            float $valorCusto = 1.80) {
                $this->valorCusto = $valorCusto;
                $this->date = $date;

                // chama o construtor da superclasse PgtoModelo
                parent::__construct($valorTotalSemFrete, $valorTotalComFrete, $id_modalidade);
        }

        public function __get($name) {
            return $this->$name;
        }

        // métodos da classe abstrata Pagamento
        public function pagar_() {
            echo 'pagar com boleto';
            //echo '<pre>'; print_r($this); echo '</pre>';

            // executa o método mágico get da superclasse PgtoModelo
            echo parent::__get('valorTotalSemFrete');
        }

        // recupera objeto Boleto transformado para Objeto Dinâmico
        public function obterObjetoDinamico(): object {
            $novoObjetoBoleto = $this->converterEmObjetoDinamico();
            return $novoObjetoBoleto;
            //$conexao = new Connection();
            //$this->pagamentoService->pagarComBoleto($novoObjetoBoleto);
        }

        // converte objeto Boleto para tipo de Objeto Dinâmico (inclusive com os atributos da superclasse PgtoModelo)
        private function converterEmObjetoDinamico(): stdClass {
            $objetoDinamico = new stdClass();

            // parent::__get   recupera os dados da superclasse PgtoModelo através dos métodos mágicos get
            $objetoDinamico->valorTotalSemFrete = parent::__get('valorTotalSemFrete');
            $objetoDinamico->valorTotalComFrete = parent::__get('valorTotalComFrete');
            $objetoDinamico->id_modalidade = parent::__get('id_modalidade');

            $objetoDinamico->dataVencimento = $this->date;
            return $objetoDinamico;
        }

    }