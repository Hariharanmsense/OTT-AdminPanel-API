<?php

declare(strict_types=1);

namespace App\Domain\Repository\AdmnUsrs;

use App\Domain\Service\AdmnUsrs\AdmnUsrsService;
use App\Exception\AdmnUsrs\AdmnUsrsException;
use App\Model\AdmnUsrModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Factory\MailerFactory; 
use App\Factory\UrlSettingFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\Crypto;

class AdmnUsrsRepository extends BaseRepository implements AdmnUsrsService
{
    
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected MailerFactory $mailer;
	protected Crypto $crypto;
    protected UrlSettingFactory $urlSetting;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory, Crypto $crypto, MailerFactory $mailer, UrlSettingFactory $urlSetting)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
		$this->crypto = $crypto;
        $this->mailer = $mailer;
        $this->urlSetting = $urlSetting;
    }

    public function activeOrDeactiveUser($userid, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository (activeOrDeactiveUser) ================");
        try{

            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnUsrModel->activeOrDeactiveUser($userid, $auditBy);
            $objLogger->info("Update Status : ".$insStatus);
            $objLogger->info("======= End AdmnUsrs Repository (activeOrDeactiveUser) ================");
            return $insStatus;

        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository (activeOrDeactiveUser) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 401);
            }
        }

    }

    public function resetPassword($input){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository (resetPassword) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $data = json_decode(json_encode($input), false);
            $password      =isset($data->password)?$data->password:'';
		    $resetPassword =isset($data->resetPassword)?$data->resetPassword:'';
            $resetCode =isset($data->resetCode)?$data->resetCode:'';

            if ($password=='') {
                throw new AdmnUsrsException('Please enter the Password', 201);               
            } 
             
             if ($resetPassword=='') {
                throw new AdmnUsrsException('Please enter the Reset Password', 201);
               
            } 
            elseif($resetPassword <> $password){
                throw new AdmnUsrsException('Password mismatch.', 201);               
            }
            if ($resetCode=='') {
                throw new AdmnUsrsException('Please enter the Reset Code', 201);               
            } 

            $resetCode  = $this->crypto->encrypt_decrypt($resetCode,'d');
            $userkeyArr = explode("$", $resetCode);
            $userId = $userkeyArr[1];

            if(empty($userId)){
                throw new AdmnUsrsException('Invalid Reset Code', 201);                
            }

            $password    	  = $this->validatePassword($password);
		    $resetPassword    = $this->validatePassword($resetPassword);
            $auditBy=$userId;
            $encrptpassword = $this->crypto->encrypt_decrypt($password, 'e');
            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnUsrModel->resetPasswordUpdate($userId,$encrptpassword,$auditBy);
            $objLogger->info("Update Status : ".$insStatus);
            $objLogger->info("======= End AdmnUsrs Repository (resetPassword) ================");
            return $insStatus;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository (resetPassword) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 401);
            }
        }
    }

    public function sendResetpasswordEmail($email,$emailname, $resetCode){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsRepository');
        $objLogger->info("======= Start sendResetpasswordEmail ================");
        $objLogger->info("-- email : ".$email);
        $objLogger->info("-- emailname : ".$emailname);
        $objLogger->info("-- resetCode : ".$resetCode);

        $adminUrl  = $this->urlSetting->getAdminBaseURL();
        //$footerHtml = $this->mailFooter();
        $objLogger->info("-- adminUrl : ".$adminUrl);

        $subject = "Password Reset Request";	  

            $body = '<div>
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tbody><tr>
                            <td> 							
                                <p>Dear Customer,</p> 
                                <p>
                                    Please <a href="'.$adminUrl.'authentication/reset-password/'.$resetCode.'" style="text-decoration:none;">click here</a> to reset your password. This link will expire in 3 hours.														 
                                </p>  
                                <p> 
                                    If the link does not work, copy and paste the following link in your browser. <br>
                                    '.$adminUrl.'custom/reset-password/'.$resetCode.'
                                </p>
                                <p > 
                                    If you did not make this request or need assistance, please  <a href="'.$adminUrl.'" style="color:#008a8a;text-decoration:none; ">click here</a>. 
                                </p>
                            </td>
                        </tr>                       
                    </tbody>
                </table>
                </div>';  

        $status = $this->mailer->SendEmail($email, $emailname, $subject, $body, $objLogger);
                //print_r($status); die;
        $objLogger->info("-- status : ".$status);
        $objLogger->info("======= END sendResetpasswordEmail ================");
        if($status == true) {
            return 'Success';
        }
        else {
            return 'Failure';
        }    
    }

    public function forgotPassword($input){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository (forgotPassword) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $data = json_decode(json_encode($input), false);
            $email = isset($data->email)?trim($data->email):'';
            if ($email=='') {
                throw new AdmnUsrsException('Please enter the Email', 201);                               
            }

            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $users = $admnUsrModel->getUsersByEmail($email);
            if(empty($users)){
                throw new AdmnUsrsException('Please enter the Valid Email', 201);  
            }    
            $userId = $users->id;
            $resetCode  = $this->generaterandom('1','12');
            $resetCode = $resetCode."$".$userId;
            $resetCode  = $this->crypto->encrypt_decrypt($resetCode,'e');
            $email = $this->validateEmail($email);
            $result = $admnUsrModel->forgotPassword($email, $resetCode);
            if(!empty($result) && $result->ErrorCode != '00'){
                throw new AdmnUsrsException('Please enter the Valid Email', 201); 
            }

            $usrData = $admnUsrModel->getUsersByEmail($email);
            if(empty($usrData)){
                throw new AdmnUsrsException('Please enter the Valid Email', 201); 
            }
            $emailname = $usrData->userName;
            $emailstatus = $this->sendResetpasswordEmail($email,$emailname, $resetCode);

            $objLogger->info("======= END AdmnUsrs Repository (forgotPassword) ================");
            return $usrData;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository (forgotPassword) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 401);
            }
        }

    }

    public function update($input, $userid, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            if(empty($userid)){
                throw new AdmnUsrsException('User Unique Id Empty', 201);
            }

            $data = json_decode(json_encode($input), false);

            $userName = isset($data->userName)?trim($data->userName):'';
            $mobileNo = isset($data->mobileNo)?trim($data->mobileNo):'';
            $designation = isset($data->designation)?trim($data->designation):'';
            //$password = isset($data->password)?trim($data->password):'';
            $email = isset($data->email)?trim($data->email):'';
            $groupId = isset($data->groupId)?trim($data->groupId):'';
            $timeZone = isset($data->timeZone)?trim($data->timeZone):'';
            $adminStatus = isset($data->adminStatus)?trim($data->adminStatus):'';

            if(empty($userName)){
                throw new AdmnUsrsException('User Name Empty', 201);
            }
            /*
            if(empty($password)){
                throw new AdmnUsrsException('Password Empty', 201);
            }
            */
            if(empty($mobileNo)){
                throw new AdmnUsrsException('Mobile No Empty', 201);
            }

            if(empty($designation)){
                throw new AdmnUsrsException('Designation Empty', 201);
            }

            if(empty($email)){
                throw new AdmnUsrsException('Email Empty', 201);
            }

            if(empty($groupId)){
                throw new AdmnUsrsException('Group Empty', 201);
            }

            if(empty($timeZone)){
                throw new AdmnUsrsException('Time Zone Empty', 201);
            }

            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnUsrModel->update($userid, $userName, $mobileNo, $designation, $email, $groupId, $adminStatus, $timeZone, $auditBy, $hotelid, $brandid);
            $objLogger->info("Insert Status : ".$insStatus);
            $objLogger->info("======= End AdmnUsrs Repository ================");
            return $insStatus;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 401);
            }
        }
    }

    public function create($input, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            $data = json_decode(json_encode($input), false);

            $userName = isset($data->userName)?trim($data->userName):'';
            $mobileNo = isset($data->mobileNo)?trim($data->mobileNo):'';
            $designation = isset($data->designation)?trim($data->designation):'';
            $password = isset($data->password)?trim($data->password):'';
            $email = isset($data->email)?trim($data->email):'';
            $groupId = isset($data->groupId)?trim($data->groupId):'';
            $timeZone = isset($data->timeZone)?trim($data->timeZone):'';

            if(empty($userName)){
                throw new AdmnUsrsException('User Name Empty', 201);
            }

            if(empty($password)){
                throw new AdmnUsrsException('Password Empty', 201);
            }

            if(empty($mobileNo)){
                throw new AdmnUsrsException('Mobile No Empty', 201);
            }

            if(empty($designation)){
                throw new AdmnUsrsException('Designation Empty', 201);
            }

            if(empty($email)){
                throw new AdmnUsrsException('Email Empty', 201);
            }

            if(empty($groupId)){
                throw new AdmnUsrsException('Group Empty', 201);
            }

            if(empty($timeZone)){
                throw new AdmnUsrsException('Time Zone Empty', 201);
            }

            $encrptpassword = $this->crypto->encrypt_decrypt($password, 'e');

            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnUsrModel->create($userName, $encrptpassword, $mobileNo, $designation, $email, $groupId, $timeZone, $auditBy, $hotelid, $brandid);
            $objLogger->info("Insert Status : ".$insStatus);
            $objLogger->info("======= End AdmnUsrs Repository ================");
            return $insStatus;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 401);
            }
        }
    }

    public function getUsrOne($userid, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository ================");
        try{
  
            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $usrData = $admnUsrModel->getUsrOne($userid, $auditBy, $hotelid, $brandid);
            $objLogger->info("======= End AdmnUsrs Repository ================");
            return $usrData;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 401);
            }
        }
    }

    public function getUsrsList($input, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
			
            $data            = json_decode(json_encode($input), false);
            $searchValue     = isset($data->searchValue)?$data->searchValue : '';
            $itemperPage     = isset($data->pageSize)?$data->pageSize  : '10';
            $currentPage     = isset($data->pageIndex)?$data->pageIndex  : '0'; 
            $sortName        = isset($data->sortName)?$data->sortName  : ''; 
            $sortOrder       = isset($data->sortOrder)?$data->sortOrder  : 'DESC'; 
            
            $limitFrom       = ($currentPage) * $itemperPage; 
			 //print_r("HAI0");die();
            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $usrlst = $admnUsrModel->getUsrsList($auditBy, $hotelid, $brandid, $searchValue, $itemperPage, $currentPage, $sortName, $sortOrder, $limitFrom);
            $objLogger->info("======= End AdmnUsrs Repository ================");
            return $usrlst;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 401);
            }
        }
    }

    public function getMenuRightStatus($auditBy, $menuid){

        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository ================");
        try{
            
            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $status = $admnUsrModel->getMenuRightStatus($auditBy, $menuid);
            $objLogger->info("Read Write Status : ".$status);
            if(empty($status)){
                throw new AdmnUsrsException('Invalid Access', 401);
            }
            $objLogger->info("======= End AdmnUsrs Repository ================");
            return $status;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 401);
            }
        }
    }
}
