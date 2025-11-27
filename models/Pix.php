<?php

    // classe que representa o Pix
    require '../abstracts/PgtoModelo.php';
    class Pix extends PgtoModelo {
        private int $id;
        private string $codigoPix;
        private DateTime $data;

        public function __construct(
            string $codigoPix,
            float $valorTotalSemFrete,
            float $valorTotalComFrete,
            int $id_modalidade) {
                $this->codigoPix = $codigoPix;

                // chama o construtor da superclasse PgtoModelo
                parent::__construct($valorTotalSemFrete, $valorTotalComFrete, $id_modalidade);
        }
        
        public function __get($name) {
            return $this->$name;
        }

        // recupera objeto Pix transformado para Objeto Dinâmico
        public function obterObjetoDinamico(): object {
            $novoObjetoPix = $this->converterObjetoDinamico();
            return $novoObjetoPix;
        }

        // converte objeto Pix para tipo de Objeto Dinâmico (inclusive com os atributos da superclasse PgtoModelo)
        private function converterObjetoDinamico(): stdClass {
            $objetoDinamico = new stdClass();

            // parent::__get   recupera os dados da superclasse PgtoModelo através dos métodos mágicos get
            $objetoDinamico->valorTotalSemFrete = parent::__get('valorTotalSemFrete');
            $objetoDinamico->valorTotalComFrete = parent::__get('valorTotalComFrete');
            $objetoDinamico->id_modalidade = parent::__get('id_modalidade');

            $objetoDinamico->codigoPix = $this->codigoPix;
            return $objetoDinamico;
        }
    }