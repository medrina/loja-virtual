<?php
    class CategoriaSubcategoriaService {
        private $conexao;
        private $categoria;
        private $subcategoria;

        public function __construct(Connection $conexao, Categoria $categoria, Subcategoria $subcategoria) {
            $this->conexao = $conexao->conectar();
            $this->categoria = $categoria;
            $this->subcategoria = $subcategoria;
        }

        // retornar todas as categorias com as suas subcategorias (essa função será executada na página home, preencher combobox, select, menu suspenso,...)
        public function show(): array {
            try {
                $query = "select c.id as 'id_categoria', c.nome as 'categoria', s.id as 'id_subcategoria', s.nome as 'subcategoria'
                            from aux_cat_sub a
                            INNER JOIN categoria c
                            on c.id = a.id_categoria
                            INNER JOIN subcategoria s
                            on s.id = a.id_subcategoria
                            ORDER BY c.nome ASC;";
                $stmt = $this->conexao->query($query);
                if($stmt->execute()) {
                    $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $lista;
                }
            }
            catch(PDOException $e) {
                echo 'Erro: '. $e->getMessage();
            }
        }

        // inserir os ids da categoria e subcategoria na tabela auxiliar aux_cat_sub
        public function salvarCad_Sub(): bool {
            try {
                $query = "INSERT INTO aux_cat_sub(id_categoria, id_subcategoria) VALUES(:categoria, :subcategoria);";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':categoria', $this->categoria->__get('id'));
                $stmt->bindValue(':subcategoria', $this->subcategoria->__get('id'));
                if($stmt->execute()) return true;
            }
            catch(PDOException $e) {
                echo 'ERROR: '. $e->getMessage();
            }
        }

    }