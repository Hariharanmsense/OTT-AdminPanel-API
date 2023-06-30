<?php

namespace App\Model;

use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Common\CommonException;
use App\Model\DB;

class CommonModel extends BaseModel
{
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    

    public function getAllReadWriteMenus($accessStatus, $groupId, $hotelId, $auditBy)
    {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonModel');       
        try{
            
            //$action = 'ASSIGNMENUALL';
            $sqlQuery = "CALL SP_AssignMenuConfig(".$groupId.", ".$hotelId.", ".$auditBy.",'".$accessStatus."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $menus = $dbObjt->getMultiDatasByObjects($sqlQuery);

            if(count($menus)>=1)
                $objLogger->info('get All Assign Menu (count) : '.count($menus));

            return $menus;
        }
        catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 401);
            }
        }
    }

    public function getAllListData($auditBy, $action, $brandid, $hotelid)
    {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonModel');       
        try{
            
            //$action = 'ASSIGNMENUALL';
            $sqlQuery = "CALL SP_OttLookUp(0, ".$auditBy.", '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $hotels = $dbObjt->getMultiDatasByObjects($sqlQuery);

            if(count($hotels)>=1)
                $objLogger->info('get All Assign Menu (count) : '.count($hotels));

            return $hotels;
        }
        catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 401);
            }
        }
    }
	
	public function getAllListDataZeroRecords($lookupid, $auditBy, $action, $brandid, $hotelid)
    {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonModel');       
        try{
            
            //$action = 'ASSIGNMENUALL';
            $sqlQuery = "CALL SP_OttLookUp(".$lookupid.", ".$auditBy.", '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $datalist = $dbObjt->getMultiDatasByArray($sqlQuery);
            
            if(count($datalist)>=1)
                $objLogger->info('getAllListDataZeroRecords (count) : '.count($datalist));

            return $datalist;
        }
        catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 401);
            }
        }
    }
}
