<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Brand\BrandException;
use App\Model\DB;
class RoomModel extends BaseModel
{
    
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }
    
    public function viewrommlist($userid,$username){
        $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$username.'.log')->createInstance('RoomModel');
        try 
        {
			
            $action = "VIEW";
            $sqlQuery = "call SP_AddandEditRoom('$action',0, 0, 0,0,0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
				
                $brandDetails = $dbObjt->getMultiDatasByObjects($sqlQuery);
				
               
                if(!empty($brandDetails)){
                    return $brandDetails;
                }
                else{
                    if (empty($brandDetails)) {
                        throw new BrandException('Room  credentials invalid. ', 200);
                    }
                }

        }catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), 401);
            }
            else {
                throw new BrandException('Database Error', 401);
            }
        }

    }

    public function addroom($hotelid,$roomno,$roomcategory,$userid,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('RoomModel');
        try 
        {
           

            $action = "ADD";
            $sqlQuery = "call SP_AddandEditRoom('$action',$hotelid,$roomno,$roomcategory,0,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user->msg)){
                    return $user;
                }
                else{
                    if (empty($user)) {
                        throw new BrandException('Room  credentials invalid. ', 200);
                    }
                }
                
        } catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), 401);
            }
            else {
                throw new BrandException('Database Error', 401);
            }
        }
    }

    public function updateroom($hotelid,$roomno,$roomcategory,$roomid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('RoomModel');
        try 
        {
            $action = "UPDATE";
            $sqlQuery = "call SP_AddandEditRoom('$action',$hotelid,$roomno,$roomcategory,$roomid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user->msg)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new BrandException('Room  credentials invalid. ', 200);
                    }
                }
                
        } catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." 1Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), 401);
            }
            else {
                throw new BrandException('Database Error', 401);
            }
        }
    }

    public function EditViewModel($roomid,$userid,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('RoomModel');
        try 
        {
            $action = "GETONE";
            $sqlQuery = "call SP_AddandEditRoom('$action',0,0,0,'$roomid',$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new BrandException('Room  credentials invalid. ', 200);
                    }
                }
                
        } catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), 401);
            }
            else {
                throw new BrandException('Database Error', 401);
            }
        }
    }
    public function DeleteRoomModel($userName,$roomid,$userid){
        $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('RoomModel');
        try 
        {
            $action = "DELETE";
            $sqlQuery = "call SP_AddandEditRoom('$action',0,0,0,$roomid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                    if (empty($user)) {
                        throw new BrandException('Room  credentials invalid. ', 200);
                    }
                }
                
        } catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), 401);
            }
            else {
                throw new BrandException('Database Error', 401);
            }
        }
    }
}
