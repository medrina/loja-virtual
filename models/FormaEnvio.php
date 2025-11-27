<?php

    // classe que representa a forma de envio
    class FormaEnvio {
        private int $id;
        private string $nome;
        private Transportadora $transportadora;

        public function __set($name, $value) {
            $this->$name = $value;
        }

        public function __get($name) {
            return $this->$name;
        }
    }