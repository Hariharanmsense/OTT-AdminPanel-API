<?php

declare(strict_types=1);

namespace App\Application\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Exception\Auth\AuthException;

class JwtToken
{
    private array $settings;
  

    public function __construct(array $settings)
    {
      
        $this->settings = $settings;
    }
	public function getToken($token){
		return JWT::encode($token, $this->settings['JWT_SECRET_KEY'], $this->settings['hash']);
	}

    public function validateToken($request, $jwtHeader){

        try{
            if (empty($jwtHeader) === true) {
                throw new AuthException('JWT Token required.', 403);
            }
    
            $jwt = explode('Bearer ', $jwtHeader);
            if (!isset($jwt[1])) {
                throw new AuthException('JWT Token invalid or Expired.', 403);
            }
            $decoded = $this->checkToken($jwt[1]);
            return $decoded;

        }catch (AuthException $e) {
            throw new AuthException('JWT Token invalid or Expired.', 403);
        } 

    }

	public function checkToken(string $token)
    {
        try {
            
            //$decoded = JWT::decode($token, $this->settings['JWT_SECRET_KEY']);
            $decoded = JWT::decode($token, new Key($this->settings['JWT_SECRET_KEY'], $this->settings['hash']));

            //print_R($decoded);die();
			
            if (is_object($decoded) && isset($decoded->sub)) {
                return $decoded;
            }

            throw new AuthException('JWT Token invalid', 403);
        } catch (AuthException $e) {
            throw new AuthException('JWT Token invalid', 403);
        } 
    }
}
