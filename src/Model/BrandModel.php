<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Brand\BrandException;
use App\Model\DB;
class BrandModel extends BaseModel
{
    
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }
    
    public function ViewbrandList($userid,$username){
        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$username.'.log')->createInstance('BrandModel');
        try 
        {
			
            $action = "VIEW";
            $sqlQuery = "call SP_AddandEditBrand('$action','','',0,0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);		
				$brandDetails = $dbObjt->getMultiDatasByObjects($sqlQuery);	
				
               
                if(!empty($brandDetails)){
                    return $brandDetails;
                }
                else{
                    if (empty($brandDetails)) {
                        throw new BrandException('Brand  credentials invalid. ', 200);
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

    public function validatebrand($brndnme,$shortnme,$userid,$username){
        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$username.'.log')->createInstance('BrandModel');
        try 
        {
            $action = "ADD";
            $sqlQuery = "call SP_AddandEditBrand('$action','$brndnme','$shortnme','$userid','')";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user->msg)){
                    return $user;
                }
                else{
                    if (empty($user)) {
                        throw new BrandException('Brand  credentials invalid. ', 200);
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

    public function updateBrandModel($brndnme,$shortnme,$userid,$username,$brndid){

        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$username.'.log')->createInstance('BrandModel');
        try 
        {
            $action = "UPDATE";
            $sqlQuery = "call SP_AddandEditBrand('$action','$brndnme','$shortnme','$userid','$brndid')";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user->msg)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new BrandException('Brand  credentials invalid. ', 200);
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

    public function EditViewModel($username,$brandid){
        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$username.'.log')->createInstance('BrandModel');
        try 
        {
            $action = "EDIT";
            $sqlQuery = "call SP_AddandEditBrand('$action','','','0','$brandid')";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new BrandException('Brand  credentials invalid. ', 200);
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
    public function DeleteBrandModel($userName,$brandid){
        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandModel');
        try 
        {
            $action = "DELETE";
            $sqlQuery = "call SP_AddandEditBrand('$action','','','0','$brandid')";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                    if (empty($user)) {
                        throw new BrandException('Brand  credentials invalid. ', 200);
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


    public function Actordeactivate($brandid,$userName,$userid){
        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandModel');
        try 
        {
            $action = "ACTORDEACT";
            $sqlQuery = "call SP_AddandEditBrand('$action','','',$userid,$brandid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                    if (empty($user)) {
                        throw new BrandException('Brand  credentials invalid. ', 200);
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
