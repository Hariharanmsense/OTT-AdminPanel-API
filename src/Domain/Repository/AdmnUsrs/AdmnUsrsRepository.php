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
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdmnUsrsRepository extends BaseRepository implements AdmnUsrsService
{
    
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    //protected MailerFactory $mailer;
	protected Crypto $crypto;
    protected UrlSettingFactory $urlSetting;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory, Crypto $crypto, UrlSettingFactory $urlSetting)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
		$this->crypto = $crypto;
        //$this->mailer = $mailer;
        $this->urlSetting = $urlSetting;
    }

    public function profileUpdate($input, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{

            $data = json_decode(json_encode($input), false);

            $userName = isset($data->userName)?trim($data->userName):'';
            $mobile = isset($data->mobile)?trim($data->mobile):'';
            $email = isset($data->email)?trim($data->email):'';
            $userId = isset($data->userId)?trim($data->userId):'';

            if(empty($userId)){
                throw new AdmnUsrsException('User Unique Id Empty', 201);
            }

            if(empty($userName)){
                throw new AdmnUsrsException('User Name Empty', 201);
            }

            if(empty($email)){
                throw new AdmnUsrsException('Email ID Empty', 201);
            }
           
            if(empty($mobile)){
                throw new AdmnUsrsException('Mobile No Empty', 201);
            }

            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnUsrModel->profileUpdate($userId, $userName, $mobile, $email, $auditBy);
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
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function getLastLogin($input, $auditBy){

        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            $data = json_decode(json_encode($input), false);
            $userId=isset($data->userId)?trim($data->userId):'';

            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $lastloginlst = $admnUsrModel->getLastLogin($userId, $auditBy);
            $objLogger->info("======= End AdmnUsrs Repository ================");
            return $lastloginlst;

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
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function resetPasswordGet($resetCode){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository (resetPasswordGet) ================");
        try{
            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $usrlst = $admnUsrModel->resetPasswordGet($resetCode);
            $objLogger->info("======= End AdmnUsrs Repository ================");
            return $usrlst;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository (resetPasswordGet) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function delete($userid, $auditBy) {
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository (delete) ================");
        try{
            if ($userid=='') {
                throw new AdmnUsrsException('Please enter the User ID', 201);
            }

            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnUsrModel->delete($userid,$auditBy);
            $objLogger->info("delete Status : ".$insStatus);
            $objLogger->info("======= End AdmnUsrs Repository (delete) ================");
            return $insStatus;

        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository (create) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function excel($response, $input, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository (Excel) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $dteFltrStDte = date("d-M-Y H:i:s");
	        $dteFltrEdDte = date("d-M-Y H:i:s");
            $auditData = $this->getUserName($auditBy);
            if(empty($auditData)){
                throw new AdmnUsrsException('Invalid Access', 201);
            }
            $whoModified = $auditData->userName;
            $usrList = $this->getUsrsList($input, $auditBy, $hotelid, $brandid);                              
            if(empty($usrList)){
                throw new AdmnUsrsException('No Records Found', 201);
            }

            $recordCount =  count($usrList);

            $columnHeaders = array("A"=>"SNo", "B"=>"User Name", "C"=>"Group Name", "D"=>"Mobile", "E"=>"Designation",
            "F"=>"Email", "G"=>"TimeZone", "H"=>"Status","I"=>"CreatedOn");
            $excel = new Spreadsheet();
            $activeFirstSheet = $excel->getActiveSheet();
            $activeFirstSheet->setTitle("User Management");
            $excel->setActiveSheetIndex(0);
            $activeFirstSheet->setCellValue('A1', "Report Name:");
            $activeFirstSheet->setCellValue('C1', "User_Management_Report");
            $activeFirstSheet->mergeCells('A1:B1');
            $activeFirstSheet->mergeCells('C1:I1');
            $activeFirstSheet->setCellValue('A2', "Who Downloaded:");
            $activeFirstSheet->setCellValue('C2', $whoModified);
            $activeFirstSheet->mergeCells('A2:B2');
            $activeFirstSheet->mergeCells('C2:I2');
            $activeFirstSheet->setCellValue('A3', "Total Records: ");
            $activeFirstSheet->getStyle('C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $activeFirstSheet->setCellValue('C3', $recordCount);
            $activeFirstSheet->mergeCells('A3:B3');
            $activeFirstSheet->mergeCells('C3:I3');
            $activeFirstSheet->setCellValue('A4', "Date: ");
            $activeFirstSheet->setCellValue('C4', date("d-M-Y"));
            $activeFirstSheet->mergeCells('A4:B4');
            $activeFirstSheet->mergeCells('C4:I4');
            //$activeFirstSheet->setCellValue('A5', "Date Filters: From: ".$dteFltrStDte." To: ".$dteFltrEdDte);
            //$activeFirstSheet->mergeCells('A5:E5');

            foreach( $columnHeaders as $columnHeader => $headerValue ){
                $activeFirstSheet->getColumnDimension("{$columnHeader}")->setAutoSize(true);
                $activeFirstSheet->setCellValue( "{$columnHeader}5", $headerValue );
            }

            $activeFirstSheet->getStyle('A5:I5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('000000');
			
		    $activeFirstSheet->getStyle('A5:I5')->getFont()->getColor()->setRGB ('EEEEEE');
			$activeFirstSheet->getStyle("A5:I5")->getFont()->setBold( true );		
			$excel->getSheetByName("User");

            $sno = 6;
            foreach($usrList as $grp){
                
                $activeFirstSheet->setCellValue('A'.$sno, ($sno-5));
                $activeFirstSheet->setCellValue('B'.$sno, trim($grp->userName));
                $activeFirstSheet->setCellValue('C'.$sno, trim($grp->GroupName));
                $activeFirstSheet->setCellValueExplicit('B'.$sno,trim($grp->mobileno),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                //$activeFirstSheet->setCellValue('D'.$sno, $grp->mobileno);
                $activeFirstSheet->setCellValue('E'.$sno, trim($grp->designation));
                $activeFirstSheet->setCellValue('F'.$sno, trim($grp->email));
                $activeFirstSheet->setCellValue('G'.$sno, trim($grp->timeZone));
                $activeFirstSheet->setCellValue('H'.$sno, trim($grp->adminStatusDetail));
                $activeFirstSheet->setCellValue('I'.$sno, trim($grp->createdOn));
                $sno++;
            }

            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => '000000'),
                    ),
                ),
            );
            $activeFirstSheet->getStyle('A1:I'.($sno-1))->applyFromArray($styleArray);

            $excelWriter = new Xlsx($excel);
            $tempFile = tempnam(File::sysGetTempDir(), 'phpxltmp');
            $tempFile = $tempFile ?: __DIR__ . '/temp.xlsx';
            $excelWriter->save($tempFile);

            // For Excel2007 and above .xlsx files   
            $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response = $response->withHeader('Content-Disposition', 'attachment; filename="user_management.xlsx"');
            $stream = fopen($tempFile, 'r+');
            $response->getBody()->write(fread($stream, (int)fstat($stream)['size']));
            return $response;


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
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
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
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }

    }


    public function oldPassword($input, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository (oldPassword) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $data = json_decode(json_encode($input), false);
            $password = isset($data->password)?$data->password:'';
            $userId = isset($data->userId)?$data->userId:'';

            if (empty($password)) {
                throw new AdmnUsrsException('Please enter the Password', 201);               
            } 

            if(empty($userId)){
                throw new AdmnUsrsException('Invalid UserId', 201);                
            }
            
            $encrptpassword = $this->crypto->encrypt_decrypt($password, 'e');
            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $usrData = $admnUsrModel->checkOldPassword($userId, $encrptpassword, $auditBy);
            return $usrData;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository (oldPassword) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function ownPasswordUpdate($input){
        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository (ownPasswordUpdate) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{ 
            $data = json_decode(json_encode($input), false);
            $password      =isset($data->password)?$data->password:'';
		    $resetPassword =isset($data->resetPassword)?$data->resetPassword:'';
            $userId =isset($data->userId)?$data->userId:'';

            if ($password=='') {
                throw new AdmnUsrsException('Please enter the Password', 201);               
            } 
             
             if ($resetPassword=='') {
                throw new AdmnUsrsException('Please enter the Reset Password', 201);
               
            } 
            elseif($resetPassword <> $password){
                throw new AdmnUsrsException('Password mismatch.', 201);               
            }

            if(empty($userId)){
                throw new AdmnUsrsException('Invalid UserId', 201);                
            }

            $password    	  = $this->validatePassword($password);
		    $resetPassword    = $this->validatePassword($resetPassword);
            //$auditBy=$userId;
            $encrptpassword = $this->crypto->encrypt_decrypt($password, 'e');
            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnUsrModel->resetPasswordUpdate($userId,$encrptpassword,$userId);
            $objLogger->info("Update Status : ".$insStatus);
            $objLogger->info("======= End AdmnUsrs Repository (ownPasswordUpdate) ================");
            return $insStatus;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository (ownPasswordUpdate) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }  
    }

    public function passwordUpdate($input, $auditBy){

        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository (passwordUpdate) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $data = json_decode(json_encode($input), false);
            $password      =isset($data->password)?$data->password:'';
		    $resetPassword =isset($data->resetPassword)?$data->resetPassword:'';
            $userId =isset($data->userId)?$data->userId:'';

            if ($password=='') {
                throw new AdmnUsrsException('Please enter the Password', 201);               
            } 
             
             if ($resetPassword=='') {
                throw new AdmnUsrsException('Please enter the Reset Password', 201);
               
            } 
            elseif($resetPassword <> $password){
                throw new AdmnUsrsException('Password mismatch.', 201);               
            }

            if(empty($userId)){
                throw new AdmnUsrsException('Invalid UserId', 201);                
            }

            $password    	  = $this->validatePassword($password);
		    $resetPassword    = $this->validatePassword($resetPassword);
            //$auditBy=$userId;
            $encrptpassword = $this->crypto->encrypt_decrypt($password, 'e');
            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnUsrModel->resetPasswordUpdate($userId,$encrptpassword,$auditBy);
            $objLogger->info("Update Status : ".$insStatus);
            $objLogger->info("======= End AdmnUsrs Repository (passwordUpdate) ================");
            return $insStatus;
        }
        catch (AdmnUsrsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnUsrs Repository (passwordUpdate) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
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
            $userId =isset($data->userId)?$data->userId:'';

            if ($password=='') {
                throw new AdmnUsrsException('Please enter the Password', 201);               
            } 
             
             if ($resetPassword=='') {
                throw new AdmnUsrsException('Please enter the Reset Password', 201);
               
            } 
            elseif($resetPassword <> $password){
                throw new AdmnUsrsException('Password mismatch.', 201);               
            }

            if(empty($userId)){
                throw new AdmnUsrsException('Invalid UserId', 201);                
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
                throw new AdmnUsrsException($ex->getMessage(), 201);
            }
            else {
                throw new AdmnUsrsException('Invalid Access', 201);
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
                                    '.$adminUrl.'authentication/reset-password/'.$resetCode.'
                                </p>
                                <p > 
                                    If you did not make this request or need assistance, please  <a href="'.$adminUrl.'" style="color:#008a8a;text-decoration:none; ">click here</a>. 
                                </p>
                            </td>
                        </tr>                       
                    </tbody>
                </table>
                </div>';  
        
        $setting = $this->getSmtpSettingsFromDb(0, $email);
        $mailer = new MailerFactory($setting);
        $status = $mailer->SendEmail($email, $emailname, $subject, $body, $objLogger);
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
                throw new AdmnUsrsException('Invalid Access', 201);
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
                throw new AdmnUsrsException('Invalid Access', 201);
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
                throw new AdmnUsrsException('Invalid Access', 201);
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
                throw new AdmnUsrsException('Invalid Access', 201);
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
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }

    public function getMenuRightStatus1($auditBy, $menuid){

        $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction_'.$auditBy, 'AdmnUsrsRepository');
        $objLogger->info("======= Start AdmnUsrs Repository ================");
        try{
            
            $admnUsrModel = new AdmnUsrModel($this->loggerFactory, $this->dBConFactory);
            $status = $admnUsrModel->getMenuRightStatus($auditBy, $menuid);
            $objLogger->info("Read Write Status : ".$status);
            if(empty($status)){
                throw new AdmnUsrsException('Invalid Access', 201);
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
                throw new AdmnUsrsException('Invalid Access', 201);
            }
        }
    }
}
