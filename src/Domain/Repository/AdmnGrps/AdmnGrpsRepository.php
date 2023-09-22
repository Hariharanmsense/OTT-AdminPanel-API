<?php

declare(strict_types=1);

namespace App\Domain\Repository\AdmnGrps;

use App\Domain\Service\AdmnGrps\AdmnGrpsService;
use App\Exception\AdmnGrps\AdmnGrpsException;
use App\Model\AdmnGrpModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdmnGrpsRepository extends BaseRepository implements AdmnGrpsService
{
    
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtTokenObjt;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory, JwtToken $jwtTokenObjt)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
        $this->jwtTokenObjt = $jwtTokenObjt;
    }

    public function delete($groupid, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository (delete) ================");
        try{
            if ($groupid=='') {
                throw new AdmnGrpsException('Please enter the group ID', 200);
            }

            $admnGrpsModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnGrpsModel->delete($groupid,$auditBy);
            $objLogger->info("delete Status : ".$insStatus);
            $objLogger->info("======= End AdmnGrps Repository (delete) ================");
            return $insStatus;

        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository (create) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }

    public function editGroupAssginMenu($input, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository (editGroupAssginMenu) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{

            $data = json_decode(json_encode($input), false);
            $groupName = isset($data->groupName)?trim($data->groupName):'';
            $groupId = isset($data->groupId)?trim($data->groupId):'';
            $hotelId = isset($data->hotelId)?trim($data->hotelId):'';
            $readMenus = isset($data->readMenus)?$data->readMenus:'';
            $writeMenus = isset($data->writeMenus)?$data->writeMenus:'';

            if(empty($groupName)){
                throw new AdmnGrpsException('Group Name Empty', 201);
            }

            if(empty($groupId)){
                throw new AdmnGrpsException('groupId Empty', 201);
            }

            if(empty($hotelId)){
                throw new AdmnGrpsException('hotelId Empty', 201);
            }

            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnGrpModel->editGroupAssginMenu($input, $auditBy);
            $objLogger->info("Update Status : ".$insStatus);
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $insStatus;

        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository (editGroupAssginMenu) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }

    }

    public function createGroupAssginMenu($input, $auditBy){

        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository (createGroupAssginMenu) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $data = json_decode(json_encode($input), false);
            $groupName = isset($data->groupName)?trim($data->groupName):'';
            $hotelId = isset($data->hotelId)?trim($data->hotelId):'';
            $readMenus = isset($data->readMenus)?$data->readMenus:'';
            $writeMenus = isset($data->writeMenus)?$data->writeMenus:'';

            if(empty($groupName)){
                throw new AdmnGrpsException('Group Name Empty', 201);
            }

            if(empty($hotelId)){
                throw new AdmnGrpsException('hotelId Empty', 201);
            }

            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnGrpModel->createGroupAssginMenu($input, $auditBy);
            //$objLogger->info("Insert Status : ".$insStatus);
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $insStatus;


        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository (createGroupAssginMenu) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }

    public function excel($response, $input, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository (Excel) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $dteFltrStDte = date("d-M-Y H:i:s");
	        $dteFltrEdDte = date("d-M-Y H:i:s");
            $auditData = $this->getUserName($auditBy);
            if(empty($auditData)){
                throw new AdmnGrpsException('Invalid Access', 201);
            }
            $whoModified = $auditData->userName;
            $grpList = $this->getGrpList($input, $auditBy, $hotelid);

            if(empty($grpList)){
                throw new AdmnGrpsException('No Records Found', 201);
            }

            $recordCount =  count($grpList);

            $columnHeaders = array("A"=>"SNo", "B"=>"Group Name", "C"=>"Status", "D"=>"CreatedBy", "E"=>"CreatedOn");
            $excel = new Spreadsheet();
            $activeFirstSheet = $excel->getActiveSheet();
            $activeFirstSheet->setTitle("Group Management");
            $excel->setActiveSheetIndex(0);
            $activeFirstSheet->setCellValue('A1', "Report Name:");
            $activeFirstSheet->setCellValue('C1', "Group_Management_Report");
            $activeFirstSheet->mergeCells('A1:B1');
            $activeFirstSheet->mergeCells('C1:E1');
            $activeFirstSheet->setCellValue('A2', "Who Downloaded:");
            $activeFirstSheet->setCellValue('C2', $whoModified);
            $activeFirstSheet->mergeCells('A2:B2');
            $activeFirstSheet->mergeCells('C2:E2');
            $activeFirstSheet->setCellValue('A3', "Total Records: ");
            $activeFirstSheet->getStyle('C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $activeFirstSheet->setCellValue('C3', $recordCount);
            $activeFirstSheet->mergeCells('A3:B3');
            $activeFirstSheet->mergeCells('C3:E3');
            $activeFirstSheet->setCellValue('A4', "Date: ");
            $activeFirstSheet->setCellValue('C4', date("d-M-Y"));
            $activeFirstSheet->mergeCells('A4:B4');
            $activeFirstSheet->mergeCells('C4:E4');
            //$activeFirstSheet->setCellValue('A5', "Date Filters: From: ".$dteFltrStDte." To: ".$dteFltrEdDte);
            //$activeFirstSheet->mergeCells('A5:E5');

            foreach( $columnHeaders as $columnHeader => $headerValue ){
                $activeFirstSheet->getColumnDimension("{$columnHeader}")->setAutoSize(true);
                $activeFirstSheet->setCellValue( "{$columnHeader}5", $headerValue );
            }

            $activeFirstSheet->getStyle('A5:E5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('000000');
			
		    $activeFirstSheet->getStyle('A5:E5')->getFont()->getColor()->setRGB ('EEEEEE');
			$activeFirstSheet->getStyle("A5:E5")->getFont()->setBold( true );		
			$excel->getSheetByName("Group");

            $sno = 6;
            foreach($grpList as $grp){
                
                $activeFirstSheet->setCellValue('A'.$sno, ($sno-5));
                $activeFirstSheet->setCellValue('B'.$sno, $grp->GroupName);
                $activeFirstSheet->setCellValue('C'.$sno, $grp->GroupStatusDetail);
                $activeFirstSheet->setCellValue('D'.$sno, $grp->createdByName);
                $activeFirstSheet->setCellValue('E'.$sno, $grp->createdOn);
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
            $activeFirstSheet->getStyle('A1:E'.($sno-1))->applyFromArray($styleArray);

            $excelWriter = new Xlsx($excel);
            $tempFile = tempnam(File::sysGetTempDir(), 'phpxltmp');
            $tempFile = $tempFile ?: __DIR__ . '/temp.xlsx';
            $excelWriter->save($tempFile);

            // For Excel2007 and above .xlsx files   
            $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response = $response->withHeader('Content-Disposition', 'attachment; filename="group_management.xlsx"');
            $stream = fopen($tempFile, 'r+');
            $response->getBody()->write(fread($stream, (int)fstat($stream)['size']));
            return $response;


        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }

    public function update($input, $groupid, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            if(empty($groupid)){
                throw new AdmnGrpsException('Group Id Empty', 201);
            }

            $data = json_decode(json_encode($input), false);
            $groupName = isset($data->groupName)?$data->groupName : '';

            if(empty($groupName)){
                throw new AdmnGrpsException('Group Name Empty', 201);
            }

            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnGrpModel->update($groupid, $groupName, $auditBy, $hotelid, $brandid);
            $objLogger->info("Insert Status : ".$insStatus);
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $insStatus;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }

    public function create($input, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            $data = json_decode(json_encode($input), false);
            $groupName = isset($data->groupName)?$data->groupName : '';

            if(empty($groupName)){
                throw new AdmnGrpsException('Group Name Empty', 201);
            }

            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnGrpModel->create($groupName, $auditBy, $hotelid, $brandid);
            $objLogger->info("Insert Status : ".$insStatus);
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $insStatus;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }

    public function getGrpOne($groupid, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository ================");
        try{
  
            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $grpData = $admnGrpModel->getGrpOne($groupid, $auditBy, $hotelid, $brandid);
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $grpData;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }

    public function getGrpList($input, $auditBy, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            $data            = json_decode(json_encode($input), false);
            $searchValue     = isset($data->searchValue)?$data->searchValue : '';
            //$itemperPage     = isset($data->pageSize)?$data->pageSize  : '10';
            //$currentPage     = isset($data->pageIndex)?$data->pageIndex  : '0'; 
            //$sortName        = isset($data->sortName)?$data->sortName  : ''; 
            //$sortOrder       = isset($data->sortOrder)?$data->sortOrder  : 'DESC'; 
            
            //$limitFrom       = ($currentPage) * $itemperPage; 

            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $grplst = $admnGrpModel->getGrpList($auditBy, $hotelid, $searchValue);
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $grplst;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }

    public function getMenuRightStatus1($auditBy, $menuid){

        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$auditBy, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository ================");
        try{
            
            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $status = $admnGrpModel->getMenuRightStatus($auditBy, $menuid);
            $objLogger->info("Status : ".$status);
            if(empty($status)){
                throw new AdmnGrpsException('Invalid Access', 201);
            }
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $status;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 201);
            }
        }
    }
}
