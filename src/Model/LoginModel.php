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
    
    public function validateLogin($uid, $pwd){
        $objLogger = $this->loggerFactory->addFileHandler('LoginModel.log')->createInstance('LoginModel');
            
        try 
        {
            $action = "login";
            $sqlQuery = "call SP_Doauth('$uid','$pwd')";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
			
            $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
			
            // if($user->msg=='invalidUser'){
			//     throw new LoginException('User ID Invalid', 200);
			// }
			// elseif($user->msg=='invalidPassword'){
			// 	throw new LoginException('Password Invalid', 200);
			// }
            if(!empty($user->msg)){
			    throw new LoginException($user->msg, 401);
			}
			else{
				$objLogger->info('User Data : '.json_encode($user));
				//print_R($user);die;
				if (empty($user)) {
					throw new LoginException('Login credentials invalid. ', 401);
				}
				else{
					// if($user->designation!='Super Admin'){
					// 	if($user->hotelDelete=='1'){
					// 	throw new LoginException("You don't have permission to access this feature. Please contact your admin", 200);
					// 	}
					// }
				} 
				// $user->created_date = $this->dateFormatchange($user->created_date);
				// $user->edited_date  = $this->dateFormatchange($user->edited_date);
				// $user->deleted_date = $this->dateFormatchange($user->deleted_date);
                // $user->access_rights = json_encode(json_decode($user->access_rights, false));
			}

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
                throw new LoginException('Database Error', 401);
            }
        }
    }

    public function getLogUser($userId, $ipAddress, $userAgent, $physicalAddress, $userInfo){

        $objLogger = $this->loggerFactory->addFileHandler('LoginModel.log')->createInstance('LoginModel');
        try 
        {
            $action = "logUser";
            $sqlQuery = "call login('$action','null','null','$userId','null','0','$ipAddress','$userAgent','$physicalAddress','$userInfo')";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
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
                throw new LoginException('Database Error', 401);
            }
        }
    }
}
