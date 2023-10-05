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
    public function createhtlinfo($JWTdata,$logo,$bgimg,$menuicon,$userId,$userName){
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'createhtlinfo');
            try{
    
                $objLogger->info("======= START WizardSetup Repository (createhtlinfo) ================");
                $tempid = isset($JWTdata->tempid)?$JWTdata->tempid:"0";
                $menuName = isset($JWTdata->menutitle)?$JWTdata->menutitle:'';
                //$menuUrl = isset($JWTdata->menulink)?$JWTdata->menulink:'';
                $hotelid = isset($JWTdata->hotelid)?$JWTdata->hotelid:'0';
                $welcome_head = isset($JWTdata->title)?addslashes($JWTdata->title):'';
                $welcome_body = isset($JWTdata->content)?addslashes($JWTdata->content):'';
                $menucontent = isset($JWTdata->menucontent)?($JWTdata->menucontent):'';
                $menuid = isset($JWTdata->menuid)?($JWTdata->menuid):'';

                $objLogger->info("Input Data:".json_encode($JWTdata));
                
                
                // $featurelist = isset($JWTdata->featureid)?$JWTdata->featureid:"0";
                // if($featurelist == 0){
                //     throw new WizardSetupException('Feature Id required', 401);
                // }
                
                if($hotelid == 0){
                    throw new WizardSetupException('Hotel Id required', 201);
                }
                if($tempid == 0){
                    throw new WizardSetupException('Template Id required', 201);
                }
                $createGuestmodel = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
                
                $createJsonlist = $createGuestmodel->createhtlinfoModel($hotelid, $tempid, $welcome_body, $welcome_head, $bgimg, $menuid,$menuName, $menuicon,$menucontent,$userId,$userName);
                
                //$createJsonlist = $createJsonFile->createJson($hotelid,$tempid, $welcome_body,$logo,$bgimg,$userId,$userName);
                $objLogger->info("Returned Result:".json_encode($createJsonlist));
                $objLogger->info("======= END WizardSetup Repository (createhtlinfo) ================");
                
                return $createJsonlist;
    
            }catch (WizardSetupException $ex) {
    
                $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
                $objLogger->info("======= END WizardSetup Repository (createhtlinfo) ================");
                $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
                if(!empty($ex->getMessage())){
                    throw new WizardSetupException($ex->getMessage(), 401);
                }
                else {
                    throw new WizardSetupException('Hotel credentials invalid', 401);
                }
            }
    }
    

    public function createGuest($JWTdata,$logo,$bgimg,$menuicon,$userId,$userName){
        

            $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'createGuest');
            try{
    
                $objLogger->info("======= START WizardSetup Repository (createGuest) ================");
                $tempid = isset($JWTdata->tempid)?$JWTdata->tempid:"0";
                $menuName = isset($JWTdata->menutitle)?$JWTdata->menutitle:'';
                //$menuUrl = isset($JWTdata->menulink)?$JWTdata->menulink:'';
                $hotelid = isset($JWTdata->hotelid)?$JWTdata->hotelid:'0';
                $welcome_head = isset($JWTdata->title)?addslashes($JWTdata->title):'';
                $welcome_body = isset($JWTdata->content)?addslashes($JWTdata->content):'';
                $menucontent = isset($JWTdata->menucontent)?($JWTdata->menucontent):'';
                $menuid = isset($JWTdata->menuid)?($JWTdata->menuid):'';
                // $featurelist = isset($JWTdata->featureid)?$JWTdata->featureid:"0";
                // if($featurelist == 0){
                //     throw new WizardSetupException('Feature Id required', 401);
                // }
                $objLogger->info("Input Data :".json_encode($JWTdata));
                if($hotelid == 0){
                    throw new WizardSetupException('Hotel Id required', 201);
                }
                if($tempid == 0){
                    throw new WizardSetupException('Template Id required', 201);
                }
                $createGuestmodel = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
                
                $createJsonlist = $createGuestmodel->createGuestModel($hotelid, $tempid, $welcome_body, $welcome_head, $bgimg, $menuid,$menuName, $menuicon,$menucontent,$userId,$userName);
                
                //$createJsonlist = $createJsonFile->createJson($hotelid,$tempid, $welcome_body,$logo,$bgimg,$userId,$userName);
                $objLogger->info("Returned Result: ".json_encode($createJsonlist));
                $objLogger->info("======= END WizardSetup Repository (createGuest) ================");
                return $createJsonlist;
    
            }catch (WizardSetupException $ex) {
    
                $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
                $objLogger->info("======= END WizardSetup Repository (createGuest) ================");
                if(!empty($ex->getMessage())){
                    throw new WizardSetupException($ex->getMessage(), 401);
                }
                else {
                    throw new WizardSetupException('Hotel credentials invalid', 401);
                }
            }
        }
    public function  Rewrite_menu($input,$menuicon,$userid,$userName){


        $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'Rewrite_menu');

        try{    
            $objLogger->info("======= START WizardSetup Repository (Rewrite_menu) ================");
            //$data = 
            $hotelid = isset($input->hotelid)?$input->hotelid:'0';
            $tempid = isset($input->tempid)?$input->tempid:'0';
            $menuid = isset($input->menuid)?$input->menuid:'';
            $menuname = isset($input->menuname)?$input->menuname:'';
            $menuicon = isset($menuicon)?$menuicon:'';
            $subtext = isset($input->subtext)?$input->subtext:'';
            $rowOrder = isset($input->rowOrder)?($input->rowOrder):'';

            $objLogger->info("Input Data: ".json_encode($input));
            if($hotelid == 0){
                throw new WizardSetupException('Hotel Id required', 201);
            }
            if($tempid == 0){
                throw new WizardSetupException('Template Id required', 201);
            }

        $List = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
        $overallview = $List->Rewrite_menusetupmodel($hotelid,$tempid,$menuid,$menuname,$menuicon,$subtext,$rowOrder,$userid,$userName);
        $objLogger->info("Returned Result: ".json_encode($overallview));
        $objLogger->info("======= END WizardSetup Repository (Rewrite_menu) ================");
        
        return $overallview;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->info("======= END WizardSetup Repository (Rewrite_menu) ================");
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), 401);
            }
            else {
                throw new WizardSetupException('Channel credentials invalid', 401);
            }
        }
    }

    public function getAlltemplate($JWTdata,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'getAlltemplate');
        
        try{
            $objLogger->info("======= START WizardSetup Repository (getAlltemplate) ================"."\r\n");
            $templateid = isset($JWTdata->templateid)?$JWTdata->templateid:"0";
            $hotelid = isset($JWTdata->hotelid)?$JWTdata->hotelid:"0";
            $objLogger->info("Input Data: ".json_encode($JWTdata));
            if($templateid == 0){
                throw new WizardSetupException('Template Id required', 201);
            }
            if($hotelid == 0){
                throw new WizardSetupException('Hotel Id required', 201);
            }
            $updatetemplate = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
            
            $update = $updatetemplate->updateTemplate($templateid,$hotelid,$userid,$userName);
            $objLogger->info("Returned Result: ".json_encode($update)."\r\n");
            $objLogger->info("======= END WizardSetup Repository (getAlltemplate) ================");
            return $update;

        }catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->info("======= END WizardSetup Repository (getAlltemplate) ================");
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
            $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'createJsonFile');
            try{
                
                $objLogger->info("======= START WizardSetup Repository (createJsonFile) ================");
        
                $tempid = isset($JWTdata->tempid)?$JWTdata->tempid:"0";
                $menuName = isset($JWTdata->menutitle)?$JWTdata->menutitle:'';
                $menuUrl = isset($JWTdata->menulink)?$JWTdata->menulink:'';
                $hotelid = isset($JWTdata->hotelid)?$JWTdata->hotelid:'';
                $welcome_head = isset($JWTdata->welcome_head)?addslashes($JWTdata->welcome_head):'';
                $welcome_body = isset($JWTdata->welcome_body)?addslashes($JWTdata->welcome_body):'';
                $sub_title = isset($JWTdata->sub_title)?addslashes($JWTdata->sub_title):'';
                $menuid = isset($JWTdata->menuid)?($JWTdata->menuid):'';
                $primary = isset($JWTdata->primaryColor)?($JWTdata->primaryColor):'';
                $secondary = isset($JWTdata->secondaryColor)?($JWTdata->secondaryColor):'';
                
                $objLogger->info("Input Data: ".json_encode($JWTdata));
                // $featurelist = isset($JWTdata->featureid)?$JWTdata->featureid:"0";
                // if($featurelist == 0){
                //     throw new WizardSetupException('Feature Id required', 401);
                // }
                
                if($hotelid == 0){
                    throw new WizardSetupException('Hotel Id required', 201);
                }
                $createJsonFile = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
                
                $createJsonlist = $createJsonFile->createJson($hotelid,$tempid,$welcome_head, $welcome_body,$menuName,$menuUrl,$logo,$bgimg,$menuicon,$sub_title,$menuid,$primary,$secondary,$userId,$userName);
                
                $objLogger->info("Returned Result".json_encode($createJsonlist));
                $objLogger->info("======= END WizardSetup Repository (createJsonFile) ================");
                //$createJsonlist = $createJsonFile->createJson($hotelid,$tempid, $welcome_body,$logo,$bgimg,$userId,$userName);
                
                
                return $createJsonlist;
    
            }catch (WizardSetupException $ex) {
    
                $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
                $objLogger->info("======= END WizardSetup Repository (createJsonFile) ================");
                if(!empty($ex->getMessage())){
                    throw new WizardSetupException($ex->getMessage(), 401);
                }
                else {
                    throw new WizardSetupException('Hotel credentials invalid', 401);
                }
            }
        }
    

    public function AddFeatures($JWTdata,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'AddFeatures');
        
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

	
    public function gettemplateDetails($hotelid,$tempid,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'gettemplateDetails');

        try{    
            $OverallList = new \stdClass();
            $DetailedView = new \stdClass();
            $templateView = new \stdClass();
            if($hotelid == 0){
                throw new WizardSetupException('Hotel Id Required', 201);
            }
        $List = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);

        $objLogger->info("input Data:"."Hotel id :".$hotelid."\r\n"."Template ID :".$tempid."\r\n");
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

        // $featureObj  = $overallview->features;
        // if(!empty($featureObj)){
        //     $OverallList->FeatureDetails = $featureObj; 
        // }
        

        /*---------------- END Feature List -------------------*/

        //$OverallList->HomeScreen = $overallview->Homescreen; 
        
        $categoriesList =array();
        $listArray = array();
        
        if(!empty($overallview->channels)){
            $GetoverAllList=$overallview->channels;
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
        $OverallList->guestService = isset($overallview->guestService['guestServices']) ? $overallview->guestService['guestServices']:'';
        $OverallList->hotelInformation = isset($overallview->hotelInformation['hotelInformation']) ? $overallview->hotelInformation['hotelInformation']:'';
        
    //print_r($OverallList);die();
        
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
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'updatefeed');
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

    public function channeldataupload($JWTdata,$userid,$userName){
        //public function createJsonFile($JWTdata,$logo,$bgimg,$userId,$userName){
            $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'channeldataupload');
            try{
                
                $data = json_decode(json_encode($JWTdata), false);
                $hotelid =isset($data->hotelid)?$data->hotelid:'0';  
                $feedtype =isset($data->channelfeedtye)?$data->channelfeedtye:'0';   
                $channelno =isset($data->channelno)?$data->channelno:'0'; 
                $channelid =isset($data->channelid)?$data->channelid:'0'; 
                $channelcategoryid =isset($data->channelcategory)?$data->channelcategory:'0'; 
                $multicastip =isset($data->multicastip)?$data->multicastip:'';
                $portno =isset($data->portno)?$data->portno:'0'; 
                $channelfrequency =isset($data->channelfrequency)?$data->channelfrequency:''; 


                if($hotelid == 0){
                    throw new WizardSetupException('Please select Hotel.', 201);
                }

                if($feedtype == 0){
                    throw new WizardSetupException('Channel feed type required.', 201);
                }

                if($channelno == 0){
                    throw new WizardSetupException('Channel No required.', 201);
                }
                
                if($channelid == 0){
                    throw new WizardSetupException('Channel Name required.', 201);
                }

                if($channelcategoryid == 0){
                    throw new WizardSetupException('Channel Category required.', 201);
                }

                if($feedtype == 2){
                    if(empty($multicastip)){
                        throw new WizardSetupException('Channel IP Address required.', 201);
                    }
                    if($portno == 0){
                        throw new WizardSetupException('Port No required.', 201);
                    }
                }
                
                if($feedtype == 3){
                    if(empty($channelfrequency)){
                        throw new WizardSetupException('Channel frequency required.', 201);
                    }
                   
                }               
                
                    
                    $wizardModel = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
                    $blkdata = $wizardModel->saveDetails($JWTdata,$userid, $userName);
                    //$objLogger->info("Bulk Data Response : ".count($blkdata));
                    $objLogger->info("======= End Wizard Repository ================");
                    return $blkdata;
                    
    
                   
    
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


        public function updatechanneldata($JWTdata,$tvchannelids,$userid,$userName){
            $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'updatechanneldata');
                try{

                    $data = json_decode(json_encode($JWTdata), false);
                    //print_r($data);die();
                    $hotelid =isset($data->hotelid)?$data->hotelid:'0';  
                    $tvchannelid =isset($tvchannelids)?$tvchannelids:'0';  
                    $feedtype =isset($data->channelfeedtye)?$data->channelfeedtye:'0';   
                    $channelno =isset($data->channelno)?$data->channelno:'0'; 
                    $channelid =isset($data->channelid)?$data->channelid:'0'; 
                    $channelcategoryid =isset($data->channelcategory)?$data->channelcategory:'0'; 
                    $multicastip =isset($data->multicastip)?$data->multicastip:'';
                    $portno =isset($data->portno)?$data->portno:'0'; 
                    $channelfrequency =isset($data->channelfrequency)?$data->channelfrequency:'';    
                    //$status =isset($data->status)?$data->status:'1'; 
    
                    if($hotelid == 0){
                        throw new WizardSetupException('Please select Hotel.', 201);
                    }
                    if($tvchannelid == 0){
                        throw new WizardSetupException('Channel required.', 201);
                    }
                    if($feedtype == 0){
                        throw new WizardSetupException('Channel feed type required.', 201);
                    }
    
                    if($channelno == 0){
                        throw new WizardSetupException('Channel No required.', 201);
                    }
                    
                    if($channelid == 0){
                        throw new WizardSetupException('Channel Name required.', 201);
                    }
    
                    if($channelcategoryid == 0){
                        throw new WizardSetupException('Channel Category required.', 201);
                    }
    
                    if($feedtype == 2){
                        if(empty($multicastip)){
                            throw new WizardSetupException('Channel IP Address required.', 201);
                        }
                        if($portno == 0){
                            throw new WizardSetupException('Port No required.', 201);
                        }
                    }
                    
                    if($feedtype == 3){
                        if(empty($channelfrequency)){
                            throw new WizardSetupException('Channel frequency required.', 201);
                        }
                       
                    }               
                    
                        
                        $wizardModel = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
                        $blkdata = $wizardModel->updateChannelWizard($JWTdata,$tvchannelid,$userid, $userName);
                        //$objLogger->info("Bulk Data Response : ".count($blkdata));
                        $objLogger->info("======= End Wizard Repository ================");
                        return $blkdata;
                        
        
                       
        
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
            $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'bulkUploadrepository');

            try{
                
                $data = json_decode(json_encode($JWTdata), false);
                $hotelid =isset($data->hotelid)?$data->hotelid:'';  
                $feedtype =isset($data->channelfeedtye)?$data->channelfeedtye:'';   
               
                if(empty($hotelid)){
                    throw new WizardSetupException('Please select Hotel.', 201);
                }
                if(empty($feedtype)){
                    throw new WizardSetupException('Channel feed type required.', 201);
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
                    $blkdata = $wizardModel->getinstemptblDetails($filePath."/".$fileName, $hotelid,$feedtype,$userid, $userName);
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

       

        public function  getguestJson($hotelid,$tempid,$userid,$userName){

            $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'getguestJson');

    
            try{    
           
               /* if($hotelid == 0){
                    throw new WizardSetupException('Hotel Id Required', 201);
                }*/
            $List = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
            $overallview = $List->getGuestobject($hotelid,$tempid,$userid,$userName);
            
            
            
            return $overallview;
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

        public function  getHomescreenJson($hotelid,$tempid,$userid,$userName){

            $objLogger = $this->loggerFactory->getFileObject('WizardSetupRepository_' . $userName, 'getHomescreenJson');

    
            try{    
           
               /* if($hotelid == 0){
                    throw new WizardSetupException('Hotel Id Required', 201);
                }*/
            $List = new WizardSetupModel($this->loggerFactory, $this->dBConFactory);
            $overallview = $List->gethomescreenObject($hotelid,$tempid,$userid,$userName);
            
            
            
            return $overallview;
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
}