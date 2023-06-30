<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\AdmnGrps\AdmnGrpsException;
use App\Model\DB;

class AdmnGrpModel extends BaseModel
{

    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function update($groupid, $groupName, $userid, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpModel');
        try{
            
            $action = 'UPDATE';
            $sqlQuery = "CALL SP_GroupConfig('".$hotelid."', '".$brandid."', ".$groupid.", '".$groupName."', 
            ".$userid.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Insert Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                
                return 'SUCCESS';
            }
            else {
                throw new AdmnGrpsException($insResult->Result, 401);
            }
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 401);
            }
        }
    }

    public function create($groupName, $userid, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpModel');
        try{
            
            $action = 'ADD';
            $sqlQuery = "CALL SP_GroupConfig('".$hotelid."', '".$brandid."', 0, '".$groupName."', 
            ".$userid.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Insert Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                
                return 'SUCCESS';
            }
            else {
                throw new AdmnGrpsException($insResult->Result, 401);
            }
           
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 401);
            }
        }
    }

    public function getGrpOne($groupid, $userid, $hotelid, $brandid)
    {
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpModel');       
        try{
            
            $action = 'GETONE';
            $sqlQuery = "CALL SP_GroupConfig('".$hotelid."', '".$brandid."', ".$groupid.", '', 
            ".$userid.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $grpData = $dbObjt->getSingleDatasByObjects($sqlQuery);
            return $grpData;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 401);
            }
        }
    }

    public function getGrpList($userid, $hotelid, $brandid, $searchValue, $itemperPage, $currentPage, $sortName, $sortOrder, $limitFrom)
    {
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpModel');       
        try{
            
            $action = 'GETALL';
            $sqlQuery = "CALL SP_GroupConfig('".$hotelid."', '".$brandid."', 0, '', 
            ".$userid.", '".$searchValue."', ".$itemperPage.", '".$currentPage."', 
            ".$limitFrom.", '".$sortName."', '".$sortOrder."', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $groups = $dbObjt->getMultiDatasByObjects($sqlQuery);

            if(count($groups)>=1)
                $objLogger->info('Group List Avaliable Group list count : '.count($groups));

            return $groups;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 401);
            }
        }
    }

    public function getMenuRightStatus($userid, $menuid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpModel');
        try{

            $sqlQuery = "SELECT grp.ReadWriteAccess FROM adminmenugroup AS grp 
                         INNER JOIN adminusers as usr ON usr.userGroup = grp.groupID
                         INNER JOIN adminmenus as mnu ON mnu.MenuID = grp.MenuID
                         WHERE usr.id = '$userid' and mnu.MenuID = '".$menuid."' ";

            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $user = $dbObjt->getSingleDatasByObjects($sqlQuery);

            if(empty($user->ReadWriteAccess)){
                throw new AdmnGrpsException('Invalid Access', 401);
            }

            return $user->ReadWriteAccess;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 401);
            }
        }
    }
}
