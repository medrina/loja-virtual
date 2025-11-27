<?php
    class MarcaController {

        // recupera lista de marcas, pela Subcategoria dos Produtos selecionado pelo Usuário Cliente na tela Home, e convertido em formato JSON, para retorno via Ajax
        public function getMarcasHome() {
            $conexao = new Connection();
            $marcaService = new MarcaService($conexao, new Marca());
            $resultado = $marcaService->getMarcasPorSubcategoria();
            echo json_encode($resultado);
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que busca lista de todas as marcas cadastradas dos produtos para serem preenchidos nos componentes de select do HTML (Modo Administrador)
        public static function getMarcas() {
            $conexao = new Connection();
            $marcaService = new MarcaService($conexao, new Marca());
            $lista = $marcaService->show();
            $lista = json_encode($lista);
            echo $lista;
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // método que adiciona marca nas telas "Cadastrar Produto" e "Atualizar Produto" (Modo Administrador)
        public static function addMarca() {
            $conexao = new Connection();
            $marca = new Marca();
            $marca->__set('nome', $_POST['marca']);
            $marcaService = new MarcaService($conexao, $marca);
            $resultado = $marcaService->setMarcaBanco();
            echo $resultado;
        }
    }