<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Hotel\HotelException;
use App\Model\DB;
class HotelModel extends BaseModel
{
    
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }
    
    public function ViewhotelList($userid,$username,$brandid){
        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$username.'.log')->createInstance('Hotelmodel');
        try 
        {

            $action = "VIEW";
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','',$brandid,0,0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $brandDetails = $dbObjt->getMultiDatasByObjects($sqlQuery);
               
                if(!empty($brandDetails)){
                    return $brandDetails;
                }
                else{
                    if (empty($brandDetails)) {
                        throw new HotelException('Hotel  credentials invalid. ', 200);
                    }
                }

        }catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }

    }

    public function createhotel($brandid,$hotelname,$location,$mail,$spocname,$mobileno,$address,$userid,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('Hotelmodel');
        try 
        {
            $action = "ADD";
            $sqlQuery = "call SP_AddandEditCustomer('$action','$hotelname','$location','$spocname','$mail','$mobileno','$address',$brandid,'0',$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                
               
                if(!empty($user->msg)){

                    return $user;
                }
                else{
                    if (empty($user)) {
                        throw new HotelException('Hotel  credentials invalid. ', 200);
                    }
                }
                
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }

    public function gethoteloneModel($hotelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('Hotelmodel');
        try 
        {
            $action = "GETONE";
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','','0',$hotelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new HotelException('Hotel  credentials invalid. ', 200);
                    }
                }
                
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }


    public function update($hotelid,$brandid,$hotelname,$location,$mail,$spocname,$mobileno,$address,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('Hotelmodel');
        try 
        {
            $action = "UPDATE";
            $sqlQuery = "call SP_AddandEditCustomer('$action','$hotelname','$location','$spocname','$mail','$mobileno','$address',$brandid,$hotelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new HotelException('Brand  credentials invalid. ', 200);
                    }
                }
                
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }

    
    public function delete($hotelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('Hotelmodel');
        try 
        {
            $action = "DELETE";
			
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','',0,$hotelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new HotelException('Hotel  credentials invalid. ', 200);
                    }
                }
                
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }
}
