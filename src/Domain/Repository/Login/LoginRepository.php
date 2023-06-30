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

class LoginRepository extends BaseRepository implements LoginService
{
    
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtTokenObjt;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory, JwtToken $jwtTokenObjt)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
        $this->jwtTokenObjt = $jwtTokenObjt;
    }

    public function doAuth($inputdata) 
    {
        $objLogger = $this->loggerFactory->addFileHandler('LoginModel.log')->createInstance('LoginRepository');
        try{
            $loginData = new \stdClass();

            
            $userid = isset($inputdata['email'])?$inputdata['email']:"";
            $password = isset($inputdata['password'])?$inputdata['password']:"";
            if(empty($userid)){
                throw new LoginException('please Enter Email ', 201);
            }
            if(empty($password)){
                throw new LoginException('Password required', 201);
            }
           
            $password = $this->encrypt_decrypt($password, 'e');
            $loginModel = new LoginModel($this->loggerFactory, $this->dBConFactory);
            $user = $loginModel->validateLogin($userid, $password);
            //print_r($user);die();
            //$logDetails = $this->logUser($user->userId,$user->password);
            // $user->lastuserid = $logDetails->lastuserid;

            

            $token = array(
                'sub'   		=> $user->loginid,
                'id'   			=> $user->loginid,
                'userName'		=>$user->username,
                'userGroup'		=>$user->userGroup,
				'email'			=>$user->email,
				'hotelId'		=>$user->hotelId,
				'brandId'		=>$user->brandId,
                'iat'   		=> time(),
                'exp'   		=> time() + (1 * 24 * 60 * 60),
            );

           
            $loginData->jwtToken = $this->jwtTokenObjt->getToken($token);
            $loginData->userData = $user;
            return $loginData;
        }
        catch (LoginException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new LoginException($ex->getMessage(), 401);
            }
            else {
                throw new LoginException('Login credentials invalid', 401);
            }
        }
    }


    public function logUser($userId)
    {
		$objLogger = $this->loggerFactory->addFileHandler('LoginModel.log')->createInstance('LoginRepository');
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
            $user = $loginModel->getLogUser($userId, $ipAddress, $userAgent, $physicalAddress, $userInfo);
			return $user;
		}
		catch (LoginException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new LoginException($ex->getMessage(), 401);
            }
            else {
                throw new LoginException('Login credentials invalid', 401);
            }
        }			
    }

}
