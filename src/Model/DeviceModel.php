<?php
declare(strict_types=1);

namespace App\Model;

use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Device\DeviceException;
use App\Model\DB;

class DeviceModel extends BaseModel
{
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function getInsTmpDevDetls($fileNamewithpath, $hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$auditBy, 'DeviceModel');
        try{

            $sqlQuery = "DELETE FROM tmpassetslist";
            $objLogger->info('DelQuery : '.$sqlQuery); 
            $dbObjtDel = new DB($this->loggerFactory, $this->dBConFactory);
            $delResult = $dbObjtDel->insOrUpdteOrDetQuery($sqlQuery);
            if($delResult)
                $objLogger->info('delete reslut : success');
            else 
                $objLogger->info('delete reslut : failuare');
            
            $blkdata = array();
            $isAVilSuccessData = false;
            $objtCon = $this->dBConFactory->getConnection();
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($fileNamewithpath);
            $worksheet = $spreadsheet->getActiveSheet();
            foreach($worksheet->getRowIterator() as $deviceData) 
            {
                if($deviceData->getRowIndex()==1){
                    $firstCell = $worksheet->getCellByColumnAndRow(1, 1)->getValue();
                    
                    if($firstCell == "Device Name"){
                        continue;
                    }
                    else{
                        throw new DeviceException('Please upload a valid files', 400);
                        exit();
                        break;
                    }
                }
                $rowIndex=$deviceData->getRowIndex();
                $deviceName        =  $worksheet->getCellByColumnAndRow(1, $rowIndex)->getValue();
                $installedDate     = $worksheet->getCellByColumnAndRow(2, $rowIndex)->getValue();
                
                $serialNumber      =  $worksheet->getCellByColumnAndRow(3, $rowIndex)->getValue();
                $manufacturer      = $worksheet->getCellByColumnAndRow(4, $rowIndex)->getValue();
                $deviceType        = $worksheet->getCellByColumnAndRow(5, $rowIndex)->getValue();
                $deviceModel       = $worksheet->getCellByColumnAndRow(6,$rowIndex)->getValue();
                $ipAddress         = $worksheet->getCellByColumnAndRow(7, $rowIndex)->getValue();
                $MACAddress        = $worksheet->getCellByColumnAndRow(8, $rowIndex)->getValue();
                $wirelessMAC       = $worksheet->getCellByColumnAndRow(9, $rowIndex)->getValue();
                $switchNamePort    = $worksheet->getCellByColumnAndRow(10, $rowIndex)->getValue();
                $installedLocation = $worksheet->getCellByColumnAndRow(11, $rowIndex)->getValue();
                $floorInstalled    = $worksheet->getCellByColumnAndRow(12, $rowIndex)->getValue();
                $supportType       = $worksheet->getCellByColumnAndRow(13, $rowIndex)->getValue();
                $supportContractNo = $worksheet->getCellByColumnAndRow(14, $rowIndex)->getValue();
                $supportContractExpireDate = $worksheet->getCellByColumnAndRow(15, $rowIndex)->getValue();
                $community         = $worksheet->getCellByColumnAndRow(16, $rowIndex)->getValue();
                $location          = $worksheet->getCellByColumnAndRow(17, $rowIndex)->getValue();
                $locationType      = $worksheet->getCellByColumnAndRow(18, $rowIndex)->getValue();
                $status            = $worksheet->getCellByColumnAndRow(19, $rowIndex)->getValue();
                $notes             = $worksheet->getCellByColumnAndRow(20, $rowIndex)->getValue();
                //$brandName         = $worksheet->getCellByColumnAndRow(21, $rowIndex)->getValue();
                $hotelCode         = $worksheet->getCellByColumnAndRow(21, $rowIndex)->getValue();
                $icmpPolicy        = $worksheet->getCellByColumnAndRow(22, $rowIndex)->getValue();

                //print date('Y-m-d', $installedDate); die();

                if(!empty($installedDate)){
                    $installedDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($installedDate);
                    $installedDate = date('Y-m-d', $installedDate);
                }
                if(empty($supportContractExpireDate)){
                    $supportContractExpireDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($supportContractExpireDate);
                    $supportContractExpireDate = date('Y-m-d', $supportContractExpireDate);
                }

                $insQuery = "INSERT INTO tmpassetslist(DeviceName,DeviceIp, Community, InstallationOn, SerialNo, 
                Manufacturer, DeviceType, ModelNo, Mac, WirelessMac, SwitchOrPort,InstalledLocation,FloorInstalled, 
                SupportType, ContractNo, ContractExpiryOn, deviceLocId, deviceLocType, Status, createdOn, createdBy, hotelCode, notes, ICMPPolicy)
                VALUES('".$deviceName."', '".$ipAddress."', '".$community."', '".$installedDate."', '".$serialNumber."', 
                '".$manufacturer."', '".$deviceType."', '".$deviceModel."', '".$MACAddress."', '".$wirelessMAC."', '".$switchNamePort."',
                '".$installedLocation."', '".$floorInstalled."', '".$supportType."', '".$supportContractNo."', '".$supportContractExpireDate."',
                '".$location."', '".$locationType."', '".$status."', SYSDATE(), ".$auditBy.", '".$hotelCode."', '".$notes."', '".$icmpPolicy."')";

                $objLogger->info('insQuery : '.$insQuery);

                $result = mysqli_query($objtCon, $insQuery);
                $errorMsg = mysqli_error($objtCon);
                if($result) {
                    $isAVilSuccessData = true;
                    $objLogger->info('Result status : inserted successfully');
                }
                else {
                    $objLogger->info('Result status : not inserted');
                    $objLogger->info('errorMsg : '.$errorMsg);
                }
            }

            $this->dBConFactory->close($objtCon);
            if($isAVilSuccessData == true){
                $blkdata = $this->bulkuploadToDB($hotelId, $auditBy);
                return $blkdata;
            }
            else {
                throw new DeviceException('Bulk upload failure', 401);
            }

            return $blkdata;
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }


    public function bulkuploadToDB($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$auditBy, 'DeviceModel');
        $blkupldData = array();
        try{

            $success = 0;
            $failure = 0;
            $sqlQuery = "call SP_AssetsBulkUpload (".$auditBy.", ".$hotelId.")";
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getMultiDatasByArray($sqlQuery);
            //$objLogger->info('update Return : '.json_encode($insResult));
           
            if(!empty($insResult)){
                for($i=0; $i< count($insResult); $i++){
                    $row = $insResult[$i];                    
                    if(!empty($row['ErrorStatus']) && strtoupper($row['ErrorStatus']) == 'SUCCESS'){
                        $success = $success + 1;	
                    }
                    else {
                        $failure = $failure + 1;
                    }
                }
            }
            
            $blkupldData['successcount'] = $success;
            $blkupldData['failuarecount'] = $failure;
            $blkupldData['data'] = $insResult;

            return $blkupldData;
            
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }

    public function update($data, $deviceid, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$auditBy, 'DeviceModel');
        try{

            
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

            $installedDate = $this->dateFormat($installedDate, "Y-m-d H:i:s");
            if(empty($installedDate)){
                throw new DeviceException('Please enter valid installed Date', 200);
            }

            $supportContractExpireDate = $this->dateFormat($supportContractExpireDate, "Y-m-d H:i:s");
            if(empty($supportContractExpireDate)){
                throw new DeviceException('Please enter valid Support Contract Expire Date', 200);
            }

            $action = 'update';
            $sqlQuery = "call SP_DeviceConfig ('$action', ".$deviceid.",'$deviceName','$ipAddress','$wirelessMAC',
            '$installedLocation','0','$supportType','$auditBy','$supportContractExpireDate',
            '$locationType','$status','$installedDate','$manufacturer','$deviceModel','$MACAddress',
            '$switchNamePort','$floorInstalled','$supportContractNo','$community','$location','$notes',
            'null','$serialNumber','$deviceType','$icmpPolicy', NULL, NULL)";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('update Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){

                return 'SUCCESS';
            }
            else {
                throw new DeviceException($insResult->Result, 401);
            }
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }

    public function create($data, $auditBy,$userName){
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$userName, 'DeviceModel');
        try{
            
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

            $installedDate = $this->dateFormat($installedDate, "Y-m-d H:i:s");
            if(empty($installedDate)){
                throw new DeviceException('Please enter valid installed Date', 200);
            }

            $supportContractExpireDate = $this->dateFormat($supportContractExpireDate, "Y-m-d H:i:s");
            if(empty($supportContractExpireDate)){
                throw new DeviceException('Please enter valid Support Contract Expire Date', 200);
            }

            $action = 'create';
            $sqlQuery = "call SP_DeviceConfig ('$action', 0,'$deviceName','$ipAddress','$wirelessMAC',
            '$installedLocation','$hotelId','$supportType','$auditBy','$supportContractExpireDate',
            '$locationType','$status','$installedDate','$manufacturer','$deviceModel','$MACAddress',
            '$switchNamePort','$floorInstalled','$supportContractNo','$community','$location','$notes',
            'null','$serialNumber','$deviceType','$icmpPolicy', NULL, NULL)";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Insert Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){

                return 'SUCCESS';
            }
            else {
                throw new DeviceException($insResult->Result, 401);
            }
            
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }

    public function getUsrOne($assetid, $auditBy)
    {
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$auditBy, 'DeviceModel');       
        try{
            
            $action = 'getSingle';
            $sqlQuery = "call SP_DeviceConfig ('$action',".$assetid.",'null','null','null','null','null','null',
            ".$auditBy.",NULL,'null','null',NULL,'null','null','null','null','null','null','null','null','null',
            'null','null','null','null', NULL, NULL)";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $deviceData = $dbObjt->getSingleDatasByObjects($sqlQuery);
            return $deviceData;
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }

    public function getDeviceList($auditBy, $hotelid, $startDate, $endDate)
    {
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$auditBy, 'DeviceModel');       
        try{
            
            $action = 'getAll';
            $sqlQuery = "call SP_DeviceConfig ('$action',0,'null','null','null','null','$hotelid','null',
            ".$auditBy.",NULL,'null','null',NULL,'null','null','null','null','null','null','null','null','null',
            'null','null','null','null', '".$startDate."', '".$endDate."')";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $devices = $dbObjt->getMultiDatasByObjects($sqlQuery);

            if(count($devices)>=1)
                $objLogger->info('Device List Avaliable Device list count : '.count($devices));

            return $devices;
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }

    public function getMenuRightStatus($auditBy, $menuid){
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction_'.$auditBy, 'DeviceModel');
        try{

            $sqlQuery = "SELECT grp.ReadWriteAccess FROM adminmenugroup AS grp 
                         INNER JOIN adminusers as usr ON usr.userGroup = grp.groupID
                         INNER JOIN adminmenus as mnu ON mnu.MenuID = grp.MenuID
                         WHERE usr.id = '$auditBy' and mnu.MenuID = '".$menuid."' ";

            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $user = $dbObjt->getSingleDatasByObjects($sqlQuery);

            if(empty($user->ReadWriteAccess)){
                throw new DeviceException('Invalid Access', 401);
            }

            return $user->ReadWriteAccess;
        }
        catch (DeviceException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException('Invalid Access', 401);
            }
        }
    }
}
