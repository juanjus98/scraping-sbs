<?php
// Incluir la clase Tipodecambio
include_once dirname(__FILE__) . '/Tipodecambio.php';

//Inicializar
$tipoCambio = new Tipodecambio();

// Traer todos los tipos de cambio devuletos por la tabla en la web de la SBS
// Retorna un array
$allCambios = $tipoCambio->getAll();
echo '<h4>Todos los tipos cambio.</h4>';
echo '<pre>';
print_r($allCambios);
echo '</pre><hr>';

// Traer un tipo de cambio de una modenda en especifico
// Retorna un array
$cambioDolar = $tipoCambio->getCambio('dolar-de-na');
echo '<h4>Tipo de cambio para Dólares.</h4>';
echo '<pre>';
print_r($cambioDolar);
echo '</pre>';