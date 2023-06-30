<?php

declare(strict_types=1);

namespace App\Application\Actions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Action
{

    /**
     * @return array|object
     */
    protected function getFormData($request)
    {
        return $request->getParsedBody();
    }

    /**
     * @return array|object
     */
    protected function getParsedBodyData($request)
    {
        //echo gettype($request);
        $input = $request->getParsedBody();
        return $input;
    }

    protected function getJsonFromParsedBodyData($request)
    {
        //echo gettype($request);
        $input = $request->getParsedBody();
        $JWTdata = json_decode(json_encode($input), false);
        return $JWTdata;
    }

    

    protected function jsonResponse($response, $message, $resultData, $code): Response 
    {

        if(empty($resultData)){
            $result = [
                'code' => $code,
                'status' => true,
                'message' => $message,
            ];
        }else{
            $result = [
                'code' => $code,
                'status' => true,
                'message' => $message,
                'result' => $resultData,
            ];
        }
        

        $json = json_encode($result, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($code);
        
    }

    /**
     * @param array|object|null $data
     */
    protected function respondWithData($response, $data = null, int $statusCode = 200, $message = ''): Response
    {
        $payload = new ActionPayload($statusCode, $data, $message);
        //print_r($payload); die();
        return $this->respond($response, $payload);
    }

    protected function respond($response, $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);
        //print_r($json);die();
        return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus($payload->getStatusCode());
    }
}
