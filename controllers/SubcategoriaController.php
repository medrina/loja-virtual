<?php
    class SubcategoriaController {
        private SubcategoriaService $subcategoriaService;

        public function __construct(SubcategoriaService $subcategoriaService) {
            $this->subcategoriaService = $subcategoriaService;
        }

        // consultar todas as Subcategorias
        public function index() {
            $subcategorias = $this->subcategoriaService->show();          
            $lista = array();
            foreach($subcategorias as $indice => $valor) {

                // elabora e preenche o array associativo contendo cada Subcategoria junto com o seu id
                $lista[$indice] = [
                    'id' => $subcategorias[$indice]['id'],
                    'nome' => $subcategorias[$indice]['nome']
                ];
            }

            // converte a lista de Subcategorias em formarto JSON, retornando à requisição
            $lista = json_encode($lista);
            echo $lista;
        }

        // retorna todos os produtos pelo id da subcategoria
        public function show($id) {
            $conexao = new Connection();
            $produtos = new SubcategoriaService($conexao, new Subcategoria());
            $prod = $produtos->getProdutos($id);
            $prod = json_encode($prod);
            echo $prod;
        }

        // retorna todos os produtos pelo id da subcategoria (modo Usuário Administrador)
        public function showAdmin($id) {
            $conexao = new Connection();
            $produtos = new SubcategoriaService($conexao, new Subcategoria());
            //$prod = $produtos->getProdutosAdmin($id);
            $prod = $produtos->getProdutos($id);
            $prod = json_encode($prod);
            echo $prod;
        }

        // cadastrar nova SubCategoria no banco (modo Uusário Admninistrador)
        public function salvarSubCategoria() {
            $id_categoria = $_POST['categoria'];
            $nomeSubcategoria = $_POST['nome'];
            $subCategoria = new Subcategoria();
            $subCategoria->__set('nome', $nomeSubcategoria);
            $conexao = new Connection();
            $subCategoriaService = new SubcategoriaService($conexao, $subCategoria);
            $resultado = $subCategoriaService->salvarSubCategoria();

            // 1: cadastro de Subcategoria já existe
            if($resultado == 1) {
                echo $resultado;
            }

            // 2: cadastro realizado com sucesso
            else if($resultado == 2) {
                $cat = new Categoria();
                $cat->__set('id', $id_categoria);
                $sub = new Subcategoria();
                $sub->__set('id', $_SESSION['id_subcategoria'] );
                $conexao = new Connection();

                // cadastrar Categoria com sua Subcategoria na tabela auxiliar aux_cat_sub
                require '../classes_aux/CategoriaSubcategoriaService.php';
                $cat_sub = new CategoriaSubcategoriaService($conexao, $cat, $sub);
                if($cat_sub->salvarCad_Sub()) echo $resultado;
                else echo 0;
            }
            else echo "else: $resultado";
        }

        // editar/atualizar SubCategoria no banco (modo Usuário Administrador)
        public function atualizarSubCategoriaBanco() {
            $resultado = $this->subcategoriaService->atualizarSubCategoria();
            echo $resultado;
        }
    }