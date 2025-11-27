$(function() {

    $('.submenu a').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let nome = $(this).text();
        $.ajax({
            type: 'GET',
            crossDomain: true,
            url: `/sub/${id}`,
            contentType: 'json',
            success: (dados) => {
                dados = JSON.parse(dados)
                let html = `<div class="row">`
                dados.forEach(element => {
                    let precoTotalFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(element.valor)
                    let precoParcelaFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(element.valor_parcela)
                    html += `<div class="col-xl-3 col-lg-4 col-sm-6 col-6">
                                <div class="mt-2 home-produto-caixa">
                                    <div>
                                        <a href="/produto/${element.id}" title="${element.nome}"><img src="./assets/img/${element.imagem}" height="100%" width="100%"></a>
                                    </div>
                                    <div class="home-produto-nome">
                                        ${element.nome}
                                    </div>
                                    <div class="home-produto-marca">
                                        ${element.marca}
                                    </div>
                                    <div class="home-produto-preco">
                                        À Vista: ${precoTotalFormatado}
                                    </div>
                                    <div class="home-produto-condicoes">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <span>À Prazo: ${element.nro_parcelas} X ${precoParcelaFormatado}</span>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mt-3"><a href="/produto/${element.id}" class="btn btn-primary home-produto-botao-VerProduto">Ver Produto</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                });
                html += '</row>'
                if(html == '<div class="row"></row>') {
                    $('#subcategoria-nome').html(`<h1 class="text-center">${nome}</h1>`)
                    $('#tabela-produtos').html('<span class="text-danger">Não há Produtos Cadastrados nessa Subcategoria!</span>')
                }
                else {
                    $('#subcategoria-nome').html(`<h1 class="text-center">${nome}</h1>`)
                    $('#mostrar-produtos').html(html)
                }

                // buscar a lista de marcas dos produtos cadastrados da subcategoria clicada pelo usuário cliente
                $.ajax({
                    type: "GET",
                    crossDomain: true,
                    url: `/marcas-lista`,
                    data: {id},
                    dataType: 'json',
                    success: (response) => {

                        // controle para impedir repetição de uma mesma marca na lista de checkbox
                        let i = 1
                        let id_antigo = response[0].id
                        let html = `<h5>Marcas</h5><div><input id="marca-${response[0].id}" type="checkbox" onclick="marca(${response[0].id}, ${id})"> ${response[0].nome}</div>`
                        if(response.length > 1) {
                            while(i < response.length) {
                                if(response[i].id != id_antigo) {
                                    html += `<div><input id="marca-${response[i].id}" type="checkbox" onclick="marca(${response[i].id}, ${id})"> ${response[i].nome}</div>`
                                    id_antigo = response[i].id
                                    if(i == response.length) break
                                }
                                else if(response[i].id == id_antigo) {
                                    if(i == response.length) break
                                }
                                i++
                            }
                        }

                        // imprime a lista de marcas no formato checkbox
                        $('#marcas').html(html)

                        // monta a caixa de opções do filtro do Maior e Menor Preço
                        $('#filtros-valor').html(`<div class="row">
                                                    <div class="col-12">
                                                        <div class="float-end">
                                                            <select id="filtros-valores" name="filtros-valor">
                                                                <option value="0">Selecione</option>
                                                                <option value="2-${id}">Maior Preço</option>
                                                                <option value="1-${id}">Menor Preço</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>`)

                        // ativa/oculta o menu de Categorias/Subcategorias quando é selecionado/clicado
                        mobileMenu.classList.toggle('show');
                    },
                    error: (erro) => {
                        console.log(`erro: ${erro}`)
                    }
                });
            },
            error: (erro) => {
                console.log('erro: ', erro)
            }
        })
    });

    // Scroll horizontal com botões
    const menu = document.getElementById('menu');
    const leftArrow = document.querySelector('.arrow.left');
    const rightArrow = document.querySelector('.arrow.right');

    const scrollAmount = 200;

    leftArrow.addEventListener('click', () => {
        menu.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    });

    rightArrow.addEventListener('click', () => {
        menu.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    });

    // Menu hamburguer
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    hamburger.addEventListener('click', () => {
        mobileMenu.classList.toggle('show');
    });

});