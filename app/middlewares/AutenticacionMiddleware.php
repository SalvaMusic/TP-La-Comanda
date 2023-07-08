<?php
require_once './controllers/JWTController.php';

use Illuminate\Support\Arr;
use LDAP\Result;
use Psr7Middlewares\Middleware\Payload;
use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;

    class AutenticacionMiddleware {
        
        private $tipo;

        public function __construct($tipo){
            $this->tipo = $tipo;
        }

        public function __invoke(Request $request, RequestHandler $handler) : Response
        {
            $response = new Response();

            try{
                $token = $request->getHeaderLine('authorization');
                
                if(!empty($token)){
                    $usuarioId = JWTController::validarToken($token, $this->tipo);
                    
                    if($usuarioId !== false){
                        $parametros = $request->getParsedBody();
                        $parametros["usuarioId"] = $usuarioId;
                        $request = $request->withParsedBody($parametros);
                        $response = $handler->handle($request);
                    } else{
                        $mensaje = 'Token Inválido';
                        $response->getBody()->write($mensaje);
                    }
                } else {
                    $mensaje = "Falta enviar Token";
                    $response->getBody()->write($mensaje);
                }  
            } catch (Exception $e){
                $mensaje = json_encode(array("error" => $e->getMessage())); 
                $response->getBody()->write($mensaje);
            }
            
            return $response;
        }
    }
?>