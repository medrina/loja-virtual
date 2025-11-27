<?php

    // classe que representa o carrinho de compras do Usuário Cliente
    class Carrinho {
        private int $id;
        private Cliente $cliente;

        public function __construct(Cliente $cliente) {
            $this->cliente = $cliente;
        }

        public function __set($name, $value) {
            $this->$name = $value;
        }

        public function __get($name) {
            return $this->$name;
        }
    }