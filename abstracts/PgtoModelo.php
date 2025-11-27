<?php

    // classe de pagamento que possui os atributos referentes ao frete
    abstract class PgtoModelo {
        private float $valorTotalSemFrete;
        private float $valorTotalComFrete;
        private int $id_modalidade;

        public function __construct(
            float $valorTotalSemFrete,
            float $valorTotalComFrete,
            int $id_modalidade) {
                $this->valorTotalSemFrete = $valorTotalSemFrete;
                $this->valorTotalComFrete = $valorTotalComFrete;
                $this->id_modalidade = $id_modalidade;            
        }

        public function setValorTotalSemFrete(float $valorTotalSemFrete) {
            $this->valorTotalSemFrete = $valorTotalSemFrete;
        }
        
        public function __get($name) {
            return $this->$name;
        }

    }