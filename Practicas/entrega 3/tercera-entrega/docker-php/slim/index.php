<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Models\DB;
use Selective\BasePath\BasePathMiddleware;
use App\Controllers\ItemsMenuController;
use App\Controllers\PedidosController;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers:X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


// a) Crear un nuevo ítem: implementar un endpoint para crear un nuevo ítem en la tabla de items_menu.
// El endpoint debe permitir enviar el nombre, el precio, el tipo (comida o bebida) y la foto.
$app->post('/itemsmenu/create', '\App\Controllers\ItemsMenuController:create');


// b) Actualizar información de un ítem: implementar un endpoint para actualizar la información de un ítem existente en la tabla items_menu. 
// El endpoint debe permitir enviar el id y los campos que se quieran actualizar.
$app->put('/itemsmenu/update/{id}', '\App\Controllers\ItemsMenuController:update');


// c) Eliminar un ítem: el endpoint debe permitir enviar el id del ítem y eliminarlo de la tabla
// solo si no existen pedidos para ese ítem.
$app->delete('/itemsmenu/delete/{id}', '\App\Controllers\ItemsMenuController:delete');


// d) Obtener los ítems ordenados por precio: implemente un endpoint para obtener los ítems
// de la tabla, permitiendo pasar los filtros por tipo (comida/bebida) y por nombre de
// producto (parcial o totalmente, por ejemplo, “gu” encontraría agua y hamburguesa) así
// como también si el orden será ascendente o descendente.
// Si no paso los filtros, todos los registros serán devueltos. Si no paso el orden, por defecto
// será ascendente.
$app->get('/itemsmenu', '\App\Controllers\ItemsMenuController:getAll');


// e) Obtener todos los pedidos del más nuevo al más viejo 
$app->get('/pedidos', '\App\Controllers\PedidosController:getAll');


// f) Crear un nuevo pedido: implementar un endpoint para crear un nuevo pedido en la tabla de pedidos. 
// El endpoint debe permitir enviar el número de mesa, el id del ítem del menú y un comentario (opcionalmente).
$app->post('/pedidos/create', '\App\Controllers\PedidosController:create');


// g) Obtener un pedido: implementar un endpoint que permita obtener un pedido a partir de su id. 
// Este debe mostrar todos los datos del pedido, incluyendo el nombre del item del menú, precio, tipo y foto
$app->get('/pedidos/{id}', '\App\Controllers\PedidosController:getOne');


// h) Eliminar un pedido: el endpoint debe borrar un pedido a partir de su id.
$app->delete('/pedidos/delete/{id}', '\App\Controllers\PedidosController:delete');


$app->run();

?>