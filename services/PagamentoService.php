<?php
    require '../classes_aux/ItensCarrinhoAux.php';
    class PagamentoService extends ItensCarrinhoAux {
        private $conexao;
        private $pagamento;

        public function __construct(Connection $conexao, Pagamento $pagamento) {
            $this->conexao = $conexao->conectar();
            $this->pagamento = $pagamento;
        }

        // método responsável por finalizar a compra, e gerar um Pedido do Usuário Cliente
        //public function pagar(bool $flag): string {
        public function pagar(bool $flag) {
            $resposta = -1;
            if(!$flag) return false;
            else {
                session_start();

                // converte de formato JSON para tipo Objeto
                $formaPgto = json_decode($_SESSION['forma_pgto']);

                $resposta = $this->salvarCompraBanco();
            }
            return $resposta;
        }

        // salvar a compra finalizada pelo Usuário Cliente, preenchendo os dados segundo as suas tabelas de destino
        private function salvarCompraBanco() {
            $id_frete = 0;
            $id_pedido = 0;
            $id_entrega = 0;
            $id_tipo_tabela = 0;
            $id_pagamento = 0;

            date_default_timezone_set('America/Sao_Paulo');
            $id_tipo_tabela = 0;
            try {

                // início das transações dos INSERTS
                $this->conexao->beginTransaction();

                // desserializar objeto do Frete
                $objFrete = $_SESSION['frete'];
                $objFrete = unserialize($objFrete);
                
                // setar os dados na tabela frete
                $query = "INSERT INTO frete(valor, id_forma_envio) VALUES(:valor, (SELECT id FROM forma_envio WHERE nome = :nome));";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':valor', $objFrete->valorFrete);
                $stmt->bindValue(':nome', $objFrete->tipoEntrega);
                if($stmt->execute()) $id_frete = $this->conexao->lastInsertId();
                
                // setar os dados na tabela pedido
                $conexao = new Connection();
                $itensCarrinho = new ItensCarrinhoAux($conexao);
                $listaIDS = $itensCarrinho->getIdsItensCarrinho($_SESSION['id_carrinho']);
                $listaIDS = json_encode($listaIDS);
                $formaPgto = $_SESSION['forma_pgto'];
                $formaPgto = json_decode($formaPgto);
                $query = "INSERT INTO pedido(data_compra, dia, mes, ano, hora_compra, lista_itens_carrinho, valor, id_carrinho) VALUES(:data_compra, :dia, :mes, :ano, :hora_compra, :lista_itens_carrinho, :valor, :id_carrinho)";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':data_compra', date("Y-m-d"));
                $data = date("Y-m-d");
                $dataFiltroAdm = explode('-', $data);
                $stmt->bindValue(':dia', (int) $dataFiltroAdm[2]);
                $stmt->bindValue(':mes', (int) $dataFiltroAdm[1]);
                $stmt->bindValue(':ano', (int) $dataFiltroAdm[0]);
                $stmt->bindValue(':hora_compra', date('H:i:s'));
                $stmt->bindValue(':lista_itens_carrinho', $listaIDS);
                $stmt->bindValue(':valor', $formaPgto->valorTotalSemFrete);
                $stmt->bindValue(':id_carrinho', $_SESSION['id_carrinho']);
                if($stmt->execute()) $id_pedido = $this->conexao->lastInsertId();
                
                // setar os dados na tabela entrega
                $dataAtual = date("Y-m-d");
                $dataEntrega = date('Y-m-d', strtotime('+'. $objFrete->tempoEntrega .' days', strtotime($dataAtual)));
                $status = 'status';
                $query = "INSERT INTO entrega
                                        (tempo_entrega, ". $status .", data_entrega, valor_com_frete, id_endereco, id_cliente, id_pedido, id_frete)
                                        VALUES(:tempo_entrega, :st, :data_entrega, :valor_com_frete, :id_endereco, :id_cliente, :id_pedido, :id_frete);";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':tempo_entrega', $objFrete->tempoEntrega);
                $stmt->bindValue(':st', 'EM TRÂNSITO');
                $stmt->bindValue(':data_entrega', $dataEntrega);
                $stmt->bindValue(':valor_com_frete', $formaPgto->valorTotalComFrete);
                $stmt->bindValue(':id_endereco', $_SESSION['id_endereco']);
                $stmt->bindValue(':id_cliente', $_SESSION['id']);
                $stmt->bindValue(':id_pedido', $id_pedido);
                $stmt->bindValue(':id_frete', $id_frete);
                if($stmt->execute()) $id_entrega = $this->conexao->lastInsertId();
                
                // setar a forma de pagamento nas tabelas cartao_credito, ou boleto ou pix
                switch($formaPgto->id_modalidade) {

                    // cartão de crédito
                    case 1: $query = "INSERT INTO cartao_credito(valor_parcela, nro_parcelas, data_validade, id_bandeira)
	                                    VALUES(:valor_parcela, :nro_parcelas, :data_validade, :id_bandeira);";
                            $stmt = $this->conexao->prepare($query);
                            $stmt->bindValue(':valor_parcela', $formaPgto->valorParcela);
                            $stmt->bindValue(':nro_parcelas', $formaPgto->numeroParcelas);
                            $stmt->bindValue(':data_validade', $formaPgto->dataValidadeCartao);
                            $stmt->bindValue(':id_bandeira', $formaPgto->id_bandeira);
                            if($stmt->execute()) $id_tipo_tabela = $this->conexao->lastInsertId();
                            break;
                    
                    // boleto
                    case 2: $dataVencimento = explode('/', $formaPgto->dataVencimento);
                            $data = $dataVencimento[2] .'-'. $dataVencimento[1] .'-'. $dataVencimento[0];
                            $query = "INSERT INTO boleto(valor, valor_taxa, nro_parcelas, data_vencimento)
	                                    VALUES(:valor, :valor_taxa, :nro_parcelas, :data_vencimento);";
                            $stmt = $this->conexao->prepare($query);
                            $stmt->bindValue(':valor', $formaPgto->valorTotalSemFrete);
                            $stmt->bindValue(':valor_taxa', 1.95);
                            $stmt->bindValue(':nro_parcelas', '1');
                            $stmt->bindValue(':data_vencimento', $data);
                            if($stmt->execute()) $id_tipo_tabela = $this->conexao->lastInsertId();
                            break;
                    // pix
                    case 3: $query = "INSERT INTO pix(valor, chave) VALUES(:valor, :chave)";
                            $stmt = $this->conexao->prepare($query);
                            $stmt->bindValue(':valor', $formaPgto->valorTotalSemFrete);
                            $stmt->bindValue(':chave', $formaPgto->codigoPix);
                            if($stmt->execute()) $id_tipo_tabela = $this->conexao->lastInsertId();
                            break;
                }

                // setar os dados na tabela pagamento
                $status = 'status';
                $query = "INSERT INTO pagamento(". $status .", id_cliente, id_modalidade, id_tipo_tabela, id_entrega)
	                        VALUES(:st, :id_cliente, :id_modalidade, :id_tipo_tabela, :id_entrega);";
                $stmt = $this->conexao->prepare($query);
                if($formaPgto->id_modalidade == 1) $stmt->bindValue(':st', 'APROVADO');
                else if($formaPgto->id_modalidade == 2 || $formaPgto->id_modalidade == 3) $stmt->bindValue(':st', 'PENDENTE');
                $stmt->bindValue(':id_cliente', $_SESSION['id']);
                $stmt->bindValue(':id_modalidade', $formaPgto->id_modalidade);
                $stmt->bindValue(':id_tipo_tabela', $id_tipo_tabela);
                $stmt->bindValue(':id_entrega', $id_entrega);
                if($stmt->execute()) $id_pagamento = $this->conexao->lastInsertId();

                // trazer cada item adicionado e o seu valor contido, do carrinho desse cliente
                $status = 'ic.status';
                $query = "SELECT ic.id AS 'id_itens_carrinho', pr.valor AS 'Valor'
                            FROM pedido pd
                            JOIN carrinho c
                            ON pd.id_carrinho = c.id
                            JOIN itens_carrinho ic
                            ON c.id = ic.id_carrinho
                            JOIN produto pr
                            ON pr.id = ic.id_produto
                            WHERE (pd.id = :id_pedido AND ". $status ." = 1);";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':id_pedido', $id_pedido);
                if($stmt->execute()) $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // setar os dados na tabela fatura
                $hora = date('H:i:s');
                $data = date("Y-m-d");
                $valor_icms = $formaPgto->valorTotalComFrete * 0.17;
                $query = "INSERT INTO fatura(data_emissao, hora_emissao, valor_total, valor_icms, valor_frete, id_pagamento)
                                VALUES(:data_emissao, :hora_emissao, :valor_total, :valor_icms, :valor_frete, :id_pagamento);";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue(':data_emissao', $data);
                $stmt->bindValue(':hora_emissao', $hora);
                $stmt->bindValue(':valor_total', $formaPgto->valorTotalComFrete);
                $stmt->bindValue(':valor_icms', $valor_icms);
                $stmt->bindValue(':valor_frete', $objFrete->valorFrete);
                $stmt->bindValue(':id_pagamento', $id_pagamento);
                if($stmt->execute()) {
                    foreach($produtos as $indice => $valor) {

                        // setar o preço unitário de cada item no carrinho do cliente
                        $status = 'status';
                        $query = "UPDATE itens_carrinho 
                                        SET preco_unit = :preco_unit
                                        WHERE (id = :id AND ". $status ." = 1);";
                        $stmt = $this->conexao->prepare($query);
                        $stmt->bindValue(':preco_unit', $produtos[$indice]['Valor']);
                        $stmt->bindValue(':id', $produtos[$indice]['id_itens_carrinho']);
                        $stmt->execute();
                    }
                }

                // confirma todos os INSERTs acima
                if($this->conexao->commit()) {

                    // fecha o carrinho de compras na tabela itens_carrinho desse cliente (itens dos produtos)
                    $conexao = new Connection();
                    $itensCarrinho = new ItensCarrinhoAux($conexao);
                    $itensCarrinho->fecharCompraItensCarrinho($_SESSION['id_carrinho']);

                    // após ocorrido todos os INSERTS acima, e o fechamento do carrinho de compras do cliente, esse método retornará true
                    return true;
                }
            }
            catch(PDOException $e) {

                // em caso de erro na inserção em alguma tabela acima, esse comando cancelará todas as transações acima
                $this->conexao->rollBack();

                // mensagem de erro de exceção do PDO
                echo 'ERRO: '. $e->getMessage();

                // ocorrendo alguma falha nos INSERTS acima, esse métoddo retornará false
                return false;
            }
            catch(Exception $e) {

                // em caso de erro na inserção em alguma tabela acima, esse comando cancelará todas as transações acima
                $this->conexao->rollBack();

                // mensagem de erro de exceção genérica
                echo 'ERRO: '. $e->getMessage();

                // ocorrendo alguma falha nos INSERTS acima, esse métoddo retornará false
                return false;
            }
        }  
    }