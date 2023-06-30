<?php
declare(strict_types=1);
namespace App\Application\Auth;
use App\Exception\Auth\AuthException;
class Crypto {
    private array $settings;

    public function __construct(array $settings)
    {
      
        $this->settings = $settings;
    }

    public function encrypt_decrypt( $string, $action = 'e' ) {
		// you may change these values to your own
		$secret_key =  $this->settings['ENDECRYPT_SECRET_KEY'];
		$secret_iv =  $this->settings['iv'];

		$output = false;
		$encrypt_method =  $this->settings['method'];
		$key = hash(  $this->settings['hash'], $secret_key );
		$iv = substr( hash(  $this->settings['hash'], $secret_iv ), 0,  $this->settings['length'] );

		if( $action == 'e' ) {
			$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
		}
		else if( $action == 'd' ){
			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		} 
		return $output;
	}
}
?>