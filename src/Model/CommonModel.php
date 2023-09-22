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

    

    public function getSingleMenuRecrds($menuid, $hotelid, $auditBy, $action){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonModel');       
        try{
            
            //$action = 'ASSIGNMENUALL';
            $sqlQuery = "SELECT aug.ReadWriteAccess FROM adminusers AS au 
            INNER JOIN admingroups AS ag ON ag.GroupID = au.userGroup 
            INNER JOIN adminmenugroup AS aug ON aug.GroupID = ag.GroupID 
            WHERE au.id = '".$auditBy."' AND aug.MenuID = '".$menuid."' 
            AND aug.hotelID = '".$hotelid."' ";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $datalist = $dbObjt->getSingleDatasByObjects($sqlQuery);
            
            if(count((array)$datalist)>=1)
                $objLogger->info('getSingleMenuRecrds (count) : '.count((array)$datalist));

            if(count((array)$datalist)<= 0)
                throw new CommonException('No Records Found', 201);

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
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAssignOrAvailMenus($hotelId, $groupId, $auditBy, $action){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonModel'); 
        try{
            
            $sqlQuery = "CALL SP_AssignMenuConfig(".$groupId.", ".$hotelId.", ".$auditBy.",'".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $menus = $dbObjt->getMultiDatasByObjects($sqlQuery);

            if(count($menus)>=1)
                $objLogger->info('get All Assign or Avail Menus (count) : '.count($menus));

            if(count($menus)<= 0)
                throw new CommonException('No Records Found', 201);

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
                throw new CommonException('Invalid Access', 201);
            }
        }
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
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllLstZroRecrds($lookupid, $auditBy, $action)
    {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonModel');       
        try{
            
            //$action = 'ASSIGNMENUALL';
            $sqlQuery = "CALL SP_OttLookUp(".$lookupid.", ".$auditBy.", '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $datalist = $dbObjt->getMultiDatasByArray($sqlQuery);
            
            if(count($datalist)>=1)
                $objLogger->info('getAllLstZroRecrds (count) : '.count($datalist));

            if(count($datalist)<= 0)
                throw new CommonException('No Records Found', 201);

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
                throw new CommonException('Invalid Access', 201);
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
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllListData($lookupid, $auditBy, $action, $brandid, $hotelid)
    {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonModel');       
        try{
            
            //$action = 'ASSIGNMENUALL';
            $sqlQuery = "CALL SP_OttLookUp(".$lookupid.", ".$auditBy.", '".$action."')";
            
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
                throw new CommonException('Invalid Access', 201);
            }
        }
    }
}
