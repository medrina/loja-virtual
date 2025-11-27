<?php

    // classe que representa o Clienet
    class Cliente {
        private int $id;
        private string $nome;
        private string $email;
        private string $senha;

        public function __construct() {
            $this->nome = '';
            $this->email = '';
        }

        public function setId(int $id): void {
            $this->id = $id;
        }

        public function setNome(string $nome): void {
            $this->nome = $nome;
        }
        
        public function setEmail(string $email): void {
            $this->email = $email;
        }

        public function setSenha(string $senha): void {
            $this->senha = $senha;
        }

        public function getID(): int {
            return $this->id;
        }
        public function getNome(): string {
            return $this->nome;
        }

        public function getEmail(): string {
            return $this->email;
        }

        protected function getSenha(): string {
            return $this->senha;
        }

    }