<?php
    require_once '../autoload.php';
    $flag = false;

    // capturar a URI
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // classe de conexão ao banco de dados (PDO)
    require_once '../config/Connection.php';
    $db = new Connection();

    // array contendo as Rotas e os seus respectivos Controllers
    require '../config/rotas.php';

    // leitura das Rotas
    foreach($routes as $route => $action) {

        // transformar a rota em uma expressão regular
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>\w+)', $route);

        // verifica se a URI corresponde a expressão regular
        if (preg_match("~^$regex$~", $uri, $matches)) {

            // separa o nome do Controller e o método nas variáveis abaixo pelo símbolo @
            list($controllerName, $metodo) = explode('@', $action);

            // divide a string do ControllerName em palavras
            $resultado = preg_split('/(?=[A-Z])/', $controllerName);

            // testa para ver se a string é diferente da string Home
            if($resultado[1] != 'Home') {

                // construir e instanciar o model, segundo a string capturada de controllerName
                $model = $resultado[1];
                $model = new $model();
                
                // construir e instanciar o objeto de Serviço, segundo a string capturada de controllerName
                $resultado = $resultado[1].'Service';
                $service = new $resultado($db, $model);
                
                // instancia o objeto, passando por parâmetro o seu respectivo objeto de serviço
                $controlador = new $controllerName($service);

                // verifica se há parâmetros na URI
                if(isset($matches[1])) {

                    // executa o objeto e o seu método, passando o id por parâmetro
                    // OBS: o controlador e o seu método, são definidos no array de rotas
                    $controlador->$metodo($matches[1]);
                }

                // se não consta parâmetros na URI, executa somente o método do seu objeto correspondente ao controllerName
                else {
                    $controlador->$metodo();
                }
            }

            // executa se a string for igual a Home (inicialmente esse controller HomeController não deverá ter um objeto de serviço)
            else {
                $controlador = new $controllerName();
                $controlador->$metodo();
            }

            // flag para controlar que a rota foi encontrada
            $flag = true;

            // se a rota foi encontrada, então ele sai do foreach
            break;
        }
    }

    // testa se a rota foi encontrada ou não
    if(!$flag) echo '<b>Rota Inválida!</b>';