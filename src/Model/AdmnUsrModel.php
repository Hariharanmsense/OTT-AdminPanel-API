<?php
namespace App\Model;

use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\AdmnUsrs\AdmnUsrsException;
use App\Model\DB;

class AdmnUsrModel extends BaseModel
{
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function profileUpdate($userId, $userName, $mobile, $email, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrModel');
        try{
            
            $action = 'PROFILEUPDATE';
            $sqlQuery = "CALL SP_AdmnUsrConfig('', '', ".$userId.", '".$userName."', 
            '', '".$email."', 0,0,'','".$mobile."', '', '', ".$auditBy.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Insert Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                
                return 'SUCCESS';
            }
            else {
                throw new AdmnUsrsException($insResult->Result, 201);
            }
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function getLastLogin($userId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsModel');
        try{

            $action = 'LASTLOGIN';
            $sqlQuery = "CALL SP_AdmnUsrConfig('', '', ".$userId.", '', '', '', 0, 0, '','','','',
            ".$auditBy.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $users = $dbObjt->getMultiDatasByObjects($sqlQuery);

            if(count((array)$users) <= 0){
                throw new AdmnUsrsException('Invalid User Id', 201);
            }
            return $users;

        }
        catch(AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }

    }

    public function checkOldPassword($userId, $encrptpassword, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsModel');
        try{
            $action = "ALREADYEXISTPWD";
            $sqlQuery = "CALL SP_AdmnUsrConfig('', '', ".$userId.", '', '".$encrptpassword."', '', 0, 0, '','','','',
            ".$auditBy.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $userDetId = isset($user->id)?$user->id:'';

            if(!empty($userDetId))
                return 'Old password same.';
            else 
                throw new UserException('Old password is mismatch', 200);

        }
        catch(AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new UserException('Old password is mismatch', 200);
            }
            else {
                throw new UserException('Cannot connect to the server. Please try again later.', 400);
            }
        }

    }

    public function resetPasswordGet($resetCode){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsModel');
        try{

            $action = 'RESETCODEVALID';
            $sqlQuery = "CALL SP_AdmnUsrConfig('', '', 0, '', '', '', 0, 0, '','','','".$resetCode."',
            0, '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $users = $dbObjt->getSingleDatasByObjects($sqlQuery, 'YES');
			if(empty($users)){
				throw new AdmnUsrsException('Invalid Access', 201);
			}
            return $users;
        }
        catch(AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }


    public function delete($userid,$auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsModel');
        try{

            $action = 'DELETE';
            $sqlQuery = "CALL SP_AdmnUsrConfig('', '', ".$userid.", '', '', '', 0, 0, '','','','',
            ".$auditBy.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('delete Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){

                return 'SUCCESS';
            }
            else if($insResult->ErrorCode == '01'){
                throw new AdmnUsrsException($insResult->Result, 201);
            }
            else {
                throw new AdmnUsrsException('Cannot connect to the server. Please try again later.', 201);
            }

        }
        catch(AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function activeOrDeactiveUser($userId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrModel');
        try{

            $action = 'ACTIVEORDEACTIVE';
            $sqlQuery = "CALL SP_AdmnUsrConfig('', '', ".$userId.", '', '', '', 0, 0, '','','','',
            ".$auditBy.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Update Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                
                return $insResult->Result;
            }
            else {
                throw new AdmnUsrsException($insResult->Result, 201);
            }

        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function getUsersByEmail($email){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$email, 'AdmnUsrModel');
        try{

            $action = 'RESETUSEDETAIL';
            $sqlQuery = "CALL SP_AdmnUsrConfig('', '', 0, '', '', '".$email."', 0, 0, '','','','',
            0, '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $users = $dbObjt->getSingleDatasByObjects($sqlQuery, "YES");
            if(count((array)$users) <= 0){
                throw new AdmnUsrsException('Please Enter Your Valid Email Address', 201);
            }
            return $users;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
            
    }

    public function resetPasswordUpdate($userId,$password,$auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$userId, 'AdmnUsrModel');
        try{
            $action = 'RESETPASSWORDUPDATE';
            $sqlQuery = "CALL SP_AdmnUsrConfig('', '', ".$userId.", '', '".$password."', '', 0, 0, '','','','',
            ".$auditBy.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Update Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                
                return 'SUCCESS';
            }
            else {
                throw new AdmnUsrsException($insResult->Result, 201);
            }

        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function forgotPassword($email, $resetCode){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$email, 'AdmnUsrModel');
        try{

            $action = 'RESETPASSWORD';
            $sqlQuery = "CALL SP_AdmnUsrConfig('', '', 0, '', '', '".$email."', 0, 0, '','','','".$resetCode."',
            0, '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $result = $dbObjt->getSingleDatasByObjects($sqlQuery);
            return $result;

        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function update($userid, $userName, $mobileNo, $designation, $email, $groupId, $adminStatus, $timeZone, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrModel');
        try{
            
            $action = 'UPDATE';
            $sqlQuery = "CALL SP_AdmnUsrConfig('".$hotelid."', '".$brandid."', ".$userid.", '".$userName."', 
            '', '".$email."', ".$groupId.",".$adminStatus.",'".$timeZone."','".$mobileNo."', '".$designation."', '', ".$auditBy.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Insert Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                
                return 'SUCCESS';
            }
            else {
                throw new AdmnUsrsException($insResult->Result, 201);
            }
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function create($userName, $encrptpassword, $mobileNo, $designation, $email, $groupId, $timeZone, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrModel');
        try{
            
            $action = 'ADD';
            $sqlQuery = "CALL SP_AdmnUsrConfig('".$hotelid."', '".$brandid."', 0, '".$userName."', 
            '".$encrptpassword."', '".$email."', ".$groupId.",1,'".$timeZone."', '".$mobileNo."', '".$designation."', '', ".$auditBy.", '', 0, '', 0, '', 
            '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Insert Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){

                return 'SUCCESS';
            }
            else {
                throw new AdmnUsrsException($insResult->Result, 201);
            }
            
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function getUsrOne($userid, $auditBy, $hotelid, $brandid)
    {
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrModel');       
        try{
            
            $action = 'GETONE';
            $sqlQuery = "CALL SP_AdmnUsrConfig('".$hotelid."', '".$brandid."', ".$userid.", '', '', '', 0, 0, '','','', '', 
            ".$auditBy.", '', 0, '', 0, '', '', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $usrData = $dbObjt->getSingleDatasByObjects($sqlQuery);
            return $usrData;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function getUsrsList($auditBy, $hotelid, $brandid, $searchValue, $itemperPage, $currentPage, $sortName, $sortOrder, $limitFrom)
    {
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrModel');       
        try{
            
            $action = 'GETALL';
            $sqlQuery = "CALL SP_AdmnUsrConfig('".$hotelid."', '".$brandid."', 0, '', '', '', 0, 0, '','','', '', 
            ".$auditBy.", '".$searchValue."', ".$itemperPage.", '".$currentPage."', 
            ".$limitFrom.", '".$sortName."', '".$sortOrder."', '".$action."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $users = $dbObjt->getMultiDatasByObjects($sqlQuery);

            if(count($users)>=1)
                $objLogger->info('Admin User List Avaliable User list count : '.count($users));

            return $users;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function getMenuRightStatus($auditBy, $menuid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrModel');
        try{

            $sqlQuery = "SELECT grp.ReadWriteAccess FROM adminmenugroup AS grp 
                         INNER JOIN adminusers as usr ON usr.userGroup = grp.groupID
                         INNER JOIN adminmenus as mnu ON mnu.MenuID = grp.MenuID
                         WHERE usr.id = '$auditBy' and mnu.MenuID = '".$menuid."' ";

            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $user = $dbObjt->getSingleDatasByObjects($sqlQuery);

            if(empty($user->ReadWriteAccess)){
                throw new AdmnUsrsException('Invalid Access', 201);
            }

            return $user->ReadWriteAccess;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }
}
