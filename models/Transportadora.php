<?php

    // classe que representa a Transportadora
    class Transportadora {
        private int $id;
        private string $nome;
        
        public function __set($name, $value) {
            $this->$name = $value;
        }

        public function __get($name) {
            return $this->$name;
        }
    }