<?php

namespace App\Model;

use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Tvsolution\TvtempException;
use App\Model\DB;

class TvtempModel extends BaseModel
{
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    

    public function getAllListtemplate($auditBy, $action)
    {
        $objLogger = $this->loggerFactory->getFileObject('TvAction_'.$auditBy, 'TvtempModel');       
        try{
            
            //$action = 'ASSIGNMENUALL';
            $sqlQuery = "CALL SP_TvtemplateDetails('".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $menus = $dbObjt->getMultiDatasByObjects($sqlQuery);

            if(count($menus)>=1)
                $objLogger->info('get All Template (count) : '.count($menus));

            return $menus;
        }
        catch (TvtempException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException('Invalid Access', 401);
            }
        }
    }
	
	 public function getallchannelfeedsmodel($auditBy, $action)
    {
        $objLogger = $this->loggerFactory->getFileObject('TvAction_'.$auditBy, 'TvtempModel');       
        try{
            
            //$action = 'ASSIGNMENUALL';
            $sqlQuery = "CALL SP_TvtemplateDetails('".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $menus = $dbObjt->getMultiDatasByObjects($sqlQuery);

            if(count($menus)>=1)
                $objLogger->info('get All Template (count) : '.count($menus));

            return $menus;
        }
        catch (TvtempException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException('Invalid Access', 401);
            }
        }
    }
	
	public function getallfeaturesModel($auditBy, $action)
    {
        $objLogger = $this->loggerFactory->getFileObject('TvAction_'.$auditBy, 'TvtempModel');       
        try{
            
            //$action = 'ASSIGNMENUALL';
            $sqlQuery = "CALL SP_TvtemplateDetails('".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $menus = $dbObjt->getMultiDatasByObjects($sqlQuery);

            if(count($menus)>=1)
                $objLogger->info('get All Template (count) : '.count($menus));

            return $menus;
        }
        catch (TvtempException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException('Invalid Access', 401);
            }
        }
    }

    public function getJsonDataModel($auditBy, $templateid,$userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('TvAction_'.$userName, 'TvtempModel');       
        try{
            $sqlQuery = "select jsonfilename from template where id =".$templateid;
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $file = $dbObjt->getSingleDatasByObjects($sqlQuery);

            //print_r($file);die();
            $json_file = file_get_contents('../'.$file->jsonfilename);
            //print_r($json_file);die();
            $json_data = json_decode($json_file,true);

            //print_r($json_data);die();

            //if(count($file)>=1)
                $objLogger->info('Json Data : '.json_encode($json_data));

            return $json_data;
        }
        catch (TvtempException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException('Invalid Access', 401);
            }
        }
    }
	
	

  }
