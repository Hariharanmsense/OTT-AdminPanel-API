<?php

declare(strict_types=1);

namespace App\Domain\Repository\Hotel;

use App\Domain\Service\Hotel\HotelService;
use App\Exception\Hotel\HotelException;
use App\Model\HotelModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use PhpParser\Node\Stmt\Catch_;

class HotelRepository extends BaseRepository implements HotelService
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
	
	 public function gnrteHtlCde($input, $brandid, $hotelname, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository (Excel) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{

            $Hotelmodel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $suggestions = $Hotelmodel->gnrteHtlCde($auditBy, $brandid, $hotelname);
            return $suggestions;
        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function excel($response, $input, $auditBy){

        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository (Excel) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $dteFltrStDte = date("d-M-Y H:i:s");
	        $dteFltrEdDte = date("d-M-Y H:i:s");
            $auditData = $this->getUserName($auditBy);
            if(empty($auditData)){
                throw new HotelException('Invalid Access', 201);
            }
            $whoModified = $auditData->userName;
            $htllst = $this->Viewhotellist($input);                              
            if(empty($htllst)){
                throw new HotelException('No Records Found', 201);
            }

            $recordCount =  count($htllst); 

            $columnHeaders = array("A"=>"SNo", "B"=>"Brand Name", "C"=>"Hotel Name", 'D' =>'SPOC Name', "E"=>"Email", "F"=>"Mobile No", 'G' =>'Address',
            "H"=>"Status", "I"=>"Created On","J"=>"Hotel Code");
            $excel = new Spreadsheet();
            $activeFirstSheet = $excel->getActiveSheet();
            $activeFirstSheet->setTitle("Hotel Details");
            $excel->setActiveSheetIndex(0);
            $activeFirstSheet->setCellValue('A1', "Report Name:");
            $activeFirstSheet->setCellValue('C1', "Hotel_Details_Report");
            $activeFirstSheet->mergeCells('A1:B1');
            $activeFirstSheet->mergeCells('C1:J1');
            $activeFirstSheet->setCellValue('A2', "Who Downloaded:");
            $activeFirstSheet->setCellValue('C2', $whoModified);
            $activeFirstSheet->mergeCells('A2:B2');
            $activeFirstSheet->mergeCells('C2:J2');
            $activeFirstSheet->setCellValue('A3', "Total Records: ");
            $activeFirstSheet->getStyle('C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $activeFirstSheet->setCellValue('C3', $recordCount);
            $activeFirstSheet->mergeCells('A3:B3');
            $activeFirstSheet->mergeCells('C3:J3');
            $activeFirstSheet->setCellValue('A4', "Date: ");
            $activeFirstSheet->setCellValue('C4', date("d-M-Y"));
            $activeFirstSheet->mergeCells('A4:B4');
            $activeFirstSheet->mergeCells('C4:J4');
            //$activeFirstSheet->setCellValue('A5', "Date Filters: From: ".$dteFltrStDte." To: ".$dteFltrEdDte);
            //$activeFirstSheet->mergeCells('A5:E5');

            foreach( $columnHeaders as $columnHeader => $headerValue ){
                $activeFirstSheet->getColumnDimension("{$columnHeader}")->setAutoSize(true);
                $activeFirstSheet->setCellValue( "{$columnHeader}5", $headerValue );
            }

            $activeFirstSheet->getStyle('A5:J5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('000000');
			
		    $activeFirstSheet->getStyle('A5:J5')->getFont()->getColor()->setRGB ('EEEEEE');
			$activeFirstSheet->getStyle("A5:J5")->getFont()->setBold( true );		
			$excel->getSheetByName("Hotel Details");

            $sno = 6;
            foreach($htllst as $grp){
                
                $activeFirstSheet->setCellValue('A'.$sno, ($sno-5));
                $activeFirstSheet->setCellValue('B'.$sno, $grp->brandname);
                $activeFirstSheet->setCellValue('C'.$sno, $grp->hotelname);
                $activeFirstSheet->setCellValue('D'.$sno, $grp->spocname);
                $activeFirstSheet->setCellValue('E'.$sno, $grp->custmail);
                //$activeFirstSheet->setCellValue('F'.$sno, $grp->mobileno);
                $activeFirstSheet->setCellValueExplicit('F'.$sno,trim($grp->mobileno),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $activeFirstSheet->setCellValue('G'.$sno, $grp->address);
                $activeFirstSheet->setCellValue('H'.$sno, $grp->statusDetail);
                $activeFirstSheet->setCellValue('I'.$sno, $grp->createdOn);
                $activeFirstSheet->setCellValue('J'.$sno, $grp->hotelcode);
                
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

            $activeFirstSheet->getStyle('A1:J'.($sno-1))->applyFromArray($styleArray);

            $excelWriter = new Xlsx($excel);
            $tempFile = tempnam(File::sysGetTempDir(), 'phpxltmp');
            $tempFile = $tempFile ?: __DIR__ . '/temp.xlsx';
            $excelWriter->save($tempFile);

            // For Excel2007 and above .xlsx files   
            $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response = $response->withHeader('Content-Disposition', 'attachment; filename="hotel_details.xlsx"');
            $stream = fopen($tempFile, 'r+');
            $response->getBody()->write(fread($stream, (int)fstat($stream)['size']));
            return $response;


        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function bwStatus($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository (BW activeOrDeactive) ================");
        try{

            $hotelModel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $hotelModel->bwStatus($hotelId, $auditBy);
            $objLogger->info("Update Status : ".$insStatus);
            $objLogger->info("======= End Hotel Repository (BW activeOrDeactive) ================");
            return $insStatus;

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository (BW activeOrDeactive) ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function alertEmailStatus($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository (Alert Email activeOrDeactive) ================");
        try{

            $hotelModel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $hotelModel->alertEmailStatus($hotelId, $auditBy);
            $objLogger->info("Update Status : ".$insStatus);
            $objLogger->info("======= End Hotel Repository (Alert Email activeOrDeactive) ================");
            return $insStatus;

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository (Alert Email activeOrDeactive) ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function icmpStatus($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository (ICMP activeOrDeactive) ================");
        try{

            $hotelModel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $hotelModel->icmpStatus($hotelId, $auditBy);
            $objLogger->info("Update Status : ".$insStatus);
            $objLogger->info("======= End Hotel Repository (ICMP activeOrDeactive) ================");
            return $insStatus;

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository (ICMP activeOrDeactive) ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function activeOrDeactive($hotelId, $auditBy){

        $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository (activeOrDeactive) ================");
        try{

            $hotelModel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $hotelModel->activeOrDeactive($hotelId, $auditBy);
            $objLogger->info("Update Status : ".$insStatus);
            $objLogger->info("======= End Hotel Repository (activeOrDeactive) ================");
            return $insStatus;

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository (activeOrDeactive) ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }
    public function Viewhotellist($inputData){


        $userName = isset($inputData->decoded->userName)?$inputData->decoded->userName:"";    
        $objLogger = $this->loggerFactory->getFileObject('Hotelmodel_'.$userName.'.log', 'Viewhotellist');
        $objLogger->info("======= Start Hotel Repository (Viewhotellist) ================");
        try{
           
            $userid = isset($inputData->decoded->id)?$inputData->decoded->id:"";
            $brandid = isset($inputData->decoded->brandId)?$inputData->decoded->brandId:"0";
            $hotelid = isset($inputData->hotel_id)?$inputData->hotel_id:"0";
            // $hotelname = isset($inputData['hotelname'])?$inputData['hotelname']:"";
           
            // if($hotelid == '')
            //     throw new HotelException('Hotel Id required', 201);

            if(empty($userid)){
                throw new HotelException('User id required', 201);
            }
           
            
            $Hotelmodel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $viewHotels = $Hotelmodel->ViewhotelList($hotelid,$userid,$userName,$brandid);
            return $viewHotels;
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Hotel credentials invalid', 201);
            }
        }
    }
    public function create($inputdata){
       
        $userName = isset($inputdata->decoded->userName)?$inputdata->decoded->userName:"";
        
        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('BrandRepository');
        try{

            $userid = isset($inputdata->decoded->id)?$inputdata->decoded->id:"";
            $brandid = isset($inputdata->brandid)?$inputdata->brandid:"0";
            $hotelname = isset($inputdata->hotelname)?addslashes($inputdata->hotelname):"";
            $location = isset($inputdata->location)?addslashes($inputdata->location):"";
            $mail = isset($inputdata->email)?$inputdata->email:"";
            $mobileno = isset($inputdata->mobileno)?$inputdata->mobileno:"";
            $address = isset($inputdata->address) ? addslashes($inputdata->address):"";
            $spocname = isset($inputdata->spocname)?addslashes($inputdata->spocname):"";
            
          


            if(empty($userid)){
                throw new HotelException('User id required', 201);
            }
            if($brandid == 0){
                throw new HotelException('Brand required', 201);
            }
            if(empty($hotelname)){
                throw new HotelException('Hotel Name required', 201);
            }
            if(empty($location)){
                throw new HotelException('Location required', 201);
            }
            if(empty($mail)){
                throw new HotelException('Email required', 201);
            }
            if(empty($mobileno)){
                throw new HotelException('MobileNo required', 201);
            }

            if(empty($address)){
                throw new HotelException('Address required', 201);
            }
            
            
            $AddHotelmodel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            
            $user = $AddHotelmodel->createhotel($brandid,$hotelname,$location,$mail,$spocname,$mobileno,$address,$userid,$userName);
            
            //$addHoteldata->userData = $user;

            //print_r($addHoteldata);die();
            return $user;
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Hotel credentials invalid', 201);
            }
        }
    }


    public function getsinglehotel($custid, $userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$userName, 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository ================");
        try{
  
            $getonehotelmodel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $grpData = $getonehotelmodel->gethoteloneModel($custid, $userid,$userName);
            $objLogger->info("======= End Hotel Repository ================");
            return $grpData;
        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 401);
            }
        }
    }


    public function update($inputdata,$hotelid){
        $userName = isset($inputdata->decoded->userName)?$inputdata->decoded->userName:"";
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$userName, 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository ================");
        $objLogger->info("Input Data : ".json_encode($inputdata));
        try{

            
            // $data = json_decode(json_encode($inputdata), false);
             
            // $groupName = isset($data->groupName)?$data->groupName : '';

            $userid = isset($inputdata->decoded->id)?$inputdata->decoded->id:"";
            $brandid = isset($inputdata->brandid)?$inputdata->brandid:"0";
            $hotelname = isset($inputdata->hotelname)?addslashes($inputdata->hotelname):"";
            $location = isset($inputdata->location)?addslashes($inputdata->location):"";
            $mail = isset($inputdata->email)?$inputdata->email:"";
            $mobileno = isset($inputdata->mobileno)?$inputdata->mobileno:"";
            $address = isset($inputdata->address) ? addslashes($inputdata->address):"";
            $spocname = isset($inputdata->spocname)?addslashes($inputdata->spocname):"";

            //print_r($inputdata);die();
            
          


            if(empty($userid)){
                throw new HotelException('User id required', 201);
            }
            if($brandid == 0){
                throw new HotelException('Brand required', 201);
            }
            if(empty($hotelname)){
                throw new HotelException('Hotel Name required', 201);
            }
            if(empty($location)){
                throw new HotelException('Location required', 201);
            }
            if(empty($mail)){
                throw new HotelException('Email required', 201);
            }
            if(empty($mobileno)){
                throw new HotelException('MobileNo required', 201);
            }

            if(empty($address)){
                throw new HotelException('Address required', 201);
            }

            $updateHotel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $updateHotel->update($hotelid,$brandid,$hotelname,$location,$mail,$spocname,$mobileno,$address,$userid,$userName);
            
            $objLogger->info("Insert Status : ".json_encode($insStatus));
            $objLogger->info("======= End Hotel Repository ================");
            return $insStatus;
        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 401);
            }
        }
    }

    public function delete($hotelid, $userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$userName, 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository ================");
        try{
  
            $deletecustomer = new HotelModel($this->loggerFactory, $this->dBConFactory);
           
            $grpData = $deletecustomer->delete($hotelid, $userid,$userName);
            $objLogger->info("======= End Hotel Repository ================");
            return $grpData;
        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 401);
            }
        }
    }
   
    function validChr($str) {
        return preg_match('/^[\W\d]+$/',$str);
    }

}

