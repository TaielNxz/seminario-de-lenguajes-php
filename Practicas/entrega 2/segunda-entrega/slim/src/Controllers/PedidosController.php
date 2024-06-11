<?php

    namespace App\Controllers;

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use App\Models\DB;

    class PedidosController {

        function create( Request $request, Response $response, $args ) {    

            // Obtener datos del pedido
            $data = $request->getParsedBody();
            $idItemMenu = $data["idItemMenu"] ?? null;
            $nromesa = $data["nromesa"] ?? null;
            $comentarios = $data["comentarios"] ?? null;
            $fechaAlta = date('Y-m-d h:i:s');

            // verificar que el comentario sea un string
            $comentarioValido = true;
            if( !is_null( $comentarios ) && !is_string( $comentarios ) ) {
                $comentarioValido = false;
            }

            try{

                if( !is_null($idItemMenu) && is_numeric($idItemMenu) 
                 && !is_null($nromesa) && is_numeric($nromesa) 
                 && $comentarioValido ) {

                    // Instanciar clase DB y Conectarse a la base de datos
                    $db = new DB();
                    $conn = $db->conectar();

                    // Consultar si el Item del menu existe
                    $query = "SELECT * FROM items_menu WHERE id = $idItemMenu";
                    $stmt = $conn->query($query);
                    $item = $stmt->fetch(\PDO::FETCH_OBJ);

                    // Verificar que el Item del menu exista
                    if ( !$item ) {

                        // Si el item no existe, enviar un mensaje de error con código de estado 404 (No encontrado)
                        $response->getBody()->write("El item del menú no existe");
                        return $response->withHeader('content-type', 'application/json')
                            ->withStatus(404);
                    }

                    // Preparar la consulta para insertar el pedido
                    $query = "INSERT INTO pedidos ( idItemMenu, nromesa, comentarios, fechaAlta ) VALUES ( :idItemMenu, :nromesa, :comentarios, :fechaAlta )";
                    $stmt = $conn->prepare($query);
            
                    // Asociar variables a la consulta SQL
                    $stmt->bindParam(':idItemMenu', $idItemMenu);
                    $stmt->bindParam(':nromesa', $nromesa);
                    $stmt->bindParam(':comentarios', $comentarios);
                    $stmt->bindParam(':fechaAlta', $fechaAlta);

                    // Ejecutar la consulta SQL 
                    $result = $stmt->execute();
        
                    // Enviar mensaje de confirmación y código de estado 200 (OK)
                    $response->getBody()->write("Se creo un nuevo pedido");
                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(200);

                } else {

                    // Crear String auxiliar para armar un mensaje de error
                    $aux = "";

                    // Concatenar mensajes de error
                    if( is_null($idItemMenu) ) {
                        $aux = $aux . "El Item del menu es obligatorio\n";
                    } else 
                    if ( !is_numeric($idItemMenu) ){
                        $aux = $aux . "El Item del menu debe tener un valor numerico\n";
                    }

                    if( is_null($nromesa) ) {
                        $aux = $aux . "El numero de mesa es obligatorio\n";
                    } else 
                    if( !is_numeric($nromesa) ){
                        $aux = $aux . "El campo 'numero de mesa' debe tener un valor numerico\n";
                    }

                    if( !$comentarioValido ) {
                        $aux = $aux . "El campo 'comentarios' debe ser un String\n";
                    }
                    
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

        function getAll( Request $request, Response $response, $args ) {    

            // Consultar todos los pedidos y sus items asociados
            $query = "SELECT p.id, p.idItemMenu, p.nromesa, p.comentarios, i.nombre, i.precio, i.tipo, i.imagen, i.tipo_imagen 
                      FROM pedidos p inner join items_menu i on p.idItemMenu = i.id ORDER BY fechaAlta DESC";
            
            try{ 

                // Instanciar clase DB y Conectarse a la base de datos
                $db = new DB();
                $conn = $db->conectar();

                // Preparar y ejecutar consulta SQL para obtener los pedidos
                $stmt = $conn->query($query);
                $pedidos = $stmt->fetchAll(\PDO::FETCH_OBJ);
        
                // Verificar si existen pedidos registrados
                if( !$pedidos ) {
                    // Si no hay pedidos, enviar mensaje de error 404 (Not Found)
                    $response->getBody()->write("No hay pedidos registrados");
                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(404);
  
                }

                // Si exiten, enviar los pedidos obtenidos en formato JSON y el código de estado 200 (OK)
                $response->getBody()->write(json_encode($pedidos));
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

        function getOne( Request $request, Response $response, $args ) {    

             // Obtener el ID del pedido a consultar
            $id = $args["id"];

            // Preparar consulta para obtener el pedido y su item asociado
            $query = "SELECT p.id, p.idItemMenu, p.nromesa, p.comentarios, i.nombre, i.precio, i.tipo, i.imagen, i.tipo_imagen 
                      FROM pedidos p inner join items_menu i on p.idItemMenu = i.id WHERE p.id = $id";

            try{

                // Instanciar clase DB y Conectarse a la base de datos
                $db = new DB();
                $conn = $db->conectar();

                // Preparar y ejecutar consulta para obtener el pedido
                $stmt = $conn->query($query);
                $pedido = $stmt->fetch(\PDO::FETCH_OBJ);

                // Verificar si el pedido existe
                if( !$pedido ) {

                    // Si el pedido no existe, enviar mensaje de error 404 (Not Found)
                    $response->getBody()->write("El pedido no existe");
                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(404);
                }

                // Si exiten, enviar los pedidos obtenidos en formato JSON y el código de estado 200 (OK)
                $response->getBody()->write(json_encode($pedido));
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

        function delete( Request $request, Response $response, $args ){

            // Obtener ID del pedido a eliminar
            $id = $args["id"];

            try{

                // Instanciar clase DB y Conectarse a la base de datos
                $db = new DB();
                $conn = $db->conectar();

                // Consultar si el pedido existe
                $query = "SELECT * FROM pedidos WHERE id = $id";
                $stmt = $conn->query($query);
                $pedido = $stmt->fetch(\PDO::FETCH_OBJ);

                // Verificar si el pedido existe
                if( !$pedido ) {

                    // Si el pedido no existe, enviar mensaje de error 404 (Not Found)
                    $response->getBody()->write("El pedido no existe");
                    return $response->withHeader('content-type', 'application/json')
                                    ->withStatus(404);
                }

                // Eliminar el pedido de la base de datos
                $query = "DELETE FROM pedidos WHERE id = $id";
                $stmt = $conn->prepare($query);
                $result = $stmt->execute();
 
                // Enviar mensaje de confirmación de eliminación y código de estado 200 (OK)
                $response->getBody()->write("El pedido fue eliminado");
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

