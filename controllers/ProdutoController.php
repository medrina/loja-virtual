<?php

use Connection as GlobalConnection;

    class ProdutoController {
        private ProdutoService $produtoService;

        public function __construct(ProdutoService $produtoService) {
            $this->produtoService = $produtoService;
        }

        // método que recupera um único produto pelo seu id
        public function show($id) {
            $produto = $this->produtoService->getProduto($id);

            // redireciona para a tela do produto
            include __DIR__ . '/../views/layouts/produto.phtml';
        }

        // método que exibe o forulário para adicionar um produto (modo Usuário Administrador)
        public function cadastrarProduto() {
            require_once './../helper/funcoes_adicionais.php';

            // verifica se o Usuário Administrador está logado
            session_start();
            if(!testarLoginAdmin()) header('Location: /?erro=4');

            // redireciona Usuário Administrador para a tela de Cadastrar Produto
            else {
                include __DIR__ . '/../views/layouts/produto-cadastrar.phtml';
            }
        }

        // carregar view de pesquisar produto para atualizar (modo Administrador)
        public function buscarProduto() {
            require_once './../helper/funcoes_adicionais.php';

            // verifica se o Usuário Administrador está logado
            session_start();
            if(!testarLoginAdmin()) header('Location: /?erro=4');

            // recupera lista de Categorias
            else {
                $conexao = new GlobalConnection();
                $categoriaService = new CategoriaService($conexao, new Categoria());
                $cat = $categoriaService->getCategorias();

                // redireciona Usuário Administrador a tela de buscar produtos (Modo Administrador)
                include __DIR__ . '/../views/layouts/produto-buscar.phtml';
            }
        }

        // método para editar/atualizar produto pelo seu id
        public function editarProduto($id) {
            require_once './../helper/funcoes_adicionais.php';

            // verifica se há algum Usuário Cliente logado
            session_start();
            if(!testarLoginAdmin()) header('Location: /?erro=4');
            else {
                $produto = $this->produtoService->getProduto($id);
                include __DIR__ . '/../views/layouts/produto-editar.phtml';
            }
        }

        // recebe os dados oriundos do formulário de cadastro de produto (modo Administrador)
        public function atualizarProduto() {            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $produto = new Produto();
                $produto->__set('id', $_POST['id_produto']);
                $produto->__set('nome', $_POST['produto_nome']);
                $produto->__set('descricao', $_POST['produto_descricao']);
                $produto->__set('cor', $_POST['produto_cor']);
                $produto->__set('valor', $_POST['produto_valor']);
                $produto->__set('nro_parcelas', $_POST['produto_nro_parcelas']);
                $produto->__set('valor_parcela', $_POST['produto_valor_parcela']);
                $produto->__set('altura', $_POST['produto_altura_valor'] * 100);
                $produto->__set('largura', $_POST['produto_largura_valor'] * 100);
                $produto->__set('comprimento', $_POST['produto_comprimento_valor'] * 100);
                $produto->__set('peso', $_POST['produto_peso_valor']);
                $conexao = new GlobalConnection();
                $produtoService = new ProdutoService($conexao, $produto);
                $resultado = $produtoService->atualizarProdutoBanco();
                echo $resultado;
            }
        }

        // alterar imagem do produto da página Atualizar Produto
        public function alterarImagem() {
            $array = array();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                    $tmp = $_FILES['imagem']['tmp_name'];
                    $nomeImagem = basename($_FILES['imagem']['name']);
                    $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (in_array($_FILES['imagem']['type'], $tiposPermitidos)) {
                        $imagemAtual = '../public/assets/img/';
                        $imagemAtual .= $_POST['imagem_atual'];
                        if(unlink($imagemAtual)) {
                            $resultado = $this->inserirImagem('imagem', $nomeImagem, $tmp);
                            if($resultado) {
                                $atualizacaoImagem = $this->produtoService->atualizarImagemBanco($resultado, $_POST['id_produto']);
                                if($atualizacaoImagem) { 
                                    $array = ['1' => $resultado]; // novo nome da imagem salva no banco com sucesso
                                }
                                else { 
                                    $array = ['5' => 5]; // não foi possível salvar o novo nome da imagem no banco
                                }
                            }
                        }
                        else { 
                            $array = ['4' => 4]; // não foi possível remover a imagem (imagem não se encontra mais na pasta img)
                        }
                    }
                    else { 
                        $array = ['3' => 3]; // formato inválido de arquivo (aceita somente arquivos do tipo imagem)
                    }
                }
                else { 
                    $array = ['2' => 2]; // sem imagem selecionada
                }
            }
            else { 
                $array = ['0' => 0]; // requisição HTTP desconhecida
            }
            $array = json_encode($array);
            echo $array;
        }

        // método que cadastrar um novo produto na loja virtual (modo Usuário Administrador)
        public function cadastrarProdutoBanco() {            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // verificação se consta algum arquivo de imagem em anexo na requisição
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                    $tmp = $_FILES['imagem']['tmp_name'];
                    $nomeImagem = basename($_FILES['imagem']['name']);

                    // definição de extensões de arquivos de imagem permitidos para o armazenamento no sistema de arquivos
                    $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

                    // testa se o arquivo em anexo é compatível com as extensões acima descritas
                    if(in_array($_FILES['imagem']['type'], $tiposPermitidos)) {

                        // a função "gerarNomesRandomicos()" irá gerar um nome aleatório para substituir o nome do arquivo da imagem do produto 
                        require '../helper/funcoes_adicionais.php';
                        $novoNome = gerarNomesRandomicos();
                        $extensao = strrchr($nomeImagem, '.');
                        $novoNome = 'foto_'. $novoNome .'_'. $extensao;

                        // caminho onde serão armazenadas as imagens dos produtos
                        $path = './../public/assets/img/';

                        // verifica se já existe o diretório img
                        // como essa função retorna um boolean, true para já criado, então vamos inverter para false, para ele executar o if
                        if(!is_dir($path)) {

                            // cria o diretório img, com código de permissão CHMOD
                            mkdir($path, 0755);
                        }
                        
                        // pega o caminho do diretório img e concatena com o nome da imagem gerada por um novo nome randômico da imagem do produto selecionado
                        $destino = $path . $novoNome;
                        
                        // pega o arquivo de imagem que está armazenada na pasta temporária (tmp) do diretório do PHP, e move/salva para o caminho:  C:\projetos\PHP\ecommerce_19 - loja8 - 25.10.25 - Cópia\public\assets\img 
                        if(move_uploaded_file($tmp, $destino)) {
                            $conexao = new GlobalConnection();
                            $produto = new Produto();
                            $produto->__set('nome', $_POST['produto_nome']);
                            $produto->__set('valor', $_POST['produto_valor']);
                            $produto->__set('altura', ($_POST['produto_altura_valor']) * 100 );
                            $produto->__set('largura', ($_POST['produto_largura_valor'] * 100));
                            $produto->__set('comprimento', ($_POST['produto_comprimento_valor'] * 100));
                            $produto->__set('peso', $_POST['produto_peso_valor']);
                            $produto->__set('descricao', empty($_POST['produto_descricao']) ? ' ' : $_POST['produto_descricao']);
                            $produto->__set('cor', empty($_POST['produto_cor']) ? ' ' : $_POST['produto_cor']);
                            $produto->__set('nro_parcelas', empty($_POST['produto_nro_parcelas']) ? 0 : $_POST['produto_nro_parcelas']);
                            $produto->__set('valor_parcela', empty($_POST['produto_valor_parcela']) ? 0 : $_POST['produto_valor_parcela']);
                            $produto->__set('imagem_path', $novoNome);
                            $produtoService = new ProdutoService($conexao, $produto);

                            // cadastra o produto
                            $resultado = $produtoService->setProdutoBanco();

                            // novo nome da imagem cadastrada
                            if($resultado) echo 1;

                            // erro do produto a ser cadastrado
                            else echo 0;
                        }
                        else {
                            //echo "Erro ao salvar a imagem.";
                            echo 2;
                        }
                    }
                    else {
                        //echo "Tipo de imagem não permitido.";
                        echo 3;
                    }
                }
                else {
                    //echo "Erro ao receber a imagem.";
                    echo 4;
                }
            }
            else {
                //echo "Requisição inválida.";
                echo 5;
            }
        }

        // alterar quantidade de itens do produto no carrinho de compras do Usuário Cliente
        public function alterarQuantidade() {
            $conexao = new GlobalConnection();
            require '../classes_aux/ItensCarrinhoAux.php';
            $itensCarrinho = new ItensCarrinhoAux($conexao);
            $resultado = $itensCarrinho->alterarQuantidadeProdutoCarrinho();
            $resultado = json_encode($resultado);
            echo $resultado;
        }

        // método responsável por gravar a imagem
        private function inserirImagem($indice, $nomeImagem, $tmp): mixed {

            // gerar nome único
            require '../helper/funcoes_adicionais.php';
            $novoNome = gerarNomesRandomicos();

            // pegar o tipo de arqivo
            $tipo = $_FILES[$indice]['type'];

            // pegar a extensão do arquivo recebido por upload
            $extensao = strrchr($nomeImagem, '.');

            // concatenar novo nome com a extensão de upload do arquivo
            $novoNome = 'foto_'. $novoNome .'_'. $extensao;

            // destino onde a imagem será salva com o novo nome
            $destino = './../public/assets/img/' . $novoNome;

            // move/salva o arquivo recebido para o destino definido acima
            if (move_uploaded_file($tmp, $destino)) return $novoNome;
            else return false;
        }

        // recupera lista de produtos por marca (filtro da tela home)
        public function getProdutosPorID_Marca() {
            $conexao = new GlobalConnection();
            $resultado = $this->produtoService->getListaProdutosPorMarca();
            echo json_encode($resultado);
        }

        // recupera lista de produtos por maior/menor preço
        public function maiorMenorPreco() {
            $consulta = 0;
            switch($_GET['opcao']) {
                case 1: $consulta = $this->produtoService->maiorMenorPreco('ASC');
                        break;
                case 2: $consulta = $this->produtoService->maiorMenorPreco('DESC');
                        break;
            }

            // converte objeto consulta no formato JSON
            echo json_encode($consulta);
        }
        
    }
