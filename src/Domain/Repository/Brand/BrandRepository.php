<?php

declare(strict_types=1);

namespace App\Domain\Repository\Brand;

use App\Domain\Service\Brand\BrandService;
use App\Exception\Brand\BrandException;
use App\Model\BrandModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use PhpParser\Node\Stmt\Catch_;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BrandRepository extends BaseRepository implements BrandService
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
	
	

    public function ViewbrandListRepository($inputdata){
		//print_r($inputdata);die();
        $userName = isset($inputdata->decoded->userName)?$inputdata->decoded->userName:"";
		
		
        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandRepository');
        try{

            
            $userid = isset($inputdata->decoded->id)?$inputdata->decoded->id:"";

            if(empty($userid)){
                throw new BrandException('User id required', 201);
            }
           
            
            $brandModel = new BrandModel($this->loggerFactory, $this->dBConFactory);		
			
			
            $viewBrand = $brandModel->ViewbrandList($userid,$userName);
			
            return $viewBrand;
			
        } catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), 401);
            }
            else {
                throw new BrandException('Brand credentials invalid', 401);
            }
        }
    }
    public function AddnewBrand($inputdata){
        $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandRepository');
        try{
            //$addBrandData = new \stdClass();

            $brndnme = isset($inputdata['brandname'])?addslashes($inputdata['brandname']):"";
            $shortnme = isset($inputdata['shortname'])?addslashes($inputdata['shortname']):"";
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";

            if(empty($userid)){
                throw new BrandException('User id required', 201);
            }
           
            
            $AddbrandModel = new BrandModel($this->loggerFactory, $this->dBConFactory);
            $user = $AddbrandModel->validatebrand($brndnme,$shortnme,$userid,$userName);
            //$addBrandData->userData = $user;
            return $user;
        } catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), 401);
            }
            else {
                throw new BrandException('Brand credentials invalid', 201);
            }
        }
    }


    public function EditViewRepository($brandis,$inputdata){
        $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandRepository');
    
        try{
        
        //$EditData = new \stdClass();
        $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";

        $editModel = new BrandModel($this->loggerFactory, $this->dBConFactory);
        $edituser = $editModel->EditViewModel($userid,$brandis);
        //$EditData->userData = $edituser;
        return $edituser;
    } catch (BrandException $ex) {

        $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
        $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
        $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
        if(!empty($ex->getMessage())){
            throw new BrandException($ex->getMessage(), 401);
        }
        else {
            throw new BrandException('Brand credentials invalid', 201);
        }
    }
}


    public function UpdateRepository($inputdata){
            $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
            $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandRepository');
        
            try{
            
            //$UpdataData = new \stdClass();
            
            $brndnme = isset($inputdata['brandname'])?addslashes($inputdata['brandname']):"";
            $brndid = isset($inputdata['brandid'])?$inputdata['brandid']:"";
            $shortnme = isset($inputdata['shortname'])?addslashes($inputdata['shortname']):"";
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";
            //print_r($inputdata);die();
            if(empty($userid)){
                throw new BrandException('User id required', 201);
            }
            if(empty($brndid)){
                throw new BrandException('Brand id required', 201);
            }
            if(empty($brndnme)){
                throw new BrandException('Brand Name required', 201);
            }
            if(empty($shortnme)){
                throw new BrandException('Short Name required', 201);
            }
            
            $brandModel = new BrandModel($this->loggerFactory, $this->dBConFactory);
            $updateuser = $brandModel->updateBrandModel($brndnme,$shortnme,$userid,$userName,$brndid);
            //$UpdataData->userData = $updateuser;
            return $updateuser;
        } catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), 401);
            }
            else {
                throw new BrandException('Brand credentials invalid', 201);
            }
        }
    }

    public function DeleteBrandRepository($brandid,$inputdata){
        $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandRepository');
    
        try{
        
            //$delteData = new \stdClass();
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";

            $brandModel = new BrandModel($this->loggerFactory, $this->dBConFactory);
            $deluser = $brandModel->DeleteBrandModel($userName,$brandid);
            //$delteData->userData = $deluser;
            return $deluser;
        } catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), 401);
            }
            else {
                throw new BrandException('Brand credentials invalid', 201);
            }
        }
    }

    public function actordeact($inputdata,$brandid){
		
        $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
        $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandRepository');
    
        try{
        

            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";
			
			if(empty($userid)){
				throw new BrandException('Userid empty', 201);
			}

            $brandModel = new BrandModel($this->loggerFactory, $this->dBConFactory);
            $deluser = $brandModel->Actordeactivate($brandid,$userName,$userid);
            //$delteData->userData = $deluser;
            return $deluser;
        } catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), 401);
            }
            else {
                throw new BrandException('Brand credentials invalid', 201);
            }
        }
    }
	
	    public function excel($response, $input, $auditBy, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('BrandModel_'.$auditBy, 'BrandRepository');
        $objLogger->info("======= Start Brand Repository  (EXCEL)================");
        //$objLogger->info("Input Data : ".json_encode($input));
        try{
			
            $brandList = $this->ViewbrandListRepository($input);
			
			$creatdBy = $input->decoded->userName;
			
			//print_r(gettype(brandList));die();
			
			 $objLogger->info("Input Data : ".json_encode($brandList));
			
			

            if(empty($brandList)){
                throw new BrandException('No Records Found', 201);
            }
			
			$date = date('d-M-Y');

            $columnHeaders = array("A"=>"SNo", "B"=>"Brand Name", "C"=>"Nick Name", "D"=>"CreatedBy", "E"=>"CreatedOn");
            $excel = new Spreadsheet();
		    $sheet = $excel->setActiveSheetIndex(0);
            foreach( $columnHeaders as $columnHeader => $headerValue ){
                $excel->getActiveSheet()->getColumnDimension("{$columnHeader}")->setAutoSize(true);
                $excel->getActiveSheet()->setCellValue( "{$columnHeader}3", $headerValue );
            }

            /*$excel->getActiveSheet()->getStyle('A1:E1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('000000');*/
			$excel->getActiveSheet()->getStyle('A3:E3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('000000');
			
		    //$excel->getActiveSheet()->getStyle('A1:E1')->getFont()->getColor()->setRGB ('EEEEEE');
			$excel->getActiveSheet()->getStyle('A3:E3')->getFont()->getColor()->setRGB ('EEEEEE');

			$excel->getActiveSheet()->setCellValue('A1', " Brand Management");
			$excel->getActiveSheet()->mergeCells('A1:E1');
			$excel->getActiveSheet()->setCellValue('A2', " Date: ".$date);
			$excel->getActiveSheet()->mergeCells('A2:C2');
			$excel->getActiveSheet()->setCellValue('D2', " Download By: ".$creatdBy);
			$excel->getActiveSheet()->mergeCells('D2:E2');
			
			

            $excel->getActiveSheet()->setTitle("Brand Management");
			$excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold( true );		
			$excel->getSheetByName("Brand Management");
            $sno = 4;
			$serialno = 1;
			//print_r($brandList);die();
            foreach($brandList as $brnd){
                
                $excel->getActiveSheet()->setCellValue('A'.$sno, ($serialno));
                $excel->getActiveSheet()->setCellValue('B'.$sno, $brnd->brandname);
                $excel->getActiveSheet()->setCellValue('C'.$sno, $brnd->shortname);
                $excel->getActiveSheet()->setCellValue('D'.$sno, $brnd->createdByName);
                $excel->getActiveSheet()->setCellValue('E'.$sno, $brnd->createdOn);
				$serialno++;
                $sno++;
            }

            $excelWriter = new Xlsx($excel);
            $tempFile = tempnam(File::sysGetTempDir(), 'phpxltmp');
            $tempFile = $tempFile ?: __DIR__ . '/temp.xlsx';
            $excelWriter->save($tempFile);

            // For Excel2007 and above .xlsx files   
            $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response = $response->withHeader('Content-Disposition', 'attachment; filename="brand_management.xlsx"');
            $stream = fopen($tempFile, 'r+');
            $response->getBody()->write(fread($stream, (int)fstat($stream)['size']));
			//fwrite($stream, file_get_contents(__DIR__ . '/brand_management.xlsx'));
			//rewind($stream);
            //$response->getBody()->write(fread($stream, (int)fstat($stream)['size']));
			
			//print_r($response);die();
			//return $response->withBody(new \Slim\Http\Stream($stream));
			//print_r($response);die();
            return $response;


        }
        catch (BrandException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Brand Repository (EXCEL)================");
            if(!empty($ex->getMessage())){
                throw new BrandException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new BrandException('Invalid Access', 401);
            }
        }
    }

    

    function validChr($str) {
        return preg_match('/^[\W\d]+$/',$str);
    }

}

