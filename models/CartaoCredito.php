<?php

    // classe que representa o cartão de crédito
    require '../abstracts/PgtoModelo.php';
    class CartaoCredito extends PgtoModelo {
        private int $id;
        private float $valorParcela;
        private int $nroParcelas;
        private string $dataValidade;
        private Bandeira $bandeira;
        private int $idBandeira;

        public function __construct(
            float $valorParcela,
            int $nroParcelas,
            string $dataValidade,
            int $idBandeira,
            float $valorTotalSemFrete,
            float $valorTotalComFrete,
            int $id_modalidade) {
                $this->valorParcela = $valorParcela;
                $this->nroParcelas = $nroParcelas;
                $this->dataValidade = $dataValidade;
                $this->idBandeira = $idBandeira;
                
                // chama o construtor da superclasse PgtoModelo
                parent::__construct($valorTotalSemFrete, $valorTotalComFrete, $id_modalidade);
        }

        public function __get($name) {
            return $this->$name;
        }

        // recupera objeto Pix transformado para Objeto Dinâmico
        public function obterObjetoDinamico(): object {
            $novoObjetoCartaoCredito = $this->converterEmObjetoDinamico();
            return $novoObjetoCartaoCredito;
        }

        // converte objeto Boleto para tipo de Objeto Dinâmico (inclusive com os atributos da superclasse PgtoModelo)
        private function converterEmObjetoDinamico(): stdClass {
            $objetoDinamico = new stdClass();

            // parent::__get   recupera os dados da superclasse PgtoModelo através dos métodos mágicos get
            $objetoDinamico->valorTotalSemFrete = parent::__get('valorTotalSemFrete');
            $objetoDinamico->valorTotalComFrete = parent::__get('valorTotalComFrete');
            $objetoDinamico->id_modalidade = parent::__get('id_modalidade');

            $objetoDinamico->numeroParcelas = $this->nroParcelas;
            $objetoDinamico->valorParcela = $this->valorParcela;
            $objetoDinamico->dataValidadeCartao = $this->dataValidade;
            $objetoDinamico->id_bandeira = $this->idBandeira;
            return $objetoDinamico;
        }
    }