<?php
    class MarcaService {
        private $conexao;
        private $marca;

        public function __construct(Connection $conexao, Marca $marca) {
            $this->conexao = $conexao->conectar();
            $this->marca = $marca;
        }

        // método que seta um objeto do tipo Marca no atributo marca da classe MarcaService
        public function setMarca(Marca $marca) {
            $this->marca = $marca;
        }

        // método que retorna objeto do tipo Marca
        public function getMarca(): Marca {
            return $this->marca;
        }

        // consulta e recupera a lista de todas as marcas cadastradas
        public function show(): array {
            try {
                $query = "SELECT id, nome FROM marca ORDER BY nome;";
                $stmt = $this->conexao->query($query);
                if($stmt->execute()) {
                    $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $lista;
                }
            }
            catch(PDOException $e) {
                return false;
            }
        }

        // cadastra uma nova marca
        public function setMarcaBanco(): int {

            // consulta e recupera a quantidade de alguma marca que já existe
            try {
                $query = "SELECT count(*) AS 'quant' FROM marca WHERE nome = :nome";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':nome', $this->getMarca()->__get('nome'));
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }

            // verifica se o resultado na consulta foi encontrado algum registro da marca (para impedir que uma mesma marca seja cadastrada novamente)
            if($resultado['quant']) return 1;
            
            // continua o fluxo normal para efetuar o cadastro de uma nova marca
            else {
                try {
                    $query = "INSERT INTO marca(nome) VALUES(:marca);";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue('marca', $this->getMarca()->__get('nome'));
                    if($stmt->execute()) return 2;
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }
            }
        }

        // obtém a lista de marcas de produtos por Subcategoria
        public function getMarcasPorSubcategoria(): array {
            try {
                $status = 'status';

                // ORDER BY p.id_marca   a consulta se dará por ordem dos ids das marcas, para que os dados sejam agrupados e que facilite a leitura no retorno do Ajax na tela Home
                $query = "SELECT m.id, m.nome FROM marca m
                            JOIN produto p
                            ON m.id = p.id_marca
                            JOIN subcategoria s
                            ON p.id_subcategoria = s.id
                            WHERE (p.id_subcategoria = :id_subcategoria AND ". $status ." = 1) ORDER BY p.id_marca;";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':id_subcategoria', $_GET['id']);
                if($stmt->execute()) {
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $resultado;
                }
                else return false;
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }
        }
    }