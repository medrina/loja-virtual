<?php

    // classe responsável por estabelecer a conexão com o banco de dados
    class Connection {

        private $dsn = 'mysql:host=localhost;dbname=loja';
        private $usuario = '';
        private $senha = '';
        private $conexao;

        public function conectar() {
            try {
                $this->conexao = new PDO($this->dsn, $this->usuario, $this->senha);
                return $this->conexao;
            }

            // quando o banco de dados estiver indisponível, esse erro irá ser capturado e tratado aqui dentro desse catch, que exibirá uma página de erro
            catch(PDOException $e) {
                include_once '../views/layouts/erro.phtml';
            }
        }
    }
?>
