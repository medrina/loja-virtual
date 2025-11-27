<?php

    // verifica se há alguma sessão aberta no momento
    function testarLogin() {
        if(!isset($_SESSION)) {
            header('Location: /');
        }
    }

    // verifica se há alguma sessão aberta e que não esteja vazia no momento
    function testarLogin2() {
        if(isset($_SESSION) && !empty($_SESSION)) return true;
        else return false;
    }

    // verifica se o Usuário Administrador está logado no sistema
    function testarLoginAdmin(): bool {
        if($_SESSION['id'] == 1) return true;
        else return false;
    }

    // verifica se há alguma sessão aberta no momento
    function testarSessao() {
        session_start();
        if(empty($_SESSION)) {
            return false;
        }
        else return true;
    }

    // testa se existe algum erro igual a 3 presente na requisição GET
    function testarGET() {
        if(isset($_GET['erro']) == 3) return 'ATENÇÃO! Operação não permitida!';
    }

    // método que gera nomes aleatórios para ser gravado no armazenamento das imagens dos produtos
    // OBJ.: elaborar nome de arquivos únicos que seja difíceis de repetir a cada upload de arquivos
    function gerarNomesRandomicos() {
        $alfabeto = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

         // pegar 12 caracteres aleatórios do $alfabeto
        $tamanho = 12;
        $letra = '';
        $resultado = '';

        // a função substr vai pegar da letra do $alfabeto o Nº randômico de 0 a 35 e vai pegar apenas 1 caractere
        for($i = 1; $i <= $tamanho; $i++) {
            $letra = substr($alfabeto, rand(0, strlen($alfabeto)-1), 1);
            $resultado .= $letra;
        }

        // pegar a data atual
        $agora = getdate();

        // criar uma informação relativa ao ano
        // ['yday']   função que retorna a quantidade de dias desde o 1º Jan até o dia atual
        $codigo_ano = $agora['year'] .'_'. $agora['yday'];

        // concatenar horas . minutos . segundos
        $codigo_data = $agora['hours'] . $agora['minutes'] . $agora['seconds'];
        $resultado .= '_' . $codigo_ano .'_'. $codigo_data;
        return $resultado;
    }

    // função que encripta a senha do Usuário Cliente
    function encriptarSenha($senha) {
        $senhaEncriptada = password_hash($senha, PASSWORD_BCRYPT);
        return $senhaEncriptada;
    }

    // função que desencripta e compara a senha digitada com a senha gravada do banco do Usuário Cliente 
    function desencriptarSenha($senha, $senhaBanco) {
        $senhaVerificada = password_verify($senha, $senhaBanco);
        return $senhaVerificada;
    }

    // função que aplica máscara para formato de números
    function aplicarMascara($val, $mask) {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
    