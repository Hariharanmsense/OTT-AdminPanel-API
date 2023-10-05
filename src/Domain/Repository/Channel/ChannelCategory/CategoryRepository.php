<?php

declare(strict_types=1);

namespace App\Domain\Repository\Channel\ChannelCategory;

use App\Domain\Service\Channel\ChannelCategory\CategoryService;
use App\Exception\Channel\ChannelException;
use App\Model\ChannelCategoryModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Stmt\Catch_;

class CategoryRepository extends BaseRepository implements CategoryService
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

    public function excel($response, $input, $auditBy,$userName){

        $objLogger = $this->loggerFactory->getFileObject('HotelRepository_'.$auditBy, 'excel');
        $objLogger->info("======= Start Hotel Repository (Excel) ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
            $dteFltrStDte = date("d-M-Y H:i:s");
	        $dteFltrEdDte = date("d-M-Y H:i:s");
         
           
           

           $chnllist = $this->ViewCategorylist($input,$userName,'EXPORTEXCEL');                              
            if(empty($chnllist)){
                throw new ChannelException('No Records Found', 201);
            }

            $recordCount =  count($chnllist); 

            $columnHeaders = array("A"=>"SNo", "B"=>"Hotel Name", "C"=>"Category Name", "D"=>"Assigned Channnels", 
            'E'=> 'Channel No','F'=> 'Ip Address','G'=> 'Port No','H'=> 'Frequency','I' =>'Channel Feed','J' =>'Created By', "K"=>"
            Created On");
            $excel = new Spreadsheet();
            $activeFirstSheet = $excel->getActiveSheet();
            $activeFirstSheet->setTitle("Channel Category Details");
            $excel->setActiveSheetIndex(0);
            $activeFirstSheet->setCellValue('A1', "Report Name:");
            $activeFirstSheet->setCellValue('C1', "Channel_Category_Report");
            $activeFirstSheet->mergeCells('A1:B1');
            $activeFirstSheet->mergeCells('C1:K1');
            $activeFirstSheet->setCellValue('A2', "Who Downloaded:");
            $activeFirstSheet->setCellValue('C2', $userName);
            $activeFirstSheet->mergeCells('A2:B2');
            $activeFirstSheet->mergeCells('C2:K2');
            $activeFirstSheet->setCellValue('A3', "Total Records: ");
            $activeFirstSheet->getStyle('C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $activeFirstSheet->setCellValue('C3', $recordCount);
            $activeFirstSheet->mergeCells('A3:B3');
            $activeFirstSheet->mergeCells('C3:K3');
            $activeFirstSheet->setCellValue('A4', "Date: ");
            $activeFirstSheet->setCellValue('C4', date("d-M-Y"));
            $activeFirstSheet->mergeCells('A4:B4');
            $activeFirstSheet->mergeCells('C4:K4');
            //$activeFirstSheet->setCellValue('A5', "Date Filters: From: ".$dteFltrStDte." To: ".$dteFltrEdDte);
            //$activeFirstSheet->mergeCells('A5:E5');

            foreach( $columnHeaders as $columnHeader => $headerValue ){
                $activeFirstSheet->getColumnDimension("{$columnHeader}")->setAutoSize(true);
                $activeFirstSheet->setCellValue( "{$columnHeader}5", $headerValue );
            }

            $activeFirstSheet->getStyle('A5:K5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('000000');
			
		    $activeFirstSheet->getStyle('A5:K5')->getFont()->getColor()->setRGB ('EEEEEE');
			$activeFirstSheet->getStyle("A5:K5")->getFont()->setBold( true );		
			$excel->getSheetByName("Channel Category Details");

            $sno = 6;
            foreach($chnllist as $grp){

                if($grp->channelfeedtype == 1){
                    $activeFirstSheet->getColumnDimension('F')->setVisible(true);
                    $activeFirstSheet->getColumnDimension('G')->setVisible(true);
                    $activeFirstSheet->getColumnDimension('H')->setVisible(false);
                }elseif($grp->channelfeedtype == 3){
                    $activeFirstSheet->getColumnDimension('F')->setVisible(false);
                    $activeFirstSheet->getColumnDimension('G')->setVisible(false);
                    $activeFirstSheet->getColumnDimension('H')->setVisible(true);
                }else{
                    $activeFirstSheet->getColumnDimension('F')->setVisible(false);
                    $activeFirstSheet->getColumnDimension('G')->setVisible(false);
                    $activeFirstSheet->getColumnDimension('H')->setVisible(false);
                }
                
                $activeFirstSheet->setCellValue('A'.$sno, ($sno-5));
                $activeFirstSheet->setCellValue('B'.$sno, $grp->hotelname);
                $activeFirstSheet->setCellValue('C'.$sno, $grp->categoryname);
                $activeFirstSheet->setCellValueExplicit('D'.$sno,($grp->channelname),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $activeFirstSheet->setCellValueExplicit('E'.$sno,($grp->channelno),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $activeFirstSheet->setCellValueExplicit('F'.$sno,($grp->channelip),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $activeFirstSheet->setCellValueExplicit('G'.$sno,($grp->channelport),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $activeFirstSheet->setCellValueExplicit('H'.$sno,($grp->channelfrquency),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $activeFirstSheet->setCellValue('I'.$sno,($grp->channelfeedname));
                $activeFirstSheet->setCellValue('J'.$sno, $grp->createdBy);
                $activeFirstSheet->setCellValue('K'.$sno, $grp->createdOn);

               // $activeFirstSheet->mergeCells('A1:C3', Worksheet::MERGE_CELL_CONTENT_MERGE);
                //$activeFirstSheet->setCellValue('F'.$sno, $grp->mobileno);
               
                // $activeFirstSheet->setCellValue('G'.$sno, $grp->address);
                // $activeFirstSheet->setCellValue('H'.$sno, $grp->statusDetail);
                // $activeFirstSheet->setCellValue('I'.$sno, $grp->createdOn);
                // //$activeFirstSheet->setCellValue('J'.$sno, $grp->hotelcode);
                
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

            $activeFirstSheet->getStyle('A1:K'.($sno-1))->applyFromArray($styleArray);

            $excelWriter = new Xlsx($excel);
            $tempFile = tempnam(File::sysGetTempDir(), 'phpxltmp');
            $tempFile = $tempFile ?: __DIR__ . '/temp.xlsx';
            $excelWriter->save($tempFile);

            // For Excel2007 and above .xlsx files   
            $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response = $response->withHeader('Content-Disposition', 'attachment; filename="channel_category_details.xlsx"');
            $stream = fopen($tempFile, 'r+');
            $response->getBody()->write(fread($stream, (int)fstat($stream)['size']));
            return $response;


        }
        catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException('Invalid Access', 201);
            }
        }
    }

    public function avilablechannel($inputdata,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('CategoryRepository_'.$userName.'.log', 'avilablechannel');
          
        try{

            $objLogger->info("======= Start Channel Category Repository (avilablechannel) ================");            
        
            $hotelid = isset($inputdata['hotel_id'])?$inputdata['hotel_id']:"0";
            $categoryid = isset($inputdata['categoryid'])?$inputdata['categoryid']:"";

            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
            if(empty($hotelid)){
                throw new ChannelException('Hotel id required', 201);
            }
            if($categoryid == ''){
                throw new ChannelException('Category required', 201);
            }
           
            
            $ChannelCategoryModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
			
            $viewChannel = $ChannelCategoryModel->availablechnlcategory($hotelid,$categoryid,$userid,$userName);
           

            $objLogger->info("======= END Channel Category Repository (avilablechannel) ================");
			

            return $viewChannel;
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->info("======= END Channel Category Repository (avilablechannel) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Channel credentials invalid', 201);
            }
        }
    }
    public function assignedchannellist($inputdata,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('CategoryRepository_'.$userName.'.log', 'assignedchannellist');
        
        try{
            $objLogger->info("======= Start Channel Category Repository (assignedchannellist) ================");     
            
        
            $hotelid = isset($inputdata['hotel_id'])?$inputdata['hotel_id']:"0";
            $categoryid = isset($inputdata['categoryid'])?$inputdata['categoryid']:"0";

            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
            if(empty($hotelid)){
                throw new ChannelException('Hotel id required', 201);
            }
            if(empty($categoryid)){
                throw new ChannelException('Category required', 201);
            }
           
            
            $ChannelCategoryModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
			
            $viewChannel = $ChannelCategoryModel->assigncategorymodel($hotelid,$categoryid,$userid,$userName);
           

            $objLogger->info("======= END Channel Category Repository (assignedchannellist) ================");
			

            return $viewChannel;
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->info("======= END Channel Category Repository (assignedchannellist) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Channel credentials invalid', 201);
            }
        }
    }
    public function ViewCategorylist($inputdata,$userName,$action){
        
        $objLogger = $this->loggerFactory->getFileObject('CategoryRepository_'.$userName.'.log', 'ViewCategorylist');
           
        try{

            $objLogger->info("======= Start Channel Category Repository (ViewCategorylist) ================"); 
            //print_R($inputdata);die();
            $input = json_decode(json_encode($inputdata),false);
            $userid = isset($input->decoded->id)?$input->decoded->id:"";
            $hotelid = isset($input->hotel_id)?$input->hotel_id:"0";
            $menuid = isset($input->menuId)?$input->menuId:"0";
            $search_value = isset($input->searchValue)?$input->searchValue:"";
            $action = !empty($action)? $action:'';

            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
            /*if($menuid !=''){
                throw new ChannelException('Menu Id required', 201);
            }*/
           
            
            $ChannelCategoryModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
			
            $viewChannel = $ChannelCategoryModel->viewCategoryllist($hotelid,$menuid,$search_value,$userid,$userName,$action);

            $objLogger->info("======= END Channel Category Repository (ViewCategorylist) ================");
			

            return $viewChannel;
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->info("======= END Channel Category Repository (ViewCategorylist) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Channel credentials invalid', 201);
            }
        }
    }
    public function create($inputdata,$userName){
        $objLogger = $this->loggerFactory->getFileObject('CategoryRepository_'.$userName.'.log', 'create');
        
        try{
            $objLogger->info("======= Start Channel Category Repository (create) ================");  

            $hotelid = isset($inputdata['hotel_id'])?($inputdata['hotel_id']):"0";
            $categoryname = isset($inputdata['categoryname'])?addslashes($inputdata['categoryname']):"";
            $assignList = isset($inputdata['assignList'])?($inputdata['assignList']):"";
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";
            $menuid = isset($inputdata['menuId'])?$inputdata['menuId']:"0";

            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }

            if($hotelid == 0){
                throw new ChannelException('Hotel required', 201);
            }
            if(empty($categoryname)){
                throw new ChannelException('Category Name id required', 201);
            }
            if($menuid == 0){
                throw new ChannelException('Menu Id required', 201);
            }
 
            $AddChannelCategoryModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
            $user = $AddChannelCategoryModel->create($hotelid,$assignList,$categoryname,$menuid,$userid,$userName);
            $objLogger->info("======= End Channel Category Repository (create) ================");
            return $user;
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->info("======= End Channel Category Repository (create) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Channel credentials invalid', 201);
            }
        }
    }


    public function getOneCategory($categoryid,$userid,$userName){

        $objLogger = $this->loggerFactory->getFileObject('CategoryRepository_'.$userName.'.log', 'getOneCategory');
        $objLogger->info("======= Start Channel Category Repository (getOneCategory) ================");  
    
        try{    

        $editModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
        $edituser = $editModel->getoneModel($categoryid,$userid,$userName);
        $objLogger->info("======= END Channel Category Repository (getOneCategory) ================");
        return $edituser;

    } catch (ChannelException $ex) {

        $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
        $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
        $objLogger->info("======= END Channel Category Repository (getOneCategory) ================");
        if(!empty($ex->getMessage())){
            throw new ChannelException($ex->getMessage(), 201);
        }
        else {
            throw new ChannelException('Channel credentials invalid', 201);
        }
    }
}


    public function update($inputdata,$categoryid,$userid){
            $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
            $objLogger = $this->loggerFactory->getFileObject('CategoryRepository_'.$userName.'.log', 'update');
            $objLogger->info("======= Start Channel Category Repository (update) ================");  
        
            try{
            
            //$UpdataData = new \stdClass();
            
            $hotelid = isset($inputdata['hotel_id'])?($inputdata['hotel_id']):"0";
            $categoryname = isset($inputdata['categoryname'])?addslashes($inputdata['categoryname']):"";
            $assignList = isset($inputdata['assignList'])?($inputdata['assignList']):"";
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";
            $menuid = isset($inputdata['menuId'])?$inputdata['menuId']:"0";
            if($hotelid == 0){
                throw new ChannelException('Hotel required', 201);
            }
            if(empty($categoryname)){
                throw new ChannelException('Category Name id required', 201);
            }
            if($menuid == 0){
                throw new ChannelException('Menu Id required', 201);
            }
            
            
            $ChannelCategoryModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
            $updateuser = $ChannelCategoryModel->update($categoryid,$assignList,$hotelid,$categoryname,$menuid,$userid,$userName);
            $objLogger->info("======= END Channel Category Repository (update) ================");
            return $updateuser;
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->info("======= END Channel Category Repository (update) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Channel credentials invalid', 201);
            }
        }
    }

    public function delete($categoryid,$userid,$userName){
        
        $objLogger = $this->loggerFactory->getFileObject('CategoryRepository_'.$userName.'.log', 'delete');
        $objLogger->info("======= Start Channel Category Repository (delete) ================");  
    
        try{

            if($categoryid == 0){
                throw new ChannelException('Category Id required', 201);
            }

            $ChannelCategoryModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
            $deleteDetails = $ChannelCategoryModel->delete($categoryid,$userid,$userName);
            $objLogger->info("======= END Channel Category Repository (delete) ================");
            //$delteData->userData = $deleteDetails;
            return $deleteDetails;
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->info("======= END Channel Category Repository (delete) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Channel credentials invalid', 201);
            }
        }
    }

    function validChr($str) {
        return preg_match('/^[\W\d]+$/',$str);
    }

}

