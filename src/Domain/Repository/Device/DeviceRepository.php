<?php

declare(strict_types=1);

namespace App\Domain\Repository\Device;

use App\Domain\Service\Device\DeviceService;
use App\Exception\Device\DeviceException;
use App\Model\DeviceModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Factory\MailerFactory; 
use App\Factory\UrlSettingFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\Crypto;

class DeviceRepository extends BaseRepository implements DeviceService
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

    public function bulkUpload($input, $files, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$auditBy, 'DeviceRepository');
        $objLogger->info("======= Start Device Repository (bulkUpload) ================");
        try{

            $data = json_decode(json_encode($input), false);
            $hotelId =isset($data->hotel_id)?$data->hotel_id:'';   
            
            if(empty($hotelId)){
                throw new DeviceException('Please select Hotel.', 201);
            }

            if(empty($files)){
                throw new DeviceException('Please upload a valid files.', 201);
            }
            
            if($files->getError() === UPLOAD_ERR_OK){
                $extension = pathinfo($files->getClientFilename(), PATHINFO_EXTENSION);
                if(strtolower($extension) != 'xlsx'){
                    throw new DeviceException("Invalid file type. Allowed file type's are xlsx.", 201);
                }
                //$uniqueFilename = 	date("YmdHis").".xlsx";    
                $fileName = $files->getClientFilename();
                $filePath = "../uploads/bulkupload/device";
                $objLogger->info(" -- filePath : ".$filePath);
                $objLogger->info(" -- fileName : ".$fileName);

                $files->moveTo($filePath."/".$fileName);

                $deviceModel = new DeviceModel($this->loggerFactory, $this->dBConFactory);
                $blkdata = $deviceModel->getInsTmpDevDetls($filePath."/".$fileName, $hotelId, $auditBy);
                //$objLogger->info("Bulk Data Response : ".count($blkdata));
                $objLogger->info("======= End Device Repository ================");
                return $blkdata;
                

            }
            else {
                throw new DeviceException('Error occurs while uploading a file.', 201);
            }
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Device Repository (bulkUpload) ================");
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }
	
    public function update($input, $deviceid, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$auditBy, 'DeviceRepository');
        $objLogger->info("======= Start Device Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            if(empty($deviceid)){
                throw new DeviceException('Device Id Empty', 201);
            }

            $data = json_decode(json_encode($input), false);

            $deviceName           =isset($data->deviceName)?$data->deviceName:'';
            $serialNumber         =isset($data->serialNo)? trim($data->serialNo):'';
            $deviceType           =isset($data->deviceType)? $data->deviceType:'';
            $ipAddress            =isset($data->deviceIp)?$data->deviceIp:'';
            $wirelessMAC          =isset($data->wirelessMac)? trim($data->wirelessMac):'';
            $installedLocation    =isset($data->installedLocation)? trim($data->installedLocation):'';
            $supportType          =isset($data->supportType)? trim($data->supportType):'';
            $supportContractExpireDate =isset($data->contractExpiryOn)? trim($data->contractExpiryOn):'';
            $locationType         =isset($data->deviceLocType)? trim($data->deviceLocType):'';
            $status               =isset($data->status)? $data->status:'';
            $installedDate        =isset($data->installationOn)? trim($data->installationOn):'';
            $manufacturer         =isset($data->manufacturer)? trim($data->manufacturer):'';
            $deviceModel          =isset($data->modelNo)? trim($data->modelNo):'';
            $MACAddress           =isset($data->mac)? trim($data->mac):'';
            $switchNamePort       =isset($data->switchOrPort)? trim($data->switchOrPort):'';
            $floorInstalled       =isset($data->floorInstalled)? trim($data->floorInstalled):'';
            $supportContractNo    =isset($data->contractNo)? trim($data->contractNo):'';
            $community            =isset($data->community)? trim($data->community):'';
            $location             =isset($data->deviceLocId)? ($data->deviceLocId):'';
            $notes                =isset($data->notes)? trim($data->notes):'';
            //$brandId              =isset($data->brand_id)?$data->brand_id:'';
            //$hotelId              =isset($data->hotel_id)?$data->hotel_id:'';
            $icmpPolicy           =isset($data->PolicyID)?$data->PolicyID:'';
         
            if ($deviceName=='') {
                throw new DeviceException('Please enter the Device Name', 201);               
            }
            if ($ipAddress=='') {
                throw new DeviceException('Please enter the IP Address', 201);                
            }
            if ($wirelessMAC=='') {
                throw new DeviceException('Please enter the wireless MAC', 201);               
            }
            if ($installedLocation=='') {
                throw new DeviceException('Please enter the installed location', 201);              
            }
             if ($supportType=='') {
                throw new DeviceException('Please enter the Support Type', 201);               
            }
            if ($supportContractExpireDate=='') {
                throw new DeviceException('Please enter the Support Contract Expire Date', 201);              
            } 
            if ($locationType=='') {
                throw new DeviceException('Please enter the Location Type', 201);                
            }
            if ($status=='') {
                throw new DeviceException('Please enter the Status', 201);               
            }
            if ($installedDate=='') {
                throw new DeviceException('Please enter the installed Date', 201);                
            }
            if ($manufacturer=='') {
                throw new DeviceException('Please enter the manufacturer', 201);               
            }
            if ($deviceModel=='') {
                throw new DeviceException('Please enter the device model', 201);               
            }
            if ($MACAddress=='') {
                throw new DeviceException('Please enter the MAC Address', 201);               
            }
            if ($switchNamePort=='') {
                throw new DeviceException('Please enter the Switch Name/Port', 201);                
            }
            if ($floorInstalled=='') {
                throw new DeviceException('Please enter the floor installed', 201);               
            }
            if ($supportContractNo=='') {
                throw new DeviceException('Please enter the support contract number', 201);               
            }
            if ($community=='') {
                throw new DeviceException('Please enter the community', 201);              
            }
            if ($location=='') {
                throw new DeviceException('Please enter the location', 201);              
            }
            if ($notes=='') {
                throw new DeviceException('Please enter the notes', 201);              
            }
            
            if ($icmpPolicy=='') {
                throw new DeviceException('Please enter the icmp Policy', 201);
            }

        
            $deviceModel = new DeviceModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $deviceModel->update($data, $deviceid, $auditBy);
            $objLogger->info("update Status : ".$insStatus);
            $objLogger->info("======= End Device Repository ================");
            return $insStatus;
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Device Repository ================");
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }

    public function create($input, $auditBy,$userName){
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$userName, 'DeviceRepository');
        $objLogger->info("======= Start Device Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            $data = json_decode(json_encode($input), false);

            $deviceName           =isset($data->deviceName)?$data->deviceName:'';
            $serialNumber         =isset($data->serialNo)? trim($data->serialNo):'';
            $deviceType           =isset($data->deviceType)? $data->deviceType:'';
            $ipAddress            =isset($data->deviceIp)?$data->deviceIp:'';
            $wirelessMAC          =isset($data->wirelessMac)? trim($data->wirelessMac):'';
            $installedLocation    =isset($data->installedLocation)? trim($data->installedLocation):'';
            $supportType          =isset($data->supportType)? trim($data->supportType):'';
            $supportContractExpireDate =isset($data->contractExpiryOn)? trim($data->contractExpiryOn):'';
            $locationType         =isset($data->deviceLocType)? trim($data->deviceLocType):'';
            $status               =isset($data->status)? $data->status:'';
            $installedDate        =isset($data->installationOn)? trim($data->installationOn):'';
            $manufacturer         =isset($data->manufacturer)? trim($data->manufacturer):'';
            $deviceModel          =isset($data->modelNo)? trim($data->modelNo):'';
            $MACAddress           =isset($data->mac)? trim($data->mac):'';
            $switchNamePort       =isset($data->switchOrPort)? trim($data->switchOrPort):'';
            $floorInstalled       =isset($data->floorInstalled)? trim($data->floorInstalled):'';
            $supportContractNo    =isset($data->contractNo)? trim($data->contractNo):'';
            $community            =isset($data->community)? trim($data->community):'';
            $location             =isset($data->deviceLocId)? ($data->deviceLocId):'';
            $notes                =isset($data->notes)? trim($data->notes):'';
            //$brandId              =isset($data->brand_id)?$data->brand_id:'';
            $hotelId              =isset($data->hotel_id)?$data->hotel_id:'';
            $icmpPolicy           =isset($data->PolicyID)?$data->PolicyID:'';

            if ($hotelId=='') {
                throw new DeviceException('Please enter the Hotel ID', 201);
                exit();
            }
            if ($deviceName=='') {
                throw new DeviceException('Please enter the Device Name', 201);
                exit();
            }
            if ($ipAddress=='') {
                throw new DeviceException('Please enter the IP Address', 201);
                exit();
            }
            if ($wirelessMAC=='') {
                throw new DeviceException('Please enter the wireless MAC', 201);
                exit();
            }
            if ($installedLocation=='') {
                throw new DeviceException('Please enter the installed location', 201);
                exit();
            }
             if ($supportType=='') {
                throw new DeviceException('Please enter the Support Type', 201);
                exit();
            }
            if ($supportContractExpireDate=='') {
                throw new DeviceException('Please enter the Support Contract Expire Date', 201);
                exit();
            } 
            if ($locationType=='') {
                throw new DeviceException('Please enter the Location Type', 201);
                exit();
            }
            if ($status=='') {
                throw new DeviceException('Please enter the Status', 201);
                exit();
            }
            if ($installedDate=='') {
                throw new DeviceException('Please enter the installed Date', 201);
                exit();
            }
            if ($manufacturer=='') {
                throw new DeviceException('Please enter the manufacturer', 201);
                exit();
            }
            if ($deviceModel=='') {
                throw new DeviceException('Please enter the device model', 201);
                exit();
            }
            if ($MACAddress=='') {
                throw new DeviceException('Please enter the MAC Address', 201);
                exit();
            }
            if ($switchNamePort=='') {
                throw new DeviceException('Please enter the Switch Name/Port', 201);
                exit();
            }
            if ($floorInstalled=='') {
                throw new DeviceException('Please enter the floor installed', 201);
                exit();
            }
            if ($supportContractNo=='') {
                throw new DeviceException('Please enter the support contract number', 201);
                exit();
            }
            if ($community=='') {
                throw new DeviceException('Please enter the community', 201);
                exit();
            }
            if ($location=='') {
                throw new DeviceException('Please enter the location', 201);
                exit();
            }
            if ($notes=='') {
                throw new DeviceException('Please enter the notes', 201);
                exit();
            }
            
            if ($icmpPolicy=='') {
                throw new DeviceException('Please enter the icmp Policy', 201);
                exit();
            }

            $deviceModel = new DeviceModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $deviceModel->create($data, $auditBy,$userName);
            $objLogger->info("Insert Status : ".$insStatus);
            $objLogger->info("======= End Device Repository ================");
            return $insStatus;
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Device Repository ================");
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }

    public function getUsrOne($assetid, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$auditBy, 'DeviceRepository');
        $objLogger->info("======= Start Device Repository ================");
        try{
  
            $admnUsrModel = new DeviceModel($this->loggerFactory, $this->dBConFactory);
            $deviceData = $admnUsrModel->getUsrOne($assetid, $auditBy);
            $objLogger->info("======= End Device Repository ================");
            return $deviceData;
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Device Repository ================");
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }

    public function getDeviceList($inputData,$auditBy, $userName,$hotelid, $startDate, $endDate){
        //print_R($auditBy);die();
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$userName, 'DeviceRepository');
        $objLogger->info("======= Start Device Repository ================");
        //$objLogger->info("Input Data : ".json_encode($input));
        try{
            

            
            if(!empty($startDate)){

                if(empty($endDate)){
                    throw new DeviceException('Please Enter End Date', 201);
                }

                $datetime = new \DateTime($startDate);
                $startDate = $datetime->format('Y-m-d').' 00:00:00';

                if(empty($startDate)){
                    throw new DeviceException('Invalid Start Date', 201);
                }

                $datetime = new \DateTime($endDate);
                $endDate = $datetime->format('Y-m-d').' 23:59:59';

                if(empty($endDate)){
                    throw new DeviceException('Invalid End Date', 201);
                }

            }

            $admnUsrModel = new DeviceModel($this->loggerFactory, $this->dBConFactory);
            $devlst = $admnUsrModel->getDeviceList($auditBy, $hotelid, $startDate, $endDate);
            $objLogger->info("======= End Device Repository ================");
            return $devlst;
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Device Repository ================");
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }

    public function getMenuRightStatus($auditBy, $menuid){

        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$auditBy, 'DeviceRepository');
        $objLogger->info("======= Start Device Repository ================");
        try{
            
            $admnUsrModel = new DeviceModel($this->loggerFactory, $this->dBConFactory, $this->mailer, $this->urlSetting);
            $status = $admnUsrModel->getMenuRightStatus($auditBy, $menuid);
            $objLogger->info("Read Write Status : ".$status);
            if(empty($status)){
                throw new DeviceException('Invalid Access', 401);
            }
            $objLogger->info("======= End Device Repository ================");
            return $status;
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Device Repository ================");
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }
}
