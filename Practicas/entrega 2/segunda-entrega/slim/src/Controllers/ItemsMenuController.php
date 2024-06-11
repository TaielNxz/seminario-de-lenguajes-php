<?php

    namespace App\Controllers;

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use App\Models\DB;
    use Exception;

    class ItemsMenuController {

        function create(Request $request, Response $response, $args) {    

            // Obtener los datos de la Request
            $data = $request->getParsedBody();
            $nombre = $data["nombre"] ?? null;
            $precio = $data["precio"] ?? null;
            $tipo = $data["tipo"] ?? null;
            $imagen = $data["imagen"] ?? null;
            $tipo_imagen = $data["tipo_imagen"] ?? null;

            // Validar que el tipo de comida ingresado sea correcto
            $tipoValido = false;
            if ( $tipo === "COMIDA" || $tipo === "BEBIDA") {
                $tipoValido = true;
            }

            try{

                // Verificar que los datos existan y que sean del tipo de dato correcto
                if( !is_null($nombre) && $nombre != "" && is_string($nombre) 
                 && !is_null($precio) && $precio != "" && is_numeric($precio) 
                 && !is_null($tipo)  && $precio != "" && $tipoValido
                 && !is_null($imagen) && $imagen != ""
                 && !is_null($tipo_imagen) && $tipo_imagen != "")  {

                    // armar una consulta SQL
                    $query = "INSERT INTO items_menu (nombre, precio, tipo, imagen, tipo_imagen) VALUES (:nombre, :precio, :tipo, :imagen, :tipo_imagen)";

                    // Instanciar clase DB y conectar a la base de datos
                    $db = new DB();
                    $conn = $db->conectar();

                    // Preparar la consulta SQL
                    $stmt = $conn->prepare($query);

                    // Asociar variables a la consulta SQL
                    $stmt->bindParam(':nombre', $nombre);
                    $stmt->bindParam(':precio', $precio);
                    $stmt->bindParam(':tipo', $tipo);
                    $stmt->bindParam(':imagen', $imagen);
                    $stmt->bindParam(':tipo_imagen', $tipo_imagen);

                    // Ejecutar consulta SQL
                    $result = $stmt->execute();
        
                    // Escribir un mensaje de confirmación en el cuerpo de la respuesta y enviarla con el código de estado 200 (OK).
                    $response->getBody()->write("Nuevo Item creado correctamente");
                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(200);

                } else {

                    // Crear String auxiliar para armar un mensaje de error
                    $aux = "";

                    // Concatenar mensajes de error
                    if( is_null($nombre) || $nombre == "" ) {
                        $aux = $aux . "El campo nombre es obligatorio\n";
                    } 
                    else if( !is_string($nombre) ) {
                        $aux = $aux . "El campo nombre debe ser un string\n";
                    }

                    if( is_null($precio) || $precio == "" ) {
                        $aux = $aux . "El campo precio es obligatorio\n";
                    }
                    else if( !is_numeric($precio) ) {
                        $aux = $aux . "El campo precio debe tener un valor numerico\n";
                    }

                    if( is_null($tipo) ) {
                        $aux = $aux . "El campo tipo es obligatorio\n";
                    }
                    else if( !$tipoValido ) {
                        $aux = $aux . "El tipo de comida es invalido\n";
                    }
                    
                    if( is_null($imagen) ) {
                        $aux = $aux . "El campo imagen es obligatorio";
                    }

                    // Escribir un mensaje de error en el cuerpo de la respuesta y enviarla con el código de estado 400 (Bad Request)
                    $response->getBody()->write($aux);
                    
                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(400);;
                }
            }
            catch(PDOException $e){
                 
                $errores = array("Mensaje: " => $e->getMessage());
                
                $response->getBody()->write(json_encode($errores));

                return $response->withHeader('content-type', 'application/json')
                                ->withStatus(400);
            }
        }

        function getAll( Request $request, Response $response, array $args ) {    

            // Obtener los datos de la Request
            $data = $request->getQueryParams();
            $nombre = $data["nombre"] ?? null;
            $tipo = $data["tipo"] ?? null;
            $orden = $data["orden"] ?? null;

            // Preparar consulta SQL
            $query = "SELECT * FROM items_menu where 1=1";
    
            // Modificar consulta SQL para aplicar filtros
            if( !is_null($nombre) ){
                $query = $query . " AND nombre like '%$nombre%'";
            }

            if( !is_null($tipo) && $tipo != "" ){
                $query = $query . " AND tipo = '$tipo'";
            }
        
            if( !is_null($orden) ){
                $query = $query . " ORDER BY precio $orden";
            } else {
                $query = $query . " ORDER BY precio asc";
            }

            try{

                // Instanciar la clase DB y conectarse a la base de datos
                $db = new DB();
                $conn = $db->conectar();

                // Ejecutar la consulta SQL para recuperar los elementos de la base de datos
                $stmt = $conn->query($query);

                // Obtener todos los elementos como objetos
                $items = $stmt->fetchAll(\PDO::FETCH_OBJ);
        
                // Comprobar si se encontraron elementos
                if ( !$items ) {
                    // Si no se encontraron elementos, enviar una respuesta 404 No encontrado
                    $response->getBody()->write("No se encontraron Items");
                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(404);
                }

                // Si se encontraron elementos, codificar los elementos en formato JSON y enviar una respuesta 200 OK
                $response->getBody()->write(json_encode($items));
                return $response->withHeader('content-type', 'application/json')
                                ->withStatus(200);
            }
            catch(PDOException $e){
                
                $errores = array("Mensaje: " => $e->getMessage());
                
                $response->getBody()->write(json_encode($errores));

                return $response->withHeader('content-type', 'application/json')
                                ->withStatus(404);
            }
        }

        function update(Request $request, Response $response, $args) {    

            // Obtener ID del ITEM a actualizar
            $id = $request->getAttribute('id');

            // Obtener datos de la Request
            $data = $request->getParsedBody();
            $nombre = $data["nombre"] ?? null;
            $precio = $data["precio"] ?? null;
            $tipo = $data["tipo"] ?? null;
            $imagen = $data["imagen"] ?? null;
            $tipo_imagen = $data["tipo_imagen"] ?? null;
          
            // Validar que el tipo de comida ingresado sea correcto
            $tipoValido = false;
            if ( $tipo == "COMIDA" || $tipo == "BEBIDA") {
                $tipoValido = true;
            }
            
            try{

                // Verificar que los datos existan y que sean del tipo de dato correcto
                if( !is_null($nombre) && $nombre != "" && is_string($nombre) 
                && !is_null($precio) && $precio != "" && is_numeric($precio) 
                && !is_null($tipo)  && $precio != "" && $tipoValido
                && !is_null($imagen) && $imagen != ""
                && !is_null($tipo_imagen) && $tipo_imagen != "")  {

                    // Instanciar clase DB y Conectarse a la base de datos
                    $db = new DB();
                    $conn = $db->conectar();

                    // Preparar consulta para obtener los datos del ITEM actual
                    $query = "SELECT nombre, precio, tipo, imagen FROM items_menu WHERE id = $id";
                    $stmt = $conn->query($query);
                    $item = $stmt->fetch(\PDO::FETCH_OBJ);

                    // Verificar si el ITEM existe
                    if( !$item ) {
                        // Si el ITEM no existe, enviar mensaje de error 404 (Not Found)
                        $response->getBody()->write("El item no existe");
                        return $response->withHeader('content-type', 'application/json')
                                        ->withStatus(404);
                    }

                    // Preparar consulta SQL para actualizar el ITEM
                    $query = "UPDATE items_menu SET nombre = :nombre, precio = :precio, tipo = :tipo, imagen = :imagen, tipo_imagen = :tipo_imagen WHERE id = $id";
                    $stmt = $conn->prepare($query);
            
                    // Asociar variables a la consulta SQL
                    $stmt->bindParam(':nombre', $nombre);
                    $stmt->bindParam(':precio', $precio);
                    $stmt->bindParam(':tipo', $tipo);
                    $stmt->bindParam(':imagen', $imagen);
                    $stmt->bindParam(':tipo_imagen', $tipo_imagen);

                    // Ejecutar la consulta SQL de actualización
                    $result = $stmt->execute();

                    // enviar mensaje de confirmación y código de estado 200 (OK)
                    $response->getBody()->write("El item fue actualizado");
                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(200);


                } else {

                    // Crear String auxiliar para armar un mensaje de error
                    $aux = "";

                    // Concatenar mensajes de error
                    if( is_null($nombre) || $nombre == "" ) {
                        $aux = $aux . "El campo nombre es obligatorio\n";
                    } 
                    else if( !is_string($nombre) ) {
                        $aux = $aux . "El campo nombre debe ser un string\n";
                    }

                    if( is_null($precio) || $precio == "" ) {
                        $aux = $aux . "El campo precio es obligatorio\n";
                    }
                    else if( !is_numeric($precio) ) {
                        $aux = $aux . "El campo precio debe tener un valor numerico\n";
                    }

                    if( is_null($tipo) || $tipo == "" ) {
                        $aux = $aux . "El campo tipo es obligatorio\n";
                    }
                    else if( !$tipoValido ) {
                        $aux = $aux . "El tipo de comida es invalido\n";
                    }
                    
                    if( is_null($imagen) ) {
                        $aux = $aux . "El campo imagen es obligatorio";
                    }

                    // Escribir un mensaje de error en el cuerpo de la respuesta y enviarla con el código de estado 400 (Bad Request)
                    $response->getBody()->write($aux);
                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(400);;
                }    

            }
            catch(PDOException $e){
                 
                $errores = array("Mensaje: " => $e->getMessage());
                
                $response->getBody()->write(json_encode($errores));
                return $response->withHeader('content-type', 'application/json')
                                ->withStatus(400);
            }

        }


        function delete( Request $request, Response $response, $args ){

            // Obtener ID del item a eliminar
            $id = $request->getAttribute('id');

            try{

                
                // Armar consulta para ver si el item existe 
                $query = "SELECT * FROM items_menu WHERE id = $id";

                // Instanciar clase DB y conectar a la base de datos
                $db = new DB();
                $conn = $db->conectar();

                // Preparar la consulta SQL
                $stmt = $conn->query($query);

                // Obtener el item como Objeto
                $item = $stmt->fetch(\PDO::FETCH_OBJ);

                // Verificar si el item existe
                if( !$item ) {

                    // Si el item no existe, enviar mensaje de error 404 (Not Found)
                    $response->getBody()->write("El Item no existe");

                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(404);
  
                }

                // Consultar si el item está asociado a algún pedido ( no puedo elimitar un item si existen pedidos que lo esten referenciando )
                $query = "SELECT * FROM pedidos WHERE idItemMenu = $id";
                $stmt = $conn->query($query);
                $pedidos = $stmt->fetch(\PDO::FETCH_OBJ);

                // Verificar si el elemento está asociado a algún pedido
                if( $pedidos ) {

                    // Si el elemento está asociado a pedidos, enviar mensaje de error 409 (Conflict)
                    $response->getBody()->write("El item no pudo ser eliminado, hay pedidos utilizando este item");
        
                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(409);
                }

                // Eliminar el elemento de la base de datos
                $query = "DELETE FROM items_menu WHERE id = $id";
                $stmt = $conn->prepare($query);
                $result = $stmt->execute();

                // Enviar respuesta con el resultado de la eliminación
                $response->getBody()->write("El item fue eliminado correctamente");

                return $response->withHeader('content-type', 'application/json')
                                ->withStatus(200);
            }
            catch(PDOException $e){

                $errores = array("Mensaje: " => $e->getMessage());
                
                $response->getBody()->write(json_encode($errores));

                return $response->withHeader('content-type', 'application/json')
                                ->withStatus(409);
            }
        }




    }


?>