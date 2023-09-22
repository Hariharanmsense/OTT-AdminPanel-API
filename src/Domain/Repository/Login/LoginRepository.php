<?php

declare(strict_types=1);

namespace App\Domain\Repository\Login;

use App\Domain\Service\Login\LoginService;
use App\Exception\Login\LoginException;
use App\Model\LoginModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use App\Application\Auth\Crypto;

class LoginRepository extends BaseRepository implements LoginService
{
    
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtTokenObjt;
    protected Crypto $crypto;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory, JwtToken $jwtTokenObjt, Crypto $crypto)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
        $this->jwtTokenObjt = $jwtTokenObjt;
        $this->crypto = $crypto;
    }

    public function logOut($input, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('LoginAction_'.$auditBy, 'LoginRepository');
        $objLogger->info("======= Start Internet Repository (update) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $data = json_decode(json_encode($input), false);
            $userId=isset($data->userId)?$data->userId:'';
            if ($userId=='') {
                throw new LoginException('Please enter the User ID', 201);                
            }
            $loginModel = new LoginModel($this->loggerFactory, $this->dBConFactory);
            $logstatus = $loginModel->logOut($userId, $auditBy);
            $objLogger->info("logout status : ".$logstatus);
            return $logstatus;

        }
        catch (LoginException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new LoginException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new LoginException('Login User Id Invalid', 201);
            }
        }
    }

    public function doAuth($inputdata) 
    {
        $objLogger = $this->loggerFactory->getFileObject('LoginAction', 'LoginRepository');
        try{
            $loginData = new \stdClass();
            $email = isset($inputdata['email'])?$inputdata['email']:"";
            $password = isset($inputdata['password'])?$inputdata['password']:"";
            if(empty($email)){
                throw new LoginException('Invalid Email', 201);
            }
            if(empty($password)){
                throw new LoginException('Invalid Password', 201);
            }

            $password = $this->crypto->encrypt_decrypt($password, 'e');
            //$password = $this->encrypt_decrypt($password, 'e');
            $loginModel = new LoginModel($this->loggerFactory, $this->dBConFactory);
            $user = $loginModel->validateLogin($email, $password);
            $lastLoginId = $this->logUser($user->loginid);
            //$user->lastLoginId = $logDetails->lastLoginId;
            $token = array(
                'sub'   		=> $user->loginid,
                'id'   			=> $user->loginid,
                'email' 		=> $user->email,
                'designation'	=> $user->designation,
                'mobileno'	=> $user->mobileno,
                'userName'		=>$user->username,
                //'access_rights' => $user->access_rights,
                //'brand_id' 		=> $user->brand_id, 
                //'hotel_id' 		=> $user->hotel_id, 
                //'user_access_id' => $user->user_access_id, 
                //'firstName'		=>$user->firstname,
                //'lastName'		=>$user->lastname,
                //'userName'		=>$user->username, 
                //'role'		    =>$user->role, 
                //'role_id'		=>$user->role_id, 
                'lastInsertId'	=> $lastLoginId, 
                'iat'   		=> time(),
                //'exp'   		=> time() + (2 * 60),
                'exp'   		=> time() + (1 * 24 * 60 * 60),
            );
           
            $loginData->jwtToken = $this->jwtTokenObjt->getToken($token);
            $loginData->userData = $user;
            return $loginData;
        }
        catch (LoginException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new LoginException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new LoginException('Login credentials invalid', 201);
            }
        }
    }


    public function logUser($userId)
    {
		$objLogger = $this->loggerFactory->addFileHandler('LoginAction.log')->createInstance('LoginRepository');
        try{
			$objLogger->info("User Id : ".$userId);
			$action    ="logUser";
			$sysipAddress = $this->get_client_ip();
			$userAgent = $this->getuserAgent();
			$userAgent = json_encode($userAgent);
			$userInfo  = ''; 
			$auditUrl  = ''; 
			$userName  = '';
			$getuserdetails = $this->getuserdetails();
				if(sizeof($getuserdetails) > 0) {
					$ipAddress = $getuserdetails['ipAddress'];				
					$userAgent = $getuserdetails['userAgent'];	
					$userAgent = json_encode($userAgent);
					$userInfo  = $getuserdetails['info'];	
					$auditUrl  = $getuserdetails['auditUrl'];	
				} 
			$physicalAddress = '';  
			$loginModel = new LoginModel($this->loggerFactory, $this->dBConFactory);
            $lastLoginId = $loginModel->getLogUser($userId, $ipAddress, $userAgent, $physicalAddress, $userInfo);
			return $lastLoginId;
		}
		catch (LoginException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new LoginException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new LoginException('Login credentials invalid', 201);
            }
        }			
    }

}
