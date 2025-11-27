<?php

    // classe que representa a Entrega do Usuário Cliente
    class Entrega {
        private int $id;
        private string $cod_rastreio;
        private int $tempo_entrega;
        private string $formato;
        private string $status;
        private float $valor_com_frete;
        private Endereco $endereco;
        private Cliente $cliente;
        private Pedido $pedido;
        private Frete $frete;

        public function __set($name, $value) {
            $this->$name = $value;
        }

        public function __get($name) {
            return $this->$name;
        }
    }