<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\AdmnGrps\AdmnGrpsException;
use App\Model\DB;
use App\Model\AdmnMenuModel;

class AdmnGrpModel extends BaseModel
{

    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function delete($groupid,$auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpAction_'.$auditBy, 'AdmnGrpModel');
        try{

            $action = 'DELETE';
            $sqlQuery = "CALL SP_GroupConfig('', '', ".$groupid.", '', 
            ".$auditBy.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('delete Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){

                return 'SUCCESS';
            }
            else if($insResult->ErrorCode == '01'){
                throw new AdmnGrpsException($insResult->Result, 201);
            }
            else {
                throw new AdmnGrpsException('Cannot connect to the server. Please try again later.', 201);
            }

        }
        catch(AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }

    public function editGroupAssginMenu($input, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpModel');
        $objLogger->info("======= Start AdmnGrps Model (editGroupAssginMenu) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{

            $data = json_decode(json_encode($input), false);
            $groupName = isset($data->groupName)?trim($data->groupName):'';
            $groupId = isset($data->groupId)?trim($data->groupId):'';
            $hotelId = isset($data->hotelId)?trim($data->hotelId):'';
            $readMenus = isset($data->readMenus)?$data->readMenus:'';
            $writeMenus = isset($data->writeMenus)?$data->writeMenus:'';
            $brandId = 0;
                
            $insResult = $this->updateGroup($groupId, $groupName, $auditBy, $hotelId, $brandId);
            $objLogger->info('update Return : '.json_encode($insResult));
            $errorCode = isset($insResult->ErrorCode)?trim($insResult->ErrorCode):'';
            if($errorCode == '00'){
                $admnMenuModel = new AdmnMenuModel($this->loggerFactory, $this->dBConFactory);    
                $insStatus = $admnMenuModel->assginMenuWithGroup($groupId, $hotelId, $readMenus, $writeMenus, $auditBy);
                $objLogger->info("update Status : ".json_encode($insStatus));
                $admnMnuErrorCode = isset($insStatus->ErrorCode)?trim($insStatus->ErrorCode):'';  
                if($admnMnuErrorCode == '00'){
                    $objLogger->info("======= End AdmnGrps Model (editGroupAssginMenu) ================");
                    return 'SUCCESS';
                }
                else {
                    throw new AdmnGrpsException($insStatus->Result, 201);
                }      
            }
            else {
                throw new AdmnGrpsException($insResult->Result, 201);
            }

        }
        catch(AdmnGrpsException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }

        }
    }

    public function updateGroup($groupid, $groupName, $userid, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpModel');
        try{
            
            $action = 'UPDATE';
            $sqlQuery = "CALL SP_GroupConfig('".$hotelid."', '".$brandid."', ".$groupid.", '".$groupName."', 
            ".$userid.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('update Return : '.json_encode($insResult));
            return  $insResult;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }

    public function createGroupAssginMenu($input, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpModel');
        $objLogger->info("======= Start AdmnGrps Model (createGroupAssginMenu) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $data = json_decode(json_encode($input), false);
            $groupName = isset($data->groupName)?$data->groupName : '';
            $hotelId = isset($data->hotelId)?trim($data->hotelId):'';
            $readMenus = isset($data->readMenus)?$data->readMenus:'';
            $writeMenus = isset($data->writeMenus)?$data->writeMenus:'';

            $brandid = 0;
            $insResult = $this->createGroup($groupName, $auditBy, $hotelId, $brandid);
            $objLogger->info('Insert Return : '.json_encode($insResult));
            $errorCode = isset($insResult->ErrorCode)?trim($insResult->ErrorCode):'';
            //$groupId = 0;
            if($errorCode == '00'){
                $groupId = $insResult->groupId;

                $admnMenuModel = new AdmnMenuModel($this->loggerFactory, $this->dBConFactory);
                $insStatus = $admnMenuModel->assginMenuWithGroup($groupId, $hotelId, $readMenus, $writeMenus, $auditBy);
                $objLogger->info("Insert Status : ".json_encode($insStatus));
                $admnMnuErrorCode = isset($insStatus->ErrorCode)?trim($insStatus->ErrorCode):'';
                if($admnMnuErrorCode == '00'){
                    $objLogger->info("======= End AdmnGrps Model (createGroupAssginMenu) ================");
                    $retResult['message'] = 'SUCCESS'; 
                    $retResult['groupId'] = $groupId; 
                    return $retResult;
                }               
                else {
                    $sqlQuery = "DELETE FROM admingroups WHERE GroupID = ".$groupId;
                    $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                    $delResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
                    $objLogger->info("Insert Status : ".$delResult);
                    if($delResult->ErrorCode == '00'){
                        
                        $retResult['message'] = 'SUCCESS'; 
                        $retResult['groupId'] = $groupId; 
                        return $retResult;
                    }
                    else {
                        throw new AdmnGrpsException($delResult->Result, 201);
                    }
                }
               
            }
            else if($errorCode == '02'){
                $groupId = $insResult->groupId;

                $admnMenuModel = new AdmnMenuModel($this->loggerFactory, $this->dBConFactory);
                $insStatus = $admnMenuModel->assginMenuWithGroup($groupId, $hotelId, $readMenus, $writeMenus, $auditBy);
                $objLogger->info("Insert Status : ".json_encode($insStatus));
                $admnMnuErrorCode = isset($insStatus->ErrorCode)?trim($insStatus->ErrorCode):'';
                if($admnMnuErrorCode == '00'){
                    $objLogger->info("======= End AdmnGrps Model (createGroupAssginMenu) ================");
                    //return 'SUCCESS';
                    $retResult['message'] = 'SUCCESS'; 
                    $retResult['groupId'] = $groupId; 
                    return $retResult;
                }               
                else {
                    $sqlQuery = "DELETE FROM admingroups WHERE GroupID = ".$groupId;
                    $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                    $delResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
                    $objLogger->info("Insert Status : ".$delResult);
                    if($delResult->ErrorCode == '00'){
                        
                        //return 'SUCCESS';
                        $retResult['message'] = 'SUCCESS'; 
                        $retResult['groupId'] = $groupId; 
                        return $retResult;
                    }
                    else {
                        throw new AdmnGrpsException($delResult->Result, 201);
                    }
                }
            }   
            else {
                throw new AdmnGrpsException($insResult->Result, 201);
            }

        }
        catch(AdmnGrpsException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }

        }
    }

    public function createGroup($groupName, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpModel');
        try{
            
            $action = 'ADD';
            $sqlQuery = "CALL SP_GroupConfig('".$hotelid."', '".$brandid."', 0, '".$groupName."', 
            ".$auditBy.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Insert Return : '.json_encode($insResult));
            return  $insResult;
           
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
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
                throw new AdmnGrpsException($insResult->Result, 201);
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
                throw new AdmnGrpsException('Invalid Access', 201);
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
                throw new AdmnGrpsException($insResult->Result, 201);
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
                throw new AdmnGrpsException('Invalid Access', 201);
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
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }

    //public function getGrpList($auditBy, $hotelid, $brandid, $searchValue, $itemperPage, $currentPage, $sortName, $sortOrder, $limitFrom)
    public function getGrpList($auditBy, $hotelid, $searchValue)
    {
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpModel');       
        try{
            
            $action = 'GETALL';
            $sqlQuery = "CALL SP_GroupConfig('".$hotelid."', '', 0, '', 
            ".$auditBy.", '".$searchValue."', 0, '', 
            0, '', '', '".$action."')";
            
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
                throw new AdmnGrpsException('Invalid Access', 201);
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
                throw new AdmnGrpsException('Invalid Access', 201);
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
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }
}
