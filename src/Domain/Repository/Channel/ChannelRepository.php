<?php

declare(strict_types=1);

namespace App\Domain\Repository\Channel;

use App\Domain\Service\Channel\ChannelService;
use App\Exception\Channel\ChannelException;
use App\Model\ChannelModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Stmt\Catch_;

class ChannelRepository extends BaseRepository implements ChannelService
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

    public function excel($response,$input, $userid,$userName){

        $objLogger = $this->loggerFactory->getFileObject('ChannelRepository_'.$userName, 'excel');
        $objLogger->info("======= Start Channel Repository (Excel) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $dteFltrStDte = date("d-M-Y H:i:s");
	        $dteFltrEdDte = date("d-M-Y H:i:s");
            
            if(empty($userid)){
                throw new ChannelException('Invalid Access', 201);
            }
            $whoModified = $userName;

            $chnllst = $this->ViewChannellist($input, $userid,$userName);                              
            if(empty($chnllst)){
                throw new ChannelException('No Records Found', 201);
            }

            $recordCount =  count($chnllst); 

            $columnHeaders = array("A"=>"SNo", "B"=>"Channel Name", "C"=>"Channel Logo", 'D' =>'Created On');
            $excel = new Spreadsheet();
            $activeFirstSheet = $excel->getActiveSheet();
            $activeFirstSheet->setTitle("Channel Details");
            $excel->setActiveSheetIndex(0);
            $activeFirstSheet->setCellValue('A1', "Report Name:");
            $activeFirstSheet->setCellValue('C1', "Channel_Details_Report");
            $activeFirstSheet->mergeCells('A1:B1');
            $activeFirstSheet->mergeCells('C1:D1');
            $activeFirstSheet->setCellValue('A2', "Who Downloaded:");
            $activeFirstSheet->setCellValue('C2', $whoModified);
            $activeFirstSheet->mergeCells('A2:B2');
            $activeFirstSheet->mergeCells('C2:D2');
            $activeFirstSheet->setCellValue('A3', "Total Records: ");
            $activeFirstSheet->getStyle('C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $activeFirstSheet->setCellValue('C3', $recordCount);
            $activeFirstSheet->mergeCells('A3:B3');
            $activeFirstSheet->mergeCells('C3:D3');
            $activeFirstSheet->setCellValue('A4', "Date: ");
            $activeFirstSheet->setCellValue('C4', date("d-M-Y"));
            $activeFirstSheet->mergeCells('A4:B4');
            $activeFirstSheet->mergeCells('C4:D4');
            //$activeFirstSheet->setCellValue('A5', "Date Filters: From: ".$dteFltrStDte." To: ".$dteFltrEdDte);
            //$activeFirstSheet->mergeCells('A5:E5');

            foreach( $columnHeaders as $columnHeader => $headerValue ){
                $activeFirstSheet->getColumnDimension("{$columnHeader}")->setAutoSize(true);
                $activeFirstSheet->setCellValue( "{$columnHeader}5", $headerValue );
            }

            $activeFirstSheet->getStyle('A5:D5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('000000');
			
		    $activeFirstSheet->getStyle('A5:D5')->getFont()->getColor()->setRGB ('EEEEEE');
			$activeFirstSheet->getStyle("A5:D5")->getFont()->setBold( true );		
			$excel->getSheetByName("Channel Details");

            $sno = 6;
            foreach($chnllst as $grp){
                $objDrawing = new Drawing();
                $gdImage = "../".$grp->channellogo;
                //$scan = scandir($gdImage);
                $imgpath = '';
                if(file_exists($gdImage)){
                    $imgpath="../".$grp->channellogo;
                    list($imageWidth, $imageHeight) = getimagesize($imgpath);
                    $objDrawing->setName('Sample image');
                    $objDrawing->setDescription('Sample image');
                    $objDrawing->setPath($imgpath);
                  
                    $objDrawing->setCoordinates('C'.$sno); // Set the cell where the image will be placed
                    $objDrawing->setWidth(40);       // Set the width of the image
                    $objDrawing->setHeight(40);
    
                    $objDrawing->setResizeProportional(true);
                    $imageX = 10;//abs(intval((40 - $imageWidth) / 2));
                    $imageY = 8;//abs(intval((40 - $imageHeight) / 2));
                    $objDrawing->setOffsetX($imageX);
                    $objDrawing->setOffsetY($imageY);
                    $objDrawing->setWorksheet($activeFirstSheet);

                }else{
                    $activeFirstSheet->setCellValue('C'.$sno, '');
                }

                $activeFirstSheet->setCellValue('A'.$sno, ($sno-5));
                $activeFirstSheet->setCellValue('B'.$sno, $grp->channelname);
                $activeFirstSheet->getColumnDimension('C')->setWidth(40); // Set column width (adjust as needed)
                $activeFirstSheet->getRowDimension($sno)->setRowHeight(40);
                $activeFirstSheet->setCellValue('D'.$sno, $grp->createdOn);
                //$activeFirstSheet->setCellValue('E'.$sno, $grp->custmail);
                //$activeFirstSheet->setCellValue('F'.$sno, $grp->mobileno);
                // $activeFirstSheet->setCellValueExplicit('F'.$sno,trim($grp->mobileno),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                // $activeFirstSheet->setCellValue('G'.$sno, $grp->address);
                // $activeFirstSheet->setCellValue('H'.$sno, $grp->statusDetail);
                // $activeFirstSheet->setCellValue('I'.$sno, $grp->createdOn);
                //$activeFirstSheet->setCellValue('J'.$sno, $grp->Channelcode);
                
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

            $activeFirstSheet->getStyle('A1:D'.($sno-1))->applyFromArray($styleArray);

            $excelWriter = new Xlsx($excel);
            $tempFile = tempnam(File::sysGetTempDir(), 'phpxltmp');
            $tempFile = $tempFile ?: __DIR__ . '/temp.xlsx';
            $excelWriter->save($tempFile);

            // For Excel2007 and above .xlsx files   
            $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response = $response->withHeader('Content-Disposition', 'attachment; filename="channel_details.xlsx"');
            $stream = fopen($tempFile, 'r+');
            $response->getBody()->write(fread($stream, (int)fstat($stream)['size']));
            $objLogger->info("======= End Channel Repository  (Excel)================");
            return $response;


        }
        catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Channel Repository  (Excel)================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException('Invalid Access', 201);
            }
        }
    }
    /*View Channel list details */

    public function ViewChannellist($inputData,$userid,$userName){

        
        $objLogger = $this->loggerFactory->getFileObject('ChannelRepository_'.$userName, 'ViewChannellist');  

        try{
            $objLogger->info("======= Start Channel Repository (ViewChannellist) ================"); 
            $input  = json_decode(json_encode($inputData),false);
            $search_value = isset($input->searchValue)?$input->searchValue :'';
            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
           
            
            $ChannelModel = new ChannelModel($this->loggerFactory, $this->dBConFactory);
            $viewChannels = $ChannelModel->viewchannellist($search_value,$userid,$userName);
            $objLogger->info("======= End Channel Repository (ViewChannellist) ================");
            return $viewChannels;
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->info("======= End Channel Repository (ViewChannellist) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Channel credentials invalid', 201);
            }
        }
    }
    public function create($inputdata,$channelimg,$userid,$userName){
              
        
        $objLogger = $this->loggerFactory->getFileObject('ChannelRepository_'.$userName, 'create');  
        try{
            
            $objLogger->info("======= Start Channel Category Repository (create) ================"); 
            //print_r($channelimg);die();

            $userid = isset($inputdata->decoded->id)?$inputdata->decoded->id:"";
           
            $channelname = isset($inputdata->channelname)?($inputdata->channelname):"";
            //$channelcategory = isset($inputdata->categoryid)?$inputdata->categoryid:"0";
            $channelimg = isset($channelimg)? ($channelimg):"";
            $objLogger->info("Inputs:".json_encode($inputdata));


            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
            if(empty($channelname)){
                throw new ChannelException('Channel Name required', 201);
            }

            if(empty($channelimg)){
                throw new ChannelException('Channel image required', 201);
            }
            
            
            $AddChannelModel = new ChannelModel($this->loggerFactory, $this->dBConFactory);
            
            $user = $AddChannelModel->createchannel($channelname, $channelimg,$userid,$userName);
            $objLogger->info("======= End Channel Repository (create) ================");
            return $user;
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->info("======= End Channel Repository (create) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Channel credentials invalid', 201);
            }
        }
    }


    public function getOneChannel($channelid, $userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('ChannelRepository_'.$userName, 'getOneChannel');
        
        try{
            $objLogger->info("======= Start Channel Repository (getOneChannel) ================");
  
            $getoneChannelModel = new ChannelModel($this->loggerFactory, $this->dBConFactory);
            $grpData = $getoneChannelModel->getoneModel($channelid, $userid,$userName);
            $objLogger->info("======= End Channel Repository (getOneChannel) ================");
            return $grpData;
        }
        catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Channel Repository (getOneChannel) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException('Invalid Access', 401);
            }
        }
    }


    public function update($inputdata,$channelid,$userid,$userName,$channelimg){
        
        $objLogger = $this->loggerFactory->getFileObject('ChannelRepository_'.$userName, 'update');
       
       
        try{

            $objLogger->info("======= Start Channel Repository (update)================");
            $objLogger->info("Input Data : ".json_encode($inputdata));

            $channelname = isset($inputdata->channelname)?addslashes($inputdata->channelname):"";
            $channelimg = isset($channelimg)? ($channelimg):"";
			$channelid = isset($channelid)?$channelid:'0';
               


            if($channelid == 0){
                throw new ChannelException('Channel id required', 201);
            }

            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
            if(empty($channelname)){
                throw new ChannelException('Channel Name required', 201);
            }

            /*if(empty($channelimg)){
                throw new ChannelException('Channel image required', 201);
            }*/

            $updateChannel = new ChannelModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $updateChannel->update($channelname, $channelimg,$channelid,$userid,$userName);
            
            $objLogger->info("Update Status : ".json_encode($insStatus));
            $objLogger->info("======= End Channel Repository (update) ================");
            return $insStatus;
        }
        catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Channel Repository (update)================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException('Invalid Access', 401);
            }
        }
    }

    public function delete($channelid, $userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('ChannelRepository_'.$userName, 'delete');
        
        try{
            $objLogger->info("======= Start Channel Repository (delete)================");
            $objLogger->info("Channel Id: ".$channelid);
            $getoneChannelModel = new ChannelModel($this->loggerFactory, $this->dBConFactory);
            $grpData = $getoneChannelModel->deleteModel($channelid, $userid,$userName);
            $objLogger->info("======= End Channel Repository (delete)================");
            return $grpData;
        }
        catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            
            $objLogger->info("======= End Channel Repository (delete)================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException('Invalid Access', 401);
            }
        }
    }
   
    function validChr($str) {
        return preg_match('/^[\W\d]+$/',$str);
    }

}

