<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Exception\Auth\AuthException;
use App\Application\Auth\JwtToken;

use App\Factory\DBConFactory;


use  App\Model\AuthModel;

class AuthMiddleware implements Middleware
{
    protected JwtToken $jwtToken;
    protected DBConFactory $dBConFactory;
   

    public function __construct(JwtToken $jwtToken, DBConFactory $dBConFactory)
    {
        $this->jwtToken = $jwtToken;
        $this->dBConFactory = $dBConFactory;
        
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
      
        try {
			//print_r($request);die();
            $jwtHeader = $request->getHeaderLine('authorization');
            $decoded = $this->jwtToken->validateToken($request, $jwtHeader);
            $contents = $request->getParsedBody();
            
            $email = isset($decoded->email)?trim($decoded->email):'';
            $id = isset($decoded->id)?trim($decoded->id):'';

            if(empty($email)){
                throw new AuthException('Invalid Access.', 403);
            }

            if(empty($id)){
                throw new AuthException('Invalid Access.', 403);
            }

            $authModel = new AuthModel($this->dBConFactory);
            $cnt = $authModel->validateEmailandUserId($email, $id);
            if($cnt <= 0){
                throw new AuthException('Invalid Access.', 403);
            }

            $contents['decoded'] = $decoded;
            $request = $request->withParsedBody($contents);
            return $handler->handle($request);
            
        }
        catch (AuthException $e) {
            throw new AuthException('JWT Token invalid.', 401);
        }
        
    }
}
