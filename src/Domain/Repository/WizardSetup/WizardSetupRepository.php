<?php
namespace App\Domain\Repository\WizardSetup;
use App\Exception\WizardSetup\WizardSetupException;
use App\Model\WizardSetupModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Domain\Service\WizardSetup\WizardSetupService;
use App\Model\HotelModel;

class WizardSetupRepository extends BaseRepository implements WizardSetupService
{
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function getAlltemplate($JWTdata,$userid,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('WizardSetupModel_'.$userName.'.log')->createInstance('WizardSetupRepository');
        try{

            $templateid = isset($JWTdata->templateid)?$JWTdata->templateid:"0";
            $hotelid = isset($JWTdata->hotelid)?$JWTdata->hotelid:"0";
            if($templateid == 0){
                throw new WizardSetupException('Template Id required', 201);
            }
            if($hotelid == 0){
                throw new WizardSetupException('Hotel Id required', 201);
            }
            $updatetemplate = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
            
            $update = $updatetemplate->updateTemplate($templateid,$hotelid,$userid,$userName);
            
            return $update;

        }catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), 401);
            }
            else {
                throw new WizardSetupException('Hotel credentials invalid', 401);
            }
        }
    }

    public function AddFeatures($JWTdata,$userid,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('WizardSetupModel_'.$userName.'.log')->createInstance('WizardSetupRepository');
        try{

    
            $hotelid = isset($JWTdata->hotelid)?$JWTdata->hotelid:"0";
            $featurelist = isset($JWTdata->featureid)?$JWTdata->featureid:"0";
            if($featurelist == 0){
                throw new WizardSetupException('Feature Id required', 201);
            }
            if($hotelid == 0){
                throw new WizardSetupException('Hotel Id required', 201);
            }
            $updatetemplate = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
            
            $update = $updatetemplate->AddFeatures($featurelist,$hotelid,$userid,$userName);
            
            return $update;

        }catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), 401);
            }
            else {
                throw new WizardSetupException('Hotel credentials invalid', 401);
            }
        }
    }

	public function createJsonFile($JWTdata,$logo,$bgimg,$menuicon,$userId,$userName){
    //public function createJsonFile($JWTdata,$logo,$bgimg,$userId,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('WizardSetupModel_'.$userName.'.log')->createInstance('WizardSetupRepository');
        try{

    
            $tempid = isset($JWTdata->tempid)?$JWTdata->tempid:"0";
            $menuName = isset($JWTdata->menutitle)?$JWTdata->menutitle:'';
            $menuUrl = isset($JWTdata->menulink)?$JWTdata->menulink:'';
            $hotelid = isset($JWTdata->hotelid)?$JWTdata->hotelid:'';
            $welcome_head = isset($JWTdata->welcome_head)?addslashes($JWTdata->welcome_head):'';
            $welcome_body = isset($JWTdata->welcome_body)?addslashes($JWTdata->welcome_body):'';
            // $featurelist = isset($JWTdata->featureid)?$JWTdata->featureid:"0";
            // if($featurelist == 0){
            //     throw new WizardSetupException('Feature Id required', 401);
            // }
            
            if($hotelid == 0){
                throw new WizardSetupException('Hotel Id required', 201);
            }
            $createJsonFile = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
			
			$createJsonlist = $createJsonFile->createJson($hotelid,$tempid,$welcome_head, $welcome_body,$menuName,$menuUrl,$logo,$bgimg,$menuicon,$userId,$userName);
            
            //$createJsonlist = $createJsonFile->createJson($hotelid,$tempid, $welcome_body,$logo,$bgimg,$userId,$userName);
            
            
            return $createJsonlist;

        }catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), 401);
            }
            else {
                throw new WizardSetupException('Hotel credentials invalid', 401);
            }
        }
    }

    public function gettemplateDetails($hotelid,$tempid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('WizardSetupModel_'.$userName.'.log')->createInstance('HotelChannelRepository');

        try{    
            $OverallList = new \stdClass();
            $DetailedView = new \stdClass();
            $templateView = new \stdClass();
            if($hotelid == 0){
                throw new WizardSetupException('Hotel Id Required', 201);
            }
        $List = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
        $overallview = $List->gettemplateDetailsModel($hotelid,$tempid,$userid,$userName);
        
        
         //print_r($overallview);die();
        /* ---------------START Customer List ------------------*/
        $OverallList->CustomerDetails=$DetailedView;
        if(!empty($overallview->template)){
            $DetailedView->brandid = $overallview->template->brandid;
            $DetailedView->brandname = $overallview->template->brandname;
            $DetailedView->hotelname = $overallview->template->hotelname;
            $DetailedView->custmail = $overallview->template->custmail;
            $DetailedView->location = $overallview->template->location;
            $DetailedView->mobileno = $overallview->template->mobileno;
            $DetailedView->spocname = $overallview->template->spocname;
            $DetailedView->address = $overallview->template->address;
            $DetailedView->tempid = $overallview->template->tempid;
            //$DetailedView->templatename = $overallview->template->templatename;
            $DetailedView->channelfeedtype = $overallview->template->channelfeedtype;
            $DetailedView->channeltype = $overallview->template->channeltype;
        }
        
        /*---------------- END Customer List -----------------*/

        /*---------------- START Template List -----------------*/

        $OverallList->TemplateDetails = $templateView;
        if(!empty($overallview->template)){
            $templateView->id = $overallview->template->tempid;
            $templateView->name = $overallview->template->templatename;
            
        }
        if(!empty($overallview->Homescreen)){
            $templateView->HomeScreen = $overallview->Homescreen;
        }
        

        /*---------------- END Template List -----------------*/

        /*---------------- START Feature List -----------------*/

        $featureObj  = $overallview->features;
        if(!empty($featureObj)){
            $OverallList->FeatureDetails = $featureObj; 
        }
        

        /*---------------- END Feature List -------------------*/

        //$OverallList->HomeScreen = $overallview->Homescreen; 

      
        $categoriesList =array();
        $listArray = array();
        
        if(!empty($overallview->channeldetail)){
            $GetoverAllList=$overallview->channeldetails;
            $i = 0 ;
                foreach($GetoverAllList as $list){
                    $logDetails = new \stdClass();
                    $logDetails->channelname = $list->channelname;
                    $logDetails->channellogo = $list->channellogo;
                    $logDetails->channelno = $list->channelno;
                    $logDetails->channelip = $list->channelip;
                    $logDetails->channelport = $list->channelport;
                    
                    if(!in_array($list->categoryname, $categoriesList)){
                        $categoriesList[]=$list->categoryname;
                        $listArray [$list->categoryname][] = $logDetails;
                        
                    }
                    else{
                        $listArray [$list->categoryname][]  = $logDetails;
                    }
                    $i++;
                }
        }
        
        
        $OverallList->ChannelDeatails = $listArray;
        
       // print_r($OverallList);die();
        
        return $OverallList;
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

    public function updatefeed($JWTdata,$userid,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('WizardSetupModel_'.$userName.'.log')->createInstance('WizardSetupRepository');
        try{

            $feedtype = isset($JWTdata->channelfeedtype)?$JWTdata->channelfeedtype:"0";
            $hotelid = isset($JWTdata->hotelid)?$JWTdata->hotelid:"0";
            if($feedtype == 0){
                throw new WizardSetupException('Channel Feed required', 201);
            }
            if($hotelid == 0){
                throw new WizardSetupException('Hotel Id required', 201);
            }
            $updatetemplate = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
            
            $update = $updatetemplate->updatefeedmodel($feedtype,$hotelid,$userid,$userName);
            
            return $update;

        }catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), 401);
            }
            else {
                throw new WizardSetupException('Hotel credentials invalid', 401);
            }
        }
    }

    public function bulkUploadrepository($JWTdata,$bulkuploadfile,$userid,$userName){
        //public function createJsonFile($JWTdata,$logo,$bgimg,$userId,$userName){
            $objLogger = $this->loggerFactory->addFileHandler('WizardSetupModel_'.$userName.'.log')->createInstance('WizardSetupRepository');
            try{
                
                $data = json_decode(json_encode($JWTdata), false);
                $hotelid =isset($data->hotelid)?$data->hotelid:'';   
               
                if(empty($hotelid)){
                    throw new WizardSetupException('Please select Hotel.', 201);
                }
                
                if(empty($bulkuploadfile)){
                    throw new WizardSetupException('Please upload a valid files.', 201);
                }
               
                if($bulkuploadfile->getError() === UPLOAD_ERR_OK){
                    $extension = pathinfo($bulkuploadfile->getClientFilename(), PATHINFO_EXTENSION);
                    if(strtolower($extension) != 'xlsx'){
                        throw new WizardSetupException("Invalid file type. Allowed file type's are xlsx.", 201);
                    }
                    //$uniqueFilename = 	date("YmdHis").".xlsx";    
                    $fileName = $bulkuploadfile->getClientFilename();
                    $filePath = "../public/uploads/bulkupload/channel";
                    $objLogger->info(" -- filePath : ".$filePath);
                    $objLogger->info(" -- fileName : ".$fileName);
    
                    $bulkuploadfile->moveTo($filePath."/".$fileName);
                    
                    $wizardModel = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
                    $blkdata = $wizardModel->getinstemptblDetails($filePath."/".$fileName, $hotelid,$userid, $userName);
                    //$objLogger->info("Bulk Data Response : ".count($blkdata));
                    $objLogger->info("======= End Wizard Repository ================");
                    return $blkdata;
                    
    
                }
                else {
                    throw new WizardSetupException('Error occurs while uploading a file.', 201);
                }        
    
            }catch (WizardSetupException $ex) {
    
                $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
                $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
                $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
                if(!empty($ex->getMessage())){
                    throw new WizardSetupException($ex->getMessage(), 401);
                }
                else {
                    throw new WizardSetupException('Hotel credentials invalid', 401);
                }
            }
        }
}