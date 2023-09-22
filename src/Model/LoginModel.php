<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Login\LoginException;
use App\Model\DB;
class LoginModel extends BaseModel
{
    
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function logOut($userId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('LoginAction_'.$auditBy, 'LoginModel');  
        try 
        {
            $action = 'SIGNOUT';
            $sqlQuery = "call SP_LogSignInDetails(".$userId.",'', '', '', '', '".$action."')";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $userStatus = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('User Data : '.json_encode($userStatus));
            if($userStatus->ErrorCode == '00'){
                return "SUCCESS";
            }
            else {
                return "FAILUARE";
            }

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
    
    public function validateLogin($email, $pwd){
        $objLogger = $this->loggerFactory->getFileObject('LoginAction', 'LoginModel');    
        try 
        {
            $sqlQuery = "call SP_Doauth('".$email."','".$pwd."')";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('User Data : '.json_encode($user));
            //print_r($user);die();     
            if(property_exists($user, 'msg') && !empty($user->msg)){
                
			    throw new LoginException('Login credentials invalid. ', 201);
			}
            return $user;
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

    public function getLogUser($userId, $ipAddress, $userAgent, $physicalAddress, $userInfo){

        $objLogger = $this->loggerFactory->addFileHandler('LoginModel.log')->createInstance('LoginModel');
        try 
        {
            $action = "SIGNIN";
            $sqlQuery = "call SP_LogSignInDetails(".$userId.",'".$ipAddress."','".$userAgent."','".$physicalAddress."','".$userInfo."', '".$action."')";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('user : '.json_encode($user));
            $lastLoginId = 0;
            if($user->ErrorCode = '00'){
                $lastLoginId = $user->lastLoginId;
            }
            return $lastLoginId;
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
}
