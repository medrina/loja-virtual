<?php
    class CategoriaController {
        private CategoriaService $categoriaService;

        public function __construct(CategoriaService $categoriaService) {
            $this->categoriaService = $categoriaService;
        }

        // mostrar todas as Categorias
        public function show(int $id) {
            $conexao = new Connection();
            $categoria = new Categoria();
            $sub = new CategoriaService($conexao, $categoria);
            $sub = $sub->getCategoriaPorID($id);
            $sub = json_encode($sub);
            echo $sub;
        }

        // adicionar nova Categoria
        public function salvarCategoria() {
            $nomeCategoria = ucwords(strtolower($_POST['categoria']));
            $categoria = new Categoria();
            $categoria->__set('nome', $nomeCategoria);
            $conexao = new Connection();
            $categoriaService = new CategoriaService($conexao, $categoria);
            $resultado = $categoriaService->salvarCategoria();
            if($resultado) echo $resultado;
            else echo $resultado;
        }

        // recupera lista de Categorias
        public function getListCategorias() {
            $conexao = new Connection();
            $cat = new CategoriaService($conexao, new Categoria);
            $cat = $cat->getCategorias();
            $listaCategorias = array();
            foreach($cat as $indice => $valor) {
                $listaCategorias[$indice] = [
                    'id' => $cat[$indice]['id'],
                    'nome' => $cat[$indice]['nome']
                ];
            }

            // converte o array associativo em formato JSON para ser retornado via requisição Ajax
            $texto = json_encode($listaCategorias);
            echo $texto;
        }

        // atualiza/edita categoria
        public function atualizarCategoriaBanco() {
            $resultado = $this->categoriaService->atualizarCategoria();
            echo $resultado;
        }
    }