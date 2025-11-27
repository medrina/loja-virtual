<?php
    class EnderecoService {
        private $conexao;
        private $endereco;

        public function __construct(Connection $conexao, Endereco $endereco) {
            $this->conexao = $conexao->conectar();
            $this->endereco = $endereco;
        }

        public function __get($name) {
            return $this->$name;
        }

        // método que vai recuperar a lista de Endereços que foram cadastrados por esse Usuário Cliente à tela de Checkout (seção Entrega da tela de Checkout)
        public function getEnderecoByCliente2(Cliente $cliente): array {
            $status = 'status';
            try {
                $query = "SELECT id, logradouro, numero, complemento, bairro, cep, id_cidade
                                FROM endereco e
                                WHERE (". $status ." = 1) AND id_cliente = :id";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':id', $cliente->getID());
                if($stmt->execute()) {

                    // variável que possui o resultado da consulta de todos os endereços cadastrados desse Usuário Cliente
                    $endereco = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // array associativo que servirá para armazenar os endereços em formato de Objeto
                    $array = [];

                    // variável que servirá de incremento de posição do array associativo
                    $i = 0;

                    // leitura da lista de endereços obtidos pela query acima
                    foreach($endereco as $indice => $valor) {

                        // a cada iteração do foreach, precisa criar um novo objeto Endereco
                        $end = new Endereco();

                        // preenchimento dos dados provenientes da consulta do banco, no objeto Endereco
                        $end->__set('id', $endereco[$indice]['id']);
                        $end->__set('logradouro', $endereco[$indice]['logradouro']);
                        $end->__set('numero', $endereco[$indice]['numero']);
                        $end->__set('bairro', $endereco[$indice]['bairro']);
                        $end->__set('cep', $endereco[$indice]['cep']);
                        $end->__set('id_cidade', $endereco[$indice]['id_cidade']);
                        $end->__set('complemento', $endereco[$indice]['complemento']);

                        // a cada iteração do foreach, é adicionado um novo objeto Endereco desse Usuário Cliente no array associativo
                        $array[$i] = [
                            'id_cliente' => $cliente->getID(),
                            'endereco' => $end
                        ];

                        // incremento que altera as posições do array associativo
                        $i++;
                    }
                    return $array;
                }
                else return false;
            }
            catch(PDOException $e) {
                echo 'Erro: '. $e->getMessage();
            }
        }

        // método que retorna os dados da Cidade
        public function getCidadeByID_2(int $id): Cidade {
            try {
                $query = "SELECT nome, uf FROM cidade WHERE id = :id_cidade";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':id_cidade', $id);
                if($stmt->execute()) {
                    $cidade = $stmt->fetch(PDO::FETCH_ASSOC);
                    $cidade = new Cidade($cidade['nome'], $cidade['uf']);
                    return $cidade;
                }
                else return false;
            }
            catch(PDOException $e) {
                echo 'Erro: '. $e->getMessage();
            }
        }

        // método responsável por cadastrar um novo Endereço registrado nesse Usuário Cliente
        public function criarEndereco(): bool {
            $id_cidade = 0;
            $status = 'status';

            // verifica se o endereço digitado pelo cliente já existe
            try {
                $query = "SELECT count(*) AS 'quant' FROM endereco 
                    WHERE
                        (". $status ." = 1) AND
                        id_cliente = :id AND id_cidade = (SELECT id FROM cidade WHERE nome = :cidade) AND 
                        cep = :cep AND
                        numero = :numero AND
                        complemento = :complemento AND
                        bairro = :bairro AND
                        logradouro = :logradouro";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':id', $this->endereco->__get('cliente')->getID());
                $stmt->bindValue(':cidade', $this->endereco->__get('cidade')->__get('nome'));
                $stmt->bindValue(':cep', $this->endereco->__get('cep'));
                $stmt->bindValue(':numero', $this->endereco->__get('numero'));
                $stmt->bindValue(':complemento', $this->endereco->__get('complemento'));
                $stmt->bindValue(':bairro', $this->endereco->__get('bairro'));
                $stmt->bindValue(':logradouro', $this->endereco->__get('logradouro'));
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }

            // se a contagem dos registros do endereço informado pelo cliente retornar maior que 0, então significa que esse endereço já existe no banco
            if($resultado['quant'] > 0) return false;

            // se a consulta não encontrou nenhum endereço, significa que se trata de um novo endereço que o Usuário Cliente informou, então, executa o else abaixo
            else {

                // verifica se a cidade já existe
                try {
                    $query = "SELECT id FROM cidade WHERE nome = :cidade";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue(':cidade', $this->endereco->__get('cidade')->__get('nome'));
                    $stmt->execute();
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }

                // se a cidade existir, recupera o id dela do banco
                if($resultado) $id_cidade = $resultado['id'];

                // se a cidade não existir, salva ela no banco, e recuperando o id recém gerado pra ela 
                else {

                    // cadastra a nova Cidade
                    try {
                        $query = "INSERT INTO cidade(nome, uf) VALUES(:nome, :uf);";
                        $stmt = $this->conexao->prepare($query);
                        $stmt->bindValue(':nome', $this->endereco->__get('cidade')->__get('nome'));
                        $stmt->bindValue(':uf', $this->endereco->__get('cidade')->__get('uf'));
                        $stmt->execute();

                        // recupera o id da cidade recém gerado na query acima
                        $id_cidade = $this->conexao->lastInsertId();
                    }
                    catch(PDOException $e) {
                        echo 'erro: '. $e->getMessage();
                    }
                }

                // salva o novo endereço, junto com id da cidade (FK)
                try {
                    $status = 'status';
                    $query = "INSERT INTO endereco(logradouro, numero, complemento, bairro, cep, ". $status .", id_cliente, id_cidade) 
                                    VALUES(:logradouro, :numero, :complemento, :bairro, :cep, :st, :id_cliente, :id_cidade);";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue(':logradouro', $this->endereco->__get('logradouro'));
                    $stmt->bindValue(':numero', $this->endereco->__get('numero'));

                    // esse dado é opcional
                    $stmt->bindValue(':complemento', $this->endereco->__get('complemento'));
                    
                    $stmt->bindValue(':bairro', $this->endereco->__get('bairro'));
                    $stmt->bindValue(':cep', $this->endereco->__get('cep'));

                    // inicialmente, o status de cada endereço será atribuído o valor de 1 (atestando que se trata de um endereço disponível para destinatário de entrega) 
                    $stmt->bindValue(':st', 1);

                    $stmt->bindValue(':id_cliente', $this->endereco->__get('cliente')->getID());
                    $stmt->bindValue(':id_cidade', $id_cidade);
                    if($stmt->execute()) return true;
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }
            }
        }

        // método responsável por consultar e recuperar a lista de Endereços cadastrados desse Usuário Cliente
        public function getListaEnderecos() {
            $status = 'e.status';
            try {
                $query = "SELECT e.id, logradouro, numero, complemento, bairro, cep, nome, uf
                    FROM endereco e JOIN cidade c
                    ON e.id_cidade = c.id
                    WHERE (". $status ." = 1) AND id_cliente = :id_cliente ORDER BY e.id_cidade";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':id_cliente', $this->endereco->__get('cliente')->getID());
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if($resultado) return $resultado;
                else return null;
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }
        }

        // método responsável por consultar e localizar um Endereço específico pelo seu id desse Usuário Cliente
        public function getEnderecoById($id): array {
            try {
                $query = "SELECT e.id AS id_endereco, c.id AS id_cidade, logradouro, numero, complemento, bairro, cep, nome, uf
                    FROM endereco e JOIN cidade c
                    ON e.id_cidade = c.id
                    WHERE e.id = :id";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':id', $id);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                return $resultado;
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }
        }

        // método responsável por atualizar um Endereço específico desse Usuário Cliente
        public function atualizarEndereco(): bool {

            // verifica se o endereço digitado pelo cliente já existe
            try {
                $query = "SELECT count(*) AS 'quant' FROM endereco 
                    WHERE 
                        id_cidade = (SELECT id FROM cidade WHERE nome = :cidade) AND 
                        cep = :cep AND
                        numero = :numero AND
                        complemento = :complemento AND
                        bairro = :bairro AND
                        logradouro = :logradouro AND
                        id_cliente = :id_cliente
                        ";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':cidade', $this->endereco->__get('cidade')->__get('nome'));
                $stmt->bindValue(':cep', $this->endereco->__get('cep'));
                $stmt->bindValue(':numero', $this->endereco->__get('numero'));
                $stmt->bindValue(':complemento', $this->endereco->__get('complemento'));
                $stmt->bindValue(':bairro', $this->endereco->__get('bairro'));
                $stmt->bindValue(':logradouro', $this->endereco->__get('logradouro'));
                session_start();
                $stmt->bindValue(':id_cliente', $_SESSION['id']);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e) {
                echo 'erro: '. $e->getMessage();
            }

            // se a contagem dos registros do endereço informado pelo cliente retornar maior que 0, então significa que esse endereço já existe no banco
            if($resultado['quant'] > 0) return false;
            
            else {

                // verifica se a cidade já existe
                try {
                    $query = "SELECT id FROM cidade WHERE nome = :cidade";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue(':cidade', $this->endereco->__get('cidade')->__get('nome'));
                    $stmt->execute();
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }

                // se a cidade existir, recupera o id dela do banco
                if($resultado) {
                    $id_cidade = $resultado['id'];
                }

                // se a cidade não existir, cadastra ela no banco, e recupera o id recém gerado pra ela
                else {

                    // cadastra a nova cidade
                    try {
                        $query = "INSERT INTO cidade(nome, uf) VALUES(:nome, :uf);";
                        $stmt = $this->conexao->prepare($query);
                        $stmt->bindValue(':nome', $this->endereco->__get('cidade')->__get('nome'));
                        $stmt->bindValue(':uf', $this->endereco->__get('cidade')->__get('uf'));
                        $stmt->execute();

                        // recupera o id da cidade recém gerado na query acima
                        $id_cidade = $this->conexao->lastInsertId();
                    }
                    catch(PDOException $e) {
                        echo 'erro: '. $e->getMessage();
                    }
                }

                // atualizar o endereço
                try {
                    $query = "UPDATE endereco 
                        SET 
                            logradouro = :logradouro,
                            numero = :numero,
                            complemento = :complemento,
                            bairro = :bairro,
                            cep = :cep,
                            id_cidade = :id_cidade        
                            WHERE id = :id_endereco;";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue(':logradouro', $this->endereco->__get('logradouro'));
                    $stmt->bindValue(':numero', $this->endereco->__get('numero'));
                    $stmt->bindValue(':complemento', $this->endereco->__get('complemento'));
                    $stmt->bindValue(':bairro', $this->endereco->__get('bairro'));
                    $stmt->bindValue(':cep', $this->endereco->__get('cep'));
                    $stmt->bindValue(':id_cidade', $id_cidade);
                    $stmt->bindValue(':id_endereco', $_POST['id_endereco']);
                    if($stmt->execute()) return true;
                }
                catch(PDOException $e) {
                    echo 'erro: '. $e->getMessage();
                }
            }
        }

        // método responsável por apagar o Endereço pelo seu id, desse Usuário Cliente
        public function apagarEnderecoById(int $id): bool {
            $status = 'status';
            try {
                $query = "UPDATE endereco 
                                    SET ". $status ." = :st
                                    WHERE (id = :id_endereco AND ". $status ." = 1);";
                $stmt = $this->conexao->prepare($query);

                // quando o Usuário Cliente quiser apagar o Endereço da sua conta, o status será alterado para o valor de 0
                // NOTA: esse procedimento apenas desativa e impede do Endereço removido de ser exibido na lista de Endereços desse Usuário Cliente
                // O Endereço continua existindo, por causa do destinatário de entrega do Pedido e Nota Fiscal (por esse motivo, o registro do endereço não é apagado totalmente da tabela endereco)
                $stmt->bindValue(':st', 0);
                
                $stmt->bindValue(':id_endereco', $id);
                if($stmt->execute()) return true;
            }
            catch(PDOException $e) {
                echo 'Erro: '. $e->getMessage();
            }
        }
        
    }