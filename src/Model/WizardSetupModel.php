<?php
namespace App\Model;

use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\WizardSetup\WizardSetupException;
use App\Model\DB;
use Slim\Http\UploadedFile;
use stdClass;

//use App\Model\HotelModel;

class WizardSetupModel extends BaseModel
{
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;
  

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
       
    }    

    public function updateTemplate($templateid,$hotelid,$userid,$userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_'.$userName, 'WizardSetupModel');       
        try{
            // $template = new \stdClass();
            // $tempDetails = new \stdClass();
            $action = 'UPDATE';
            $sqlQuery = "CALL SP_WizardSetup('".$action."',$templateid,$hotelid,0,$userid)";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $updateTemp = $dbObjt->getSingleDatasByObjects($sqlQuery);

            // $templateName = $this->getTemplateName($templateid,$userName); 
            
            // $template->TemplateDetails =$tempDetails;
            // $tempDetails->id = $templateid;
            // $tempDetails->name = $templateName;
            // $JsonUrl = "../public/Template/".$hotelid."/".$templateid."/".$hotelid.".json";
            // $basejsonurl = "../public/Template/".$hotelid."/".$templateid;
            // $templateData = json_encode($template,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            // if (!file_exists($JsonUrl)) {
            //     mkdir($basejsonurl,0777,true);
            //     // Create the file and write the content
            //     // $result = file_put_contents($JsonUrl, $templateData);
            // }
            // file_put_contents($JsonUrl, $templateData );

            return $updateTemp;
        }
        catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }


    public function AddFeatures($featurelist,$hotelid,$userid,$userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_'.$userName, 'WizardSetupModel');       
        try{
            
            //$list = explode(',',$featurelist);
			$list = $featurelist;
			$result ='';
			if(!empty($list)){
				for($m = 0; $m < count($list);$m++){
					$sqlQuery = "insert into temphotelfeaturelist (hotelid,featureid) values($hotelid,$list[$m])";
					$objLogger->info('Query : '.$sqlQuery); 
					$dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
					$insertion = $dbObjt->insOrUpdteOrDetQuery($sqlQuery);  
				}
				$action = 'ADD';
				$sqlQuery = "CALL SP_WizardSetup('".$action."',0,$hotelid,0,$userid)";
                $objLogger->info('Query : '.$sqlQuery); 
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $result = $dbObjt->getSingleDatasByObjects($sqlQuery);
			}
                    
            return $result;
        }
        catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

	public function createJson($hotelid,$tempid,$welcome_head, $welcome_body,$menuName,$menuUrl,$logo,$bgimg,$menuicon,$userid,$userName)
    //public function createJson($hotelid,$tempid,$welcome_body,$logo,$bgimg,$userid,$userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_'.$userName, 'WizardSetupModel');       
        try{
            $homeSreen = new \stdClass();
            $subscreen = new \stdClass();
            $parentUrl = "../public/uploads/Template/".$hotelid."/".$tempid."/";
            $baseurl = "public/uploads/Template/".$hotelid."/".$tempid."/";
            $logoUrl='';
            $backgruondimg ='';
            $imgurl='';
            if(!empty($logo)){
                
                $imgname = 'Logo';
                $logoresult = $this->moveUploadedFile($parentUrl, $logo,$imgname);
                $logoUrl=   $baseurl. $logoresult;
            }
            if(!empty($bgimg)){
                $imgname = 'Background';
                $bgersult = $this->moveUploadedFile($parentUrl, $bgimg,$imgname);
                $backgruondimg = $baseurl.$bgersult;
            }
            $menuList  = array();
            //print_R($menuName);die();
            
             if(!empty($menuName)){
                $i = 0;
                
                foreach($menuName as $mnu){
                    $submenu = new \stdClass();
                    
                    if(!empty($menuicon)){
                        $imgname = $mnu;
                        $menuiconresult = $this->moveUploadedFile($parentUrl, $menuicon[$i],$imgname);
                        $imgurl = $baseurl.$menuiconresult;
                    }
                    $submenu->title = $mnu;
                    
                    if(empty($imgurl)){
                        $submenu->img = '';
                    }else{
                        $submenu->img = $imgurl;
                    }
                   
                    if(!empty($menuUrl)){
                        $submenu->pagelink = $menuUrl[$i];
                    }else{
                        $submenu->pagelink = '';
                    }
                    
                    
                    $menuList []= $submenu;
                    $i++;
                }
            }
			$welcome_head = '';
            $homeSreen->HomePageDetails = 'HomePageDetails';
            $homeSreen->HomePageDetails= $subscreen;
            $subscreen->logo = $logoUrl;
            $subscreen->bg_img= $backgruondimg;
            $subscreen->welcome= $welcome_head;
            $subscreen->welcome_txt= $welcome_body;
            $subscreen->menu = $menuList;
            $JsonUrl = "../public/Template/".$hotelid."/".$tempid."/".$hotelid.".json";
            $basejsonurl = "../public/Template/".$hotelid."/".$tempid;
            $jsonData = json_encode($homeSreen,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            if (!file_exists($JsonUrl)) {
                mkdir($basejsonurl,0777,true);
                // Create the file and write the content
                // $result = file_put_contents($JsonUrl, $jsonData);
            }
            file_put_contents($JsonUrl, $jsonData );
            return $homeSreen;
        }
        catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }


    public function gettemplateDetailsModel($hotelid,$tempid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('WizardSetupModel_'.$userName.'.log')->createInstance('WizardSetupModel');

        try{    
            $templateDetails = new \stdClass();
            if($hotelid == 0){
                throw new WizardSetupException('Hotel Id Required', 401);
            }

            $templateQry = "select lc.brandid,(select bd.brandname from brand_details as bd where bd.id=lc.brandid) as brandname,lc.hotelname,lc.custmail,
            lc.location,lc.mobileno,lc.spocname,lc.address,lc.tempid,lc.channelfeedtype,tm.templatename,cf.channeltype
             FROM locations as lc 
             left join template as tm on tm.id = lc.tempid 
             left join channelfeed as cf on cf.id=lc.channelfeedtype where lc.id=".$hotelid;

             $featureQury = "select fl.id,fl.name  from  hotelfeaturelist as hf  left join featurelist as fl on fl.id = hf.featureid
             where hf.hotelid=".$hotelid;

            $channelQry = "select tvc.channelno,tvl.channelname,tvl.channellogo,tvc.channelip,tvc.channelport
            ,if(cg.categoryid is null,0,cg.categoryid) categoryid,if(cgc.categoryname is null,'Unknown',cgc.categoryname) 
            categoryname from tvchannels as tvc 
            left join tvchannelslist as tvl on tvc.channellistid = tvl.id
            left join channelgroup as cg on   cg.channelid=tvl.id and cg.hotelid = tvc.hotelid
            left join channelcategory as cgc on cgc.id = cg.categoryid
            where tvc.hotelid=".$hotelid;

                $objLogger->info('Query : '.$templateQry); 
                $objLogger->info('Query : '.$featureQury); 
                $objLogger->info('Query : '.$channelQry); 

                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $templatelist = $dbObjt->getMultiDatasByObjectsList($templateQry);
                $featurelist = $dbObjt->getMultiDatasByObjectsNullReturns($featureQury);
                $channelList = $dbObjt->getMultiDatasByObjectsNullReturns($channelQry);
                $HomeScreen = $this->getJsonDataModel($hotelid, $tempid,$userName);
                
                if(!empty($templatelist)){
                   
                    $templateDetails->template = $templatelist ;
                }else{
                    $templateDetails->template = '' ;
                }
                
                if(!empty($featurelist)){
                    $templateDetails->features = $featurelist ;
                }else{
                    $templateDetails->features = '';
                }
                
                if(!empty($channelList)){
                    $templateDetails->channels = $channelList ;
                }else{
                    $templateDetails->channels = '';
                }

                if(!empty($HomeScreen)){
                    $templateDetails->Homescreen = $HomeScreen ;
                }else{
                    $templateDetails->Homescreen = '';
                }
                
                //print_R($templatelist);die();
        
            return $templateDetails;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), 401);
            }
            else {
                throw new WizardSetupException('Channel credentials invalid', 401);
            }
        }
    }

    public function getJsonDataModel($hotelid, $templateid,$userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_'.$userName, 'WizardSetupModel');       
        try{
    
            $file = "../public/Template/".$hotelid."/".$templateid."/".$hotelid.".json";

            //print_r($file);die();
            $json_data = '';
            if(file_exists($file)){
                $json_file = file_get_contents($file);
                //print_r($json_file);die();
                $json_data = json_decode($json_file,true);
            }
           

            //print_r($json_data);die();

            //if(count($file)>=1)
                $objLogger->info('Json Data : '.json_encode($json_data));

            return $json_data;
        }
        catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    public function updatefeedmodel($feedtype,$hotelid,$userid,$userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_'.$userName, 'WizardSetupModel');       
        try{
            
            $action = 'UPDATEFEED';
            $sqlQuery = "CALL SP_WizardSetup('".$action."',0,$hotelid,$feedtype,$userid)";
            
            $objLogger->info('Query : '.$sqlQuery); 
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $updateTemp = $dbObjt->getSingleDatasByObjects($sqlQuery);          

            return $updateTemp;
        }
        catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    public function getinstemptblDetails($fileNamewithpath,$hotelid,$userid,$userName){
            $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_'.$userName, 'WizardSetupModel');
            try{
    
                $sqlQuery = "DELETE FROM tempchnlbulkupload";
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
                foreach($worksheet->getRowIterator() as $ChannelData) 
                {
                    
                    if($ChannelData->getRowIndex()==1){
                        
                        $firstCell = $worksheet->getCellByColumnAndRow(1, 1)->getValue();
                        //print_r(($firstCell));die();
                        if(trim(strtoupper($firstCell)) == 'CHANNEL NO'){
                            continue;
                        }
                        else{
                            throw new WizardSetupException('Please upload a valid files', 201);
                            exit();
                            break;
                        }
                    }
                    
                    $rowIndex=$ChannelData->getRowIndex();
                    $channelno        =  $worksheet->getCellByColumnAndRow(1, $rowIndex)->getValue();
                    $channelname     = $worksheet->getCellByColumnAndRow(2, $rowIndex)->getValue();
                    
                    $channeltype      =  $worksheet->getCellByColumnAndRow(3, $rowIndex)->getValue();
                    $portno      = $worksheet->getCellByColumnAndRow(4, $rowIndex)->getValue();
                    $multicastip        = $worksheet->getCellByColumnAndRow(5, $rowIndex)->getValue();
                    $channelfrequency       = $worksheet->getCellByColumnAndRow(6,$rowIndex)->getValue();
                    $hotelname         = $worksheet->getCellByColumnAndRow(7, $rowIndex)->getValue();

                    $insQuery = "INSERT INTO tempchnlbulkupload(channelno,channelname,channelcategory, portno, multicastip, 
                    channelfrequency,hotelid, createdOn, createdBy)
                    VALUES(".$channelno.", '".$channelname."', '".$channeltype."', '".$portno."', '".$multicastip."', 
                    '".$channelfrequency."', '".$hotelid."', SYSDATE(), ".$userid.")";
    
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
                    $blkdata = $this->bulkuploadToDB($hotelid, $userid);
                    return $blkdata;
                }
                else {
                    throw new WizardSetupException('Bulk upload failure', 401);
                }
    
                return $blkdata;
            }
            catch (WizardSetupException $ex) {
    
                $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
                $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
                //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
                if(!empty($ex->getMessage())){
                    throw new WizardSetupException($ex->getMessage(), $ex->getCode());
                }
                else {
                    throw new WizardSetupException('Invalid Access', 401);
                }
            }
    }

    public function bulkuploadToDB($hotelid, $userid){
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_'.$userid, 'WizardSetupModel');
        $blkupldData = array();
        try{

            $success = 0;
            $failure = 0;
           
            $sqlQuery = "call SP_channelBulkUpload (".$userid.", ".$hotelid.")";
            
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
        catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    public function getTemplateName($tempateid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_'.$userName, 'WizardSetupModel');  
        $sqlQuery = "select count(*) as cnt from template where id=".$tempateid;
            
        $objLogger->info('Query : '.$sqlQuery); 
        $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
        $getSingleResult  = $dbObjt->getSingleDatasByObjects($sqlQuery);
        if($getSingleResult){
            
            if($getSingleResult->cnt > 0){
                $returnQry = "select templatename as name from template where id=".$tempateid;            
                $objLogger->info('Query : '.$returnQry); 
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $getName  = $dbObjt->getSingleDatasByObjects($returnQry);
                //print_r($getName);die();
                //echo($getName->name);die();
                return $getName->name;
            }else{
                return '';
            }
            print_R($getSingleResult);die();
        }


    }

    function moveUploadedFile($directory, $orginalName,$filename)
    {
       // print_r(($orginalName->getClientFilename()));die();
       $ImageName = $orginalName->getClientFilename();
      
        
       $ext = pathinfo($ImageName, PATHINFO_EXTENSION);
       
        $newName = $filename.'.'.$ext;
         
        if ($orginalName->getError() === UPLOAD_ERR_OK) {
           
            if(!file_exists($directory)){
                mkdir($directory,0777,true);
            }
            
            $orginalName->moveTo($directory . DIRECTORY_SEPARATOR . $newName);
            
        }
        //echo $newName;die();
        //$orginalName->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $newName;
    }

}