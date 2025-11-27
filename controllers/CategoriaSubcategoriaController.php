<?php
    class CategoriaSubcategoriaController {
        
        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // chama a página para adicionar Categorias e Subcategorias
        public static function exibirViewAddCategoriaSubcategoria() {
            require_once './../helper/funcoes_adicionais.php';
            session_start();
            if(testarLoginAdmin()) include __DIR__ .'/../views/layouts/add_cat_sub.phtml';
            else header('Location: /?erro=4');
        }

        // MÉTODO QUE RECEBE UM REDIRECIONAMENTO DA CLASSE HOME
        // chama a página para editar a Categoria e Subcategoria já cadastrada
        public static function editarCategoriaSubcategoria() {
            require_once './../helper/funcoes_adicionais.php';
            session_start();
            if(!testarLoginAdmin()) header('Location: /?erro=4');
            else {
                $conexao = new Connection();
                $subcategoriaService = new CategoriaService($conexao, new Categoria());
                $listaCategorias = $subcategoriaService->getCategorias();
                $subcategoriaService = new SubcategoriaService($conexao, new Subcategoria());
                $listaSubcategorias = $subcategoriaService->show();
                include __DIR__ .'/../views/layouts/editar_cat_sub.phtml';
            }
        }

    }