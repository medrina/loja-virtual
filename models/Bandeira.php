<?php

    // classe que representa as bandeiras do cartão de crédito
    class Bandeira {
        private int $id;
        private string $nome;
                
        public function __set($name, $value) {
            $this->$name = $value;
        }

        public function __get($name) {
            return $this->$name;
        }
    }