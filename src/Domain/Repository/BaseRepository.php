<?php
declare(strict_types=1);
namespace App\Domain\Repository;
use Respect\Validation\Validator as v;
use App\Exception\AdmnUsrs\AdmnUsrsException;
use App\Model\DB;
use App\Exception\BaseException;

class BaseRepository
{
    
	protected function getUserName($auditBy)
	{
		$sqlQuery = "SELECT userName FROM adminusers WHERE id = '".$auditBy."' ";
		$dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
		$deviceData = $dbObjt->getSingleDatasByObjects($sqlQuery);
		return $deviceData;

	}

	public function getSmtpSettingsFromDb($hotelId, $auditBy){

		$objLogger = $this->loggerFactory->getFileObject('SMTPMailSetting_'.$auditBy, 'BaseRepository');
		$sqlQuery = "CALL SP_GetSmtpSetting(".$hotelId.")";
		$objLogger->info('Query : '.$sqlQuery); 
		$dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
		$smtpSetting = $dbObjt->getSingleDatasByArray($sqlQuery, 'YES');
		$objLogger->info('smtpSetting : '.json_encode($smtpSetting)); 
		return $smtpSetting;
	}
	
	public function getMenuRightStatus($auditBy, $menuid, $hotelid){

		$objLogger = $this->loggerFactory->getFileObject('MenuRightAccess_'.$auditBy, 'BaseRepository');
        $objLogger->info("======= Start AdmnUsrs Repository ================");
        try{
            
			$sqlQuery = "SELECT grp.ReadWriteAccess FROM adminmenugroup AS grp 
                         INNER JOIN adminusers as usr ON usr.userGroup = grp.groupID
                         INNER JOIN adminmenus as mnu ON mnu.MenuID = grp.MenuID
                         WHERE usr.id = '$auditBy' and grp.MenuID = '".$menuid."' and grp.hotelID = '".$hotelid."' ";

            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $readaccess = $dbObjt->getSingleDatasByObjects($sqlQuery, 'YES');
			if(empty($readaccess)){
				throw new AdmnUsrsException('this operation has been cancelled due to access restrictions', 201);
			}

			$rdAccess = isset($readaccess->ReadWriteAccess)?$readaccess->ReadWriteAccess:'';
			if(empty($rdAccess)){
				throw new AdmnUsrsException('this operation has been cancelled due to access restrictions', 201);
			}

			return  $rdAccess;
            
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
	}

	
	protected function validatePassword(string $password): string
    {
		//echo $password;die;
     
		if(!preg_match("/^(?=.*\W+)(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[a-zA-Z0-9!@$%^&?*#]{6,15}$/",$password)){
			throw new AdmnUsrsException('Please enter a valid password', 200);
			exit();
		}

        return $password;
    }
	protected function validateEmail(string $emailValue): string
    {
        $email = filter_var($emailValue, FILTER_SANITIZE_EMAIL);
        if (!v::email()->validate($email)) {
            throw new AdmnUsrsException('Please enter a valid email', 200);
        }

        return $email;
    }

	protected function generaterandom($type,$length){ 
		if($type == '1') {
			 $characters = "abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
		}
		elseif($type == '2') {
			 $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
		}
		elseif($type == '3') {
			 $characters = "1234567890"; 
		} 	   
		 
		$token = ''; 
		 
		for ($i = 0; $i < $length; $i++) { 
			$index = rand(0, strlen($characters) - 1); 
			$token .= $characters[$index]; 
		}  
		return $token;    
	}
	
	protected function encrypt_decrypt( $string, $action = 'e' ) {
		// you may change these values to your own
		$secret_key = 'tcs@2018@isthehiddenkey#$%';
		$secret_iv = '23032018';

		$output = false;
		$encrypt_method = "AES-256-CBC";
		$key = hash( 'sha256', $secret_key );
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

		if( $action == 'e' ) {
			$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
		}
		else if( $action == 'd' ){
			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		} 
		return $output;
	}
	
	
	protected function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	protected function getuserAgent() {
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
		$ub ="";
		// First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
		  $platform = 'linux';
		} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		  $platform = 'mac';
		} elseif (preg_match('/windows|win32/i', $u_agent)) {
		  $platform = 'windows';
		}
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
		  $bname = 'Internet Explorer';
		  $ub = "MSIE";
		} elseif(preg_match('/Firefox/i',$u_agent)) {
		  $bname = 'Mozilla Firefox';
		  $ub = "Firefox";
		} elseif(preg_match('/Chrome/i',$u_agent)) {
		  $bname = 'Google Chrome';
		  $ub = "Chrome";
		} elseif(preg_match('/Safari/i',$u_agent)) {
		  $bname = 'Apple Safari';
		  $ub = "Safari";
		} elseif(preg_match('/Opera/i',$u_agent)) {
		  $bname = 'Opera';
		  $ub = "Opera";
		} elseif(preg_match('/Netscape/i',$u_agent)) {
		  $bname = 'Netscape';
		  $ub = "Netscape";
		}
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
		  // we have no matching number just continue
		}
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
		  //we will have two since we are not using 'other' argument yet
		  //see if version is before or after the name
		  if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
			$version= $matches['version'][0];
		  } else {
			$version= $matches['version'][1];
		  }
		} else {
		  $version= $matches['version'][0];
		}
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
	  return array(
		'userAgent' => $u_agent,
		'name'      => $bname,
		'version'   => $version,
		'platform'  => $platform,
		'pattern'    => $pattern
		);
	}

	//=== Get User Info ===//
	function getuserdetails() { 
		//$userDetails = json_decode(file_get_contents("http://ipinfo.io/{$ipAddress}/json"));  
		$auditUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$userdetails = array();
		$fullDetails = array();
		$userDetails['userAgent'] = $this->getuserAgent(); 
		$userDetails['auditUrl'] = $auditUrl; 
		$fullDetails = array();
		//$ipAddress   = file_get_contents("http://www.geoplugin.com/ip.php"); // Get IP Only
		//$fullDetails   = file_get_contents("http://www.geoplugin.net/json.gp"); // Get Full details
		
		//if($http_response_header[0] == 'HTTP/1.1 404 Not Found') {
			//$fullDetails = array();
	//	}
	//	else { 
	//		$fullDetails  = json_decode($fullDetails , true); 
	//	} 
		 
		
		$infoDetails = array(); 
		if(sizeof($fullDetails) > 0 ) {
			$userDetails['ipAddress']         = $fullDetails['geoplugin_request']; 
			$infoDetails['ipAddress']         = $fullDetails['geoplugin_request'];
			$infoDetails['city']              = $fullDetails['geoplugin_city'];
			$infoDetails['state']             = $fullDetails['geoplugin_region'];
			$infoDetails['stateCode']         = $fullDetails['geoplugin_regionCode'];
			$infoDetails['country']           = $fullDetails['geoplugin_countryName'];
			$infoDetails['countryCode']       = $fullDetails['geoplugin_countryCode'];
			//$infoDetails['latitude']          = $fullDetails['geoplugin_latitude'];
			//$infoDetails['longitude']         = $fullDetails['geoplugin_longitude'];
			//$infoDetails['radius']            = $fullDetails['geoplugin_locationAccuracyRadius'];
			$infoDetails['timeZone']          = $fullDetails['geoplugin_timezone'];
			$infoDetails['currencyCode']      = $fullDetails['geoplugin_currencyCode'];
			$infoDetails['currencySymbol']    = $fullDetails['geoplugin_currencySymbol'];
			$infoDetails['currencyConverter'] = $fullDetails['geoplugin_currencyConverter'];  
			//$userDetails['info']     			= file_get_contents("http://www.geoplugin.net/php.gp?ip=$ipAddress"); 
			$userDetails['info']               = json_encode($infoDetails,true); 
		}
		else { 
			$ipAddress   = $this->get_client_ip(); 
			$userDetails['ipAddress'] = $ipAddress; 
			$userDetails['info'] = json_encode($infoDetails); 
		} 
		return $userDetails;  
	}
}
?>