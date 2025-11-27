<?php
function meuAutoload($classe) {

    // inicializa as classes da camada de Models
    $caminho = '../models/' . str_replace('\\', '/', $classe) . '.php';
    if (file_exists($caminho)) {
        require_once $caminho;
    }

    // inicializa as classes da camada de Controllers
    $caminho = '../controllers/' . str_replace('\\', '/', $classe) . '.php';
    if (file_exists($caminho)) {
        require_once $caminho;
    }

    // inicializa as classes da camada de Services
    $caminho = '../services/' . str_replace('\\', '/', $classe) . '.php';
    if (file_exists($caminho)) {
        require_once $caminho;
    }
}

// Registra o autoloader
spl_autoload_register('meuAutoload');