$(document).ready(function () {
    let id = 0

    // alteração da URI quando usuário se loga
    let url = window.location.href
    let uri = url.substring(url.indexOf('//') + 1)
    uri = uri.substring(uri.indexOf('/') + 1)
    uri = uri.substring(uri.lastIndexOf('0') + 1)
    if(uri == '/login/validar') window.location.reload()

    // retirar padding do login de modal da tela de adicionar ao carrinho
    let coluna = document.querySelector('html')
    let largura = coluna.offsetWidth
    if(largura < 400) {
        $('#form-modal-caixa').removeClass('p-5')
        $('#form-modal div.form-floating').removeClass('form-floating')
        $('#modalSignin .modal-header').removeClass('p-5')
    }
        
    $('#categoria').on('change', (e) => {
        id = $(e.target).val()
        if(id == 0) {
            alert('Por favor, selecione uma Categoria')
            $('#subcategoria').html('<option>--------</option>')
            $('#tabela-produtos').html('')
        }
        else {
            id = $('#categoria').val()
            $.ajax({
                type: 'GET',
                crossDomain: true,
                url: `/catsub/${id}`,
                contentType: 'json',
                success: (dados) => {
                    dados = JSON.parse(dados)
                    let html = "<option value='0'>--- Selecione ---</option>"
                    dados.forEach(element => {
                        html += `<option value='${element.id}'>${element.nome}</option>`
                    });
                    $('#subcategoria').html(html)
                    $('#tabela-produtos').html('')
                },
                error: (erro) => {
                    console.log('erro: ', erro)
                }
            })
        }
    })

    $('#subcategoria').on('change', (e) => {
        id = $(e.target).val()
        $.ajax({
            type: 'GET',
            crossDomain: true,
            url: `/sub/${id}`,
            contentType: 'json',
            success: (dados) => {
                dados = JSON.parse(dados)
                let html = '<div class="row">'
                dados.forEach(element => {
                    html += `<div class="col-xl-3 col-lg-4 col-sm-6" style="border: 1p solid;">
                                <div class="mt-2" style="padding: 2px; border: 1px solid blue;">
                                    <div class="" style="height: 120px;">
                                        <a href="/produto/${element.id}" title="${element.nome}" target="_blank"><img src="./assets/img/${element.imagem}" height="100%" width="100%"></a>
                                    </div>
                                    <div style="font-size: 14px;">
                                        ${element.nome}
                                    </div>
                                    <div style="font-size: 12px;">
                                        ${element.marca}
                                    </div>
                                    <div style="font-size: 12px;">
                                        À Vista: R$ ${element.valor}
                                    </div>
                                    <div style="font-size: 10px;">
                                        <span>À Prazo: ${element.nro_parcelas} X R$ ${element.valor_parcela}</span>
                                        <span class=""><a href="/produto/${element.id}" class="btn btn-primary" style="font-size: 10px;" target="_blank">Ver Produto</a></span>
                                    </div>
                                </div>
                            </div>`
                });
                html += '</row>'
                if(html == '<div class="row"></row>') $('#tabela-produtos').html('<span class="text-danger">Não há Produtos Cadastrados nessa Subcategoria!</span>')
                else $('#tabela-produtos').html(html)
            },
            error: (erro) => {
                console.log('erro: ', erro)
            }
        })
    })

    $('#botao-add-carrinho').on('click', () => {

        // elemento do botão de fazer logoff (Sair)
        let logoff = $('#link-logoff').val()

        // verifica se há algum usuário logado no momento
        $.ajax({
            type: "GET",
            url: "/login-test",
            dataType: "json",
            success: (response) => {

                // caso 1: quando o usuário está logado, mas a tela do produto não exibe as opções de estar logado
                if(response == 1 && logoff == undefined) {
                    window.location.reload()
                }

                // caso 2: quando o usuário não está mais logado, mas a tela do produto continua exibindo as opções de estar logado
                else if(response == 0 && logoff == 'ola') {
                    alert('Erro ao adicionar produto!!!')
                    window.location.assign('/cliente/painel?erro=3')
                }
                else {

                    // caso 3: quando o usuário está logado, e a tela do produto exibe as opções de estar logado
                    if(logoff == 'ola') {
                        let dados = $('#form-adicionar-produto').serialize()
                        $.ajax({
                            type: "POST",
                            data: dados,
                            url: `/produtos/add`,
                            dataType: "json",
                            success: function (response) {
                                if(response) {
                                    alert('Produto foi adicionado ao carrinho!');
                                }
                            },
                            error: (erro) => {
                                alert('Não foi possível adicionar produto ao seu carrinho! Tente mais tarde.')
                            }
                        });
                    }

                    // caso 4: quando o usuário não está logado, e a tela do produto não exibe as opções de estar logado
                    else {

                        // ativa um modal suspenso contendo um formulário de login na tela do produto
                        $('#modalSignin').css('display', 'block').css('position', 'absolute').css('top', '100px').css('left', '50px')

                        // limpa a mensagem de erro do modal
                        $('#resp-modal').html('')
                    }
                }
            }
        });
        
    })

    // fecha o modal suspenso de login
    $('#fechar-modal-login').on('click', () => {
        $('#modalSignin').css('display', 'none')
    })

    // botão de "Entrar" do modal suspenso
    $('#botao-login-modal').on('click', (e) => {
        $.ajax({
            type: "GET",
            url: "/login-test",
            dataType: "json",
            success: (response) => {
                if(response == 1) window.location.reload()
                else {
                    let dados = $('#form-modal').serialize()
                    $.ajax({
                        type: "POST",
                        data: dados,
                        url: "/login/validar-modal",
                        dataType: 'json',
                        error: (erro) => {

                            // resposta de erro ao realizar login
                            if(erro.responseText == 'erro') {
                                $('#resp-modal').html('<div class="bg-danger-subtle text-danger-emphasis p-3 mt-3">ATENÇÃO: e-mail ou senha inválidos!!! Por favor, informe o seu login e senha corretos!</div>')
                            }

                            // resposta de sucesso ao realizar login
                            else {
                                $('#resp-modal').html('')
                                $('#modalSignin').css('display', 'none')
                                window.location.reload()
                            }
                        }
                    });
                }
            }
        });
    })

    $('#botao-deletar-produto').on('onkeyup', () => {
        let url = $('#deletar-produto').val()
    })
    
    $('#cep').on('keyup', (e) => {
        let cep = $('#cep').val()
        if(cep.length == 8) {
            $('#msg-erro').html('<span class="text-secondary">Aguarde... <i class="fa-solid fa-spinner fa-spin-pulse spinner-cep"></i></span>')
            $.ajax({
                type: "GET",
                url: `https://viacep.com.br/ws/${cep}/json/`,
                dataType: "json",
                success: function (dados) {
                    if(!dados.erro) {
                        $('#rua').val(dados.logradouro)
                        $('#bairro').val(dados.bairro)
                        $('#cidade').val(dados.localidade)
                        $('#uf').val(dados.uf)
                        $('#msg-erro').html('')
                    }
                    else {
                        $('#msg-erro').html('<span class="text-danger">CEP Inválido!</span>')
                        $('#rua').val('')
                        $('#bairro').val('')
                        $('#cidade').val('')
                        $('#uf').val('')
                    }
                },
                error: function (erro) {
                    $('#msg-erro').html('<span class="text-danger">Erro de Conexão!</span>')
                 }
            });
        }
    })

    $('#botao-adicionar-categoria').on('click', () => {
        $.ajax({
            type: "GET",
            url: "/login-test-admin",
            dataType: "json",
            success: (response) => {
                if(response == 0) window.location.assign('/?erro=4')
                else {
                    let nome = $('#cat_nome')
                    if(!nome.val()) {
                        alert('Por favor, digite uma Categoria!')
                    }    
                    else {
                        let dados = $('#form-categoria').serialize()
                        $.ajax({
                            type: "POST",
                            data: dados,
                            url: `/categoria/add`,
                            dataType: "json",
                            success: function (response) {
                                if(response == 1) {
                                    alert('ATENÇÃO! Essa Categoria já foi cadastrada!')
                                }
                                else if(response == 2) {
                                    alert('Categoria cadastrada com sucesso!!!')
                                    window.location.reload()
                                }
                                else alert('Não foi possível adicionar a Categoria!')
                            },
                            error: function (error) {
                                console.log(`error: ${error}`)
                            }
                        });
                    }
                }
            }
        });
    })

    $('#botao-adicionar-subcategoria').on('click', () => {
        $.ajax({
            type: "GET",
            url: "/login-test-admin",
            dataType: "json",
            success: (response) => {
                if(response == 0) window.location.assign('/?erro=4')
                else {
                    let categoria_add = $('#categorias-add').val()
                    let nome = $('#sub_nome')
                    if(!nome.val()) {
                        alert('Por favor, digite uma Subcategoria!')
                    }
                    else if(categoria_add == null) {
                        alert('Por favor, selecione uma Categoria Cadastrada!')
                    }
                    else {
                        let nome = $('#sub_nome')
                        $.ajax({
                            type: "POST",
                            data: {nome: nome.val(), categoria: categoria_add},
                            url: `/subcategoria/add`,
                            dataType: "json",
                            success: function (response) {
                                if(response == 1) {
                                    alert('ATENÇÃO! Essa Subcategoria já foi cadastrada!')
                                }
                                else if(response == 2) {
                                    alert('Subcategoria cadastrada com sucesso!')
                                    window.location.reload()                        
                                }
                                else alert('Não foi possível adicionar a Subcategoria!')
                            },
                            error: function (error) {
                                console.log(`error: ${error}`)
                            }
                        });
                    }
                }
            }
        });
    })

    $('#botao-salvar-dados-admin').on('click', () => {
        $.ajax({
            type: "GET",
            url: "/login-test-admin",
            dataType: "json",
            success: (response) => {
                if(response == 0) window.location.assign('/?erro=4')
                else {
                    let flag = true
                    let teste = $('input')
                    for(var i = 0; i < teste.length; i++) {
                        if(teste[i].value == '') {
                            alert('Preencha todos os campos!!!')
                            flag = false
                            $('#erro-senhas-admin').html('')
                            break;
                        }
                    }
                    if(flag) {
                        let dados = $('#form-editar-dados-admin').serialize()
                        $.ajax({
                            type: "POST",
                            url: "/admin/salvar-dados",
                            data: dados,
                            dataType: "json",
                            success: function (response) {
                                if(response == 0) $('#erro-senhas-admin').html('<span class="text-danger">Senhas não conferem!!!<br>Digite as duas senhas iguais</span>')
                                else if(response == 1) {
                                    alert('Dados Atualizados com Sucesso!!!')
                                    window.location.assign('/cliente/painel')
                                }
                            },
                            error: function (error) {
                                console.log(`erro: ${error}`)
                            }
                        });
                    }
                }
            }
        });
    })

    $('#botao-enviar-editar-dados').on('click', () => {
        $.ajax({
            type: "GET",
            url: "/login-test",
            dataType: "json",
            success: (response) => {
                if(response == 0) window.location.assign('/cliente/painel?erro=3')
                else {
                    let flag = true
                    let camposObrigatorios = $('.campo-obrigatorio')
                    for(var i = 0; i < camposObrigatorios.length; i++) {
                        if(camposObrigatorios[i].value == '') {
                            alert('Preencha todos os campos obrigatórios!!!')
                            flag = false
                            break
                        }
                    }
                    if(flag) {
                        let dados = $('#form-editar-endereco').serialize()
                        $.ajax({
                            type: "POST",
                            url: "/cliente/painel/editar-endereco",
                            data: dados,
                            dataType: "json",
                            success: function (response) {
                                if(response == 0) {
                                    $('#msg-atualizar-endereco').html('<div class="bg-danger-subtle text-danger-emphasis p-3 mb-3 text-center">Esse Endereço já existe!</div>')
                                }
                                else {
                                    $('#msg-atualizar-endereco').html('<div class="bg-success-subtle text-success-emphasis p-3 mb-3 text-center">Endereço Atualizado com Sucesso!!!</span></div>')
                                }
                            },
                            error: function (error) {
                                console.log(`erro: ${error}`)
                            }
                        });
                    }
                }
            }
        });
    })

    $('#opcao-alterar-senha-admin').on('click', () => {
        let chave = $('#opcao-alterar-senha-admin')
        if(chave[0].checked) {
            $('#campos-alterar-senhas-admin').html(
                `<h5>Alteração de Senha</h5>
                <label>Nova Senha</label>
                <input type="password" class="form-control" name="senha-alterar-admin">
                <label>Confirmar Senha</label>
                <input type="password" class="form-control" name="senha-confirmar-admin">
                <div id="erro-senhas-admin"></div>`
            )
        }
        else $('#campos-alterar-senhas-admin').html('')
    })
        
});

function removerProduto(id) {
    $.ajax({
        type: "GET",
        url: "/login-test",
        dataType: "json",
        success: (response) => {
            if(response == 0) window.location.assign('/cliente/painel?erro=3')
            else {
                $.ajax({
                    type: "POST",
                    data: {id: id},
                    url: `/remover`,
                    dataType: "json",
                    success: function (response) {
                        window.location.reload()
                    },
                    error: function (error) {
                        window.location.reload()
                    }
                });
            }
        }
    });
}

// diminuir quantidade do item adicionado no carrinho de compras do Usuário Cliente
function diminuirQuantidade(prod, car) {
    $.ajax({
        type: "GET",
        url: "/login-test",
        dataType: "json",
        success: (response) => {
            if(response == 0) window.location.assign('/cliente/painel?erro=3')
            else {
                var quant = ($(`#quantidade-${prod}`).val()) - 1
                var total = $('#valor-total-php').val()
                var valor_unitario = $(`#valor-unitario-php-${prod}`).val()
                if(quant == 0) {
                    alert('Atenção, não é permitido diminuir a quantidade')
                    quant = 1
                }
                else {
                    $.ajax({
                        type: "GET",
                        url: "/cliente/produto/alterar-quant",
                        data: {quant, prod, car},
                        dataType: "json",
                        success: function (response) {
                            alert('Item diminuído com sucesso!')
                            let novoValorTotalProduto = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(response.total)
                            $(`#valor-total-${prod}`).html(novoValorTotalProduto)
                            $(`#quantidade-${prod}`).val(response.quantidade)
                            let calculo = total - valor_unitario
                            let valorFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(calculo)
                            $('#valor-total-carrinho').html(valorFormatado)
                            $('#valor-total-php').val(calculo)
                            $('#vlr-total').val(calculo)
                        },
                        error: function (error) {
                            console.log(`erro: ${error}`)
                        }
                    });
                }
            }
        }
    });
}

// aumentar quantidade do item adicionado no carrinho de compras do Usuário Cliente
function aumentarQuantidade(prod, car) {
    $.ajax({
        type: "GET",
        url: "/login-test",
        dataType: "json",
        success: (response) => {
            if(response == 0) window.location.assign('/cliente/painel?erro=3')
            else {
                var quant = parseInt($(`#quantidade-${prod}`).val())
                quant += 1
                var total = parseFloat($('#valor-total-php').val())
                var valor_unitario = parseFloat($(`#valor-unitario-php-${prod}`).val())
                $.ajax({
                    type: "GET",
                    url: "/cliente/produto/alterar-quant",
                    data: {quant, prod, car},
                    dataType: "json",
                    success: function (response) {
                        alert('Item aumentado com sucesso!')
                        let novoValorTotalProduto = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(response.total)
                        $(`#valor-total-${prod}`).html(novoValorTotalProduto)
                        $(`#quantidade-${prod}`).val(response.quantidade)
                        let calculo = 0
                        calculo = total + valor_unitario
                        let valorFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(calculo)
                        $('#valor-total-carrinho').html(valorFormatado)
                        $('#valor-total-php').val(calculo)
                        $('#vlr-total').val(calculo)
                    },
                    error: function (error) {
                        console.log(`erro: ${error}`)
                    }
                });
            }
        }
    });
}

// apagar endereço da conta do Usuário Cliente
function apagarEndereco(id) {
    $.ajax({
        type: "GET",
        url: "/login-test",
        dataType: "json",
        success: (response) => {
            if(response == 0) window.location.assign('/cliente/painel?erro=3')
            else {
                const resp = confirm('Tem certeza que deseja apagar esse endereço?')
                if(resp) {
                    $.ajax({
                        type: "POST",
                        url: `/cliente/painel/apagar-endereco`,
                        data: {id},
                        dataType: "json",
                        success: function (response) {
                            if(response) $(`#endereco-id-${id}`).html('')
                        },
                        error: function (error) { 
                            console.log(`erro: ${error}`)
                        }
                    });
                }
            }
        }
    });
}

function cadastrarAdmin() {
    let flag = true
    let dadosPreenchidos = $('#form-modal-cad-admin input')
    for(var i = 0; i < dadosPreenchidos.length; i++) {
        if(dadosPreenchidos[i].value == '') {
            alert('Preencha todos os campos!!!')
            flag = false
            break
        }
    }
    if(flag) {
        $('#botao-cadastrar-admin').html(`<button id="botao-cad-modal-admin" class="btn btn-primary btn-lg w-100" type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                                <span role="status">Cadastrando...</span>
                                                </button>`).css('cursor', 'not-allowed')
        let dados = $('#form-modal-cad-admin').serialize()
        $.ajax({
            type: "POST",
            url: "/cliente/cad-adm",
            data: dados,
            dataType: "json",
            success: ((response) => {
                if(response) {
                    setTimeout(() => {
                        alert('Cadastro do Administrador realizado com sucesso!!!')
                        $('#modalCadastroAdmin').css('display', 'none')
                    }, 2000)
                }
            }),
            error: ((erro) => {
                setTimeout(() => {
                    alert('ATENÇÃO!!!\nNão foi possível cadastrar o Usuário Administrador!\nPor favor, contate a equipe de Suporte!')
                    $('#botao-cadastrar-admin').html(`<button id="botao-cad-modal-admin" class="w-100 mb-2 btn btn-lg rounded-3 btn-primary fonte-modal" onclick="cadastrarAdmin()" type="button">Cadastrar</button>`).css('cursor', 'default')
                }, 2000)
            })
        });
    }
}
