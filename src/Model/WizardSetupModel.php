<?php
namespace App\Model;

use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\WizardSetup\WizardSetupException;
use App\Model\DB;
use Slim\Http\UploadedFile;
use stdClass;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

//use PhpOffice\PhpSpreadsheet\Helper\Sample;

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
   public  function comparison($menus1, $data)
    {

        return ($menus1['orderby'] - $data['orderby']);
    }
    public function createhtlinfoModel($hotelid, $tempid, $body, $head, $bgimg, $menuid,$menuName, $menuicon,$sub_text,$userId, $userName){
        $htlinfojson = "../public/template/".$hotelid."/".$tempid."/hotel_info.json";
        $JsonfileName = 'hotel_info.json';
        $basetemplateUrl = "../public/template/" . $hotelid . "/" . $tempid;
        $relatedpath = "public/uploads/template/" . $hotelid . "/" . $tempid."/Hotel_info"."/";
        $imgurlpath = "../public/uploads/template/" . $hotelid . "/" . $tempid."/Hotel_info";

        //$testPage = "../public/uploads/menujson";

        $json_data = '';

        $jsonobject = new stdClass();
        $json_new = new stdClass();
        $logoUrl = $this->GethomescreenDetails($hotelid, $tempid, 'logo');
        $backgruondimg = $this->GethomescreenDetails($hotelid, $tempid, 'bg_img');
        $guestbgimg = $this->ReadJsonFile($hotelid, $tempid, 'bgimg',$JsonfileName,'hotelInformation');
        $guestmenu = $this->ReadJsonFile($hotelid, $tempid, 'menu',$JsonfileName,'hotelInformation');

        $imgurl = '';
        if (!empty($bgimg) && $bgimg !='undefined') {
            $imgname = 'Hotel_Info_Background';
            $menuiconresult = $this->moveUploadedFile($imgurlpath, $bgimg, $imgname);
            $imgurl = $relatedpath . $menuiconresult;
        }else{
            $imgurl = $guestbgimg;
        }


        $json_new->hotelid = $hotelid;
        $json_new->templateid = $tempid;
        $json_new->logo = $logoUrl;
        $json_new->home_bgimg = $backgruondimg;
        $json_new->bgimg = $imgurl;
        $json_new->welcome = $head;
        $json_new->welcome_txt = $body;
        $menu_details = array();
        
        if(!empty($menuName)){
            $menupos = 0;
            for ($m=0; $m < count($menuName); $m++) { 
               $menu_obj =  new stdClass();
               $menu_obj->title = $menuName[$m];
              
               if(!empty($menuicon[$m])){
                $filename = str_replace(" ","_",$menuName[$m]);
                
                $menuiconresult = $this->moveUploadedFile($imgurlpath, $menuicon[$m], $filename);
                $menuimgurl = $relatedpath . $menuiconresult;
                $menu_obj->img = $menuimgurl;
               }else{
                
                $menu_obj->img = isset($guestmenu[$menupos]->img)?$guestmenu[$menupos]->img:'';
                $menupos++;
               }
               if(!empty($sub_text[$m])){
                $menu_obj->content = $sub_text[$m];
               }
                array_push($menu_details, $menu_obj);
            }
        }
        $json_new->menu = $menu_details;
        $jsonobject->hotelInformation = $json_new;

        if (!file_exists($htlinfojson)) {

            if(!is_dir($basetemplateUrl)){
                mkdir($basetemplateUrl, 0777, true);
            }
            
        }
       
        $jsonData = json_encode($jsonobject, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        file_put_contents($htlinfojson, $jsonData);

        return $jsonobject;
    }


    public function createGuestModel($hotelid, $tempid, $body, $head, $bgimg, $menuid,$menuName, $menuicon,$sub_text,$userId, $userName)
    {
        $GuestJsonFile = "../public/template/".$hotelid."/".$tempid."/guest_service.json";
        $Jsonfilename ='guest_service.json';
        $basetemplateUrl = "../public/template/" . $hotelid . "/" . $tempid;
        $imgurlpath = "../public/uploads/template/" . $hotelid . "/" . $tempid."/Guest_service";
        $relatedpath = "public/uploads/template/" . $hotelid . "/" . $tempid."/Guest_service"."/";
        $guestbgimg = $this->ReadJsonFile($hotelid, $tempid, 'bgimg', $Jsonfilename,'guestServices');
        $guestmenu = $this->ReadJsonFile($hotelid, $tempid, 'menu', $Jsonfilename,'guestServices');
        //print_R($guestmenu);die();
        //$testPage = "../public/uploads/menujson";

        $json_data = '';

        $jsonobject = new stdClass();
        $json_new = new stdClass();
        $logoUrl = $this->GethomescreenDetails($hotelid, $tempid, 'logo');
        $backgruondimg = $this->GethomescreenDetails($hotelid, $tempid, 'bg_img');

        $imgurl = '';
        if (!empty($bgimg) && $bgimg !='undefined' ) {
            $imgname = 'Guest_Service_Background';
            $bgimgrslt = $this->moveUploadedFile($imgurlpath, $bgimg, $imgname);
            $imgurl = $relatedpath . $bgimgrslt;
        }else{
            $imgurl = $guestbgimg;
        }


        $json_new->hotelid = $hotelid;
        $json_new->templateid = $tempid;
        $json_new->logo = $logoUrl;
        $json_new->home_bgimg = $backgruondimg;
        $json_new->bgimg = $imgurl;
        $json_new->title = $head;
        $json_new->subtext = $body;
        $jsonobject->guestServices = $json_new;
        $menu_details = array();
      
        if(!empty($menuName)){
            $guestimginc = 0;
            for ($m=0; $m < count($menuName); $m++) { 
               $menu_obj =  new stdClass();
               $menu_obj->title = $menuName[$m];
              
               if(!empty($menuicon[$m])){
                $filename = str_replace(" ","_",$menuName[$m]);
                
                $menuiconresult = $this->moveUploadedFile($imgurlpath, $menuicon[$m], $filename);
                $menuimgurl = $relatedpath . $menuiconresult;
                $menu_obj->img = $menuimgurl;
               }else{
                $menu_obj->img = isset($guestmenu[$m]->img)?$guestmenu[$m]->img:'';

               // print_R($guestmenu[$m]);die();
                //$guestimginc++;
               }
               if(!empty($sub_text[$m])){
                $menu_obj->content = $sub_text[$m];
               }
                array_push($menu_details, $menu_obj);
            }
        }
        $json_new->menu = $menu_details;
        $jsonobject->guestServices = $json_new;

        if (!file_exists($GuestJsonFile)) {

            if(!is_dir($basetemplateUrl)){
                mkdir($basetemplateUrl, 0777, true);
            }
            
        }
       
        $jsonData = json_encode($jsonobject, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        file_put_contents($GuestJsonFile, $jsonData);

        return $jsonobject;
    }

    public function Rewrite_menusetupmodel($hotelid, $tempid, $menuid, $menuname, $menuimg, $subtext,$rowOrder, $userid, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        try {
            $template = new stdClass();
            // $tempDetails = new stdClass();
            $subclass = new stdClass();
            $JsonUrl = "../public/template/" . $hotelid . "/" . $tempid . "/home.json";
            $parentUrl = "../public/uploads/template/" . $hotelid . "/" . $tempid . "/";
            $imgpath = "public/uploads/template/" . $hotelid . "/" . $tempid . "/";
            $basetemplateUrl = "../public/template/" . $hotelid . "/" . $tempid;
            $menu_array = array();
            $menulist='';
            if (file_exists($JsonUrl)) {
                $feature_array= array();
                $json_file = file_get_contents($JsonUrl);
                $json_data = json_decode($json_file, false);
                $data = isset($json_data->HomePageDetails) ? $json_data->HomePageDetails : '';


                //$template->HomePageDetails='';
                $hotelid = isset($data->hotelid) ? $data->hotelid : '';
                $templateid = isset($data->templateid) ? $data->templateid : '';
                $logo = isset($data->logo) ? $data->logo : '';
                $bg_img = isset($data->bg_img) ? $data->bg_img : '';
                $welcome_txt = isset($data->welcome_txt) ? $data->welcome_txt : '';
                $featursemenu = isset($data->featursemenu) ? $data->featursemenu : '';
                $menulist = isset($data->menu) ? $data->menu : '';
                

                $subclass->hotelid = $hotelid;
                $subclass->templateid = $templateid;
                $subclass->logo = $logo;
                $subclass->bg_img = $bg_img;
                $subclass->welcome_txt = $welcome_txt;
                $subclass->featursemenu = $featursemenu;
                
            } else {
                $subclass->hotelid = '';
                $subclass->templateid = '';
                $subclass->logo = '';
                $subclass->bg_img = '';
                $subclass->welcome_txt = '';
            }
            if(!empty($menuname)){
                for ($i = 0; $i < count($menuname); $i++) {
                    $menuDetails = new stdClass();
                    $img_name = str_replace(" ", "_", $menuname[$i]);
                    $menuiconrslt = '';
                    if (!empty($menuimg[$i])) {
                        $menuiconrslt = $this->moveUploadedFile($parentUrl, $menuimg[$i], $img_name);
                    }else{
                       $menupath = isset($menulist[$i]->img)?$menulist[$i]->img:'';
                    }
    
                    $menuicon = (!empty($menuimg[$i])) ? $imgpath . $menuiconrslt : $menupath;
                    $menuDetails->menuid = $menuid[$i];
                    $menuDetails->title = $menuname[$i];
                    $menuDetails->img = $menuicon;
                    $menuDetails->subtitle = $subtext[$i];
                    $menuDetails->orderby = $rowOrder[$i];
    
                    array_push($menu_array, $menuDetails);
                }
            }
           
            $subclass->menu = $menu_array;

            $template->HomePageDetails = $subclass;
    
            $jsonData = json_encode($template, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

            if (!file_exists($JsonUrl)) {
                mkdir($basetemplateUrl, 0777, true);
                // Create the file and write the content
                // $result = file_put_contents($JsonUrl, $jsonData);
            }
            file_put_contents($JsonUrl, $jsonData);

            return $template;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    public function updateTemplate($templateid, $hotelid, $userid, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        try {
            // $template = new stdClass();
            // $tempDetails = new stdClass();
            $action = 'UPDATE';
            $sqlQuery = "CALL SP_WizardSetup('" . $action . "',$templateid,$hotelid,0,$userid)";

            $objLogger->info('Query : ' . $sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $updateTemp = $dbObjt->getSingleDatasByObjects($sqlQuery);

            return $updateTemp;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }


    public function AddFeatures($featurelist, $hotelid, $userid, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        try {

            //$list = explode(',',$featurelist);
            $list = $featurelist;
            $result = '';
            if (!empty($list)) {
                for ($m = 0; $m < count($list); $m++) {
                    $sqlQuery = "insert into temphotelfeaturelist (hotelid,featureid) values($hotelid,$list[$m])";
                    $objLogger->info('Query : ' . $sqlQuery);
                    $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                    $insertion = $dbObjt->insOrUpdteOrDetQuery($sqlQuery);
                }
                $action = 'ADD';
                $sqlQuery = "CALL SP_WizardSetup('" . $action . "',0,$hotelid,0,$userid)";
                $objLogger->info('Query : ' . $sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $result = $dbObjt->getSingleDatasByObjects($sqlQuery);
            }

            return $result;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    public function createJson($hotelid, $tempid, $welcome_head, $welcome_body, $menuName, $menuUrl, $logo, $bgimg, $menuicon, $sub_title, $menuid, $userid, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'createJson');
        try {
            $homeSreen = new stdClass();
            $subscreen = new stdClass();

            $welcome_head = '';
            $homeSreen->HomePageDetails = 'HomePageDetails';
            $homeSreen->HomePageDetails = $subscreen;
            $subscreen->hotelid = $hotelid;
            $subscreen->templateid = $tempid;



            $JsonUrl = "../public/template/" . $hotelid . "/" . $tempid . "/home.json";
            $basejsonurl = "../public/template/" . $hotelid . "/" . $tempid;

            $parentUrl = "../public/uploads/template/" . $hotelid . "/" . $tempid . "/";
            $baseurl = "public/uploads/template/" . $hotelid . "/" . $tempid . "/";
            $logoUrl = '';
            $backgruondimg = '';
            $logmenuName = isset($menuid) ? json_encode($menuid) : '';
            $logmenuId = isset($menuName) ? json_encode($menuName) : '';
            $logsubtitle = isset($sub_title) ? json_encode($sub_title) : '';
            //print_r($sub_title);die();
            $objLogger->info('Json Url : ' . $JsonUrl
                . "\r\n------ hotelid: " . $hotelid
                . "\r\n------ tempid: " . $tempid
                . "\r\n------ welcome_body: " . $welcome_body
                . "\r\n------ menuName: " . $logmenuName
                . "\r\n------ MenuId: " . $logmenuId
                . "\r\n------ sub_title: " . $logsubtitle);
            $imgurl = '';

            $menuList = array();
            //print_R($menuName);die();

            if (!empty($menuName)) {
                $i = 0;
                $premenu= array();
                if (file_exists($JsonUrl)) {
                    
                    $json_file = file_get_contents($JsonUrl);
                    $json_data = json_decode($json_file, true);
                    //print_R($json_file);die();
                    $PreviousLogoUrl = isset($json_data['HomePageDetails']['logo']) ? $json_data['HomePageDetails']['logo'] : '';
                    $PreviousbgUrl = isset($json_data['HomePageDetails']['bg_img']) ? $json_data['HomePageDetails']['bg_img'] : '';
                    $Previouswlcomtxt = isset($json_data['HomePageDetails']['welcome_txt']) ? $json_data['HomePageDetails']['welcome_txt'] : '';
                    $premenu = isset($json_data['HomePageDetails']['menu']) ? $json_data['HomePageDetails']['menu'] : '';
                    //$prevfeaturelst = isset($json_data['HomePageDetails']['featursemenu']) ? $json_data['HomePageDetails']['featursemenu'] : '';
                    if (!empty($PreviousLogoUrl)) {
                        $subscreen->logo = $PreviousLogoUrl;
                    }

                    if (!empty($PreviousbgUrl)) {
                        $subscreen->bg_img = $PreviousbgUrl;
                    }


                    if (!empty($Previouswlcomtxt)) {
                        $subscreen->welcome = '';
                        $subscreen->welcome_txt = $Previouswlcomtxt;
                    }

                    $currentMenuNames = explode(',', $menuName);
                    $previousmenuarray = array();
                    if(!empty($premenu)){
                        for ($cm=0; $cm < count($currentMenuNames) ; $cm++) { 
                            
                            $current_menuid = explode(',', $menuid);
                            
                            $flag = 0;
                               
                            foreach ($premenu as $key => $mnu) { 
                                
                              
                                if($current_menuid[$cm] == $mnu['menuid']){
                                    $pre_menu_obj = new stdClass();
                                    $pre_menu_obj->menuid  =$mnu['menuid'];
                                    $pre_menu_obj->title  = $mnu['title'];
                                    $pre_menu_obj->img  = $mnu['img'];
                                    $pre_menu_obj->subtitle  = $mnu['subtitle'];
                                    $pre_menu_obj->orderby = isset($mnu['orderby'])?$mnu['orderby']:'';
                                    array_push($previousmenuarray,$pre_menu_obj);
                                    unset($mnu);
                                    $flag= 1;  
                                    break;                                
                                    
                                }

                                                            
                                
                            }

                            if($flag == 0):
                                $curr_menu_obj = new stdClass();
                                $curr_menu_obj->menuid  = $current_menuid[$cm];
                                $curr_menu_obj->title  = $currentMenuNames[$cm];
                                $curr_menu_obj->img  = '';
                                $curr_menu_obj->subtitle  = '';
                                $curr_menu_obj->orderby = '';
                                array_push($previousmenuarray,$curr_menu_obj);
                            
                            endif;

                        }
                    }
                   //print_r($previousmenuarray);die();
                    //$preuniquemenus  = $this->unique_key($previousmenuarray,'menuid');
                   // usort ($previousmenuarray ,[WizardSetupModel::class, "comparison"]);

                    //print_r($previousmenuarray);die();
                    $property = 'orderby';
                    $menusorderby_asc  = array_column($previousmenuarray, $property); // created new array
                    array_multisort($menusorderby_asc, $previousmenuarray); // variable (order by) assending order 

                    
                    $subscreen->menu = $previousmenuarray;

                }

                $arraymenuName = explode(',', $menuName);
                foreach ($arraymenuName as $mnu) {
                    $submenu = new stdClass();
                    $arrayMenu_id = explode(',', $menuid);

                    if ($menuid) {
                        $submenu->menuid = $arrayMenu_id[$i];
                    } else {
                        $submenu->menuid = '';
                    }
                    $submenu->title = $mnu;
                    array_push($menuList, $submenu);
                    $i++;
                }

                //print_R($menuList);die();
                $subscreen->featursemenu = $menuList;


               
                // $subscreen->menu= $menu_List;
                

            } else {

                //$return = 0;

                if (file_exists($JsonUrl)) {
                    $json_file = file_get_contents($JsonUrl);
                    //print_r($json_file);die();
                    $json_data = json_decode($json_file, true);
                    $PreviousLogoUrl = isset($json_data['HomePageDetails']['logo']) ? $json_data['HomePageDetails']['logo'] : '';
                    $PreviousbgUrl = isset($json_data['HomePageDetails']['bg_img']) ? $json_data['HomePageDetails']['bg_img'] : '';
                    $Previouswlcomtxt = isset($json_data['HomePageDetails']['welcome_txt']) ? $json_data['HomePageDetails']['welcome_txt'] : '';
                    $previousfeature = isset($json_data['HomePageDetails']['featursemenu']) ? $json_data['HomePageDetails']['featursemenu'] : '';
                    
                    
                    if (!empty($logo) /*&& empty($PreviousLogoUrl)*/) {
                        $imgname = 'Logo';
                        $logoresult = $this->moveUploadedFile($parentUrl, $logo, $imgname);
                        $logoUrl = $baseurl . $logoresult;
                        $subscreen->logo = $logoUrl;
                    } else {
                        $subscreen->logo = $PreviousLogoUrl;
                    }
                    if (!empty($bgimg) /*&& empty($PreviousbgUrl)*/) {
                        $imgname = 'Background';
                        $bgersult = $this->moveUploadedFile($parentUrl, $bgimg, $imgname);
                        $backgruondimg = $baseurl . $bgersult;
                        $subscreen->bg_img = $backgruondimg;
                    } else {
                        $subscreen->bg_img = $PreviousbgUrl;
                    }
                    if (!empty($welcome_body) /*&& empty($Previouswlcomtxt)*/) {
                        $subscreen->welcome = $welcome_head;
                        $subscreen->welcome_txt = $welcome_body;

                    } else {
                        $subscreen->welcome = '';
                        $subscreen->welcome_txt = $Previouswlcomtxt;
                    }
                    $subscreen->featursemenu = $previousfeature;


                    $menudata = isset($json_data['HomePageDetails']['menu']) ? $json_data['HomePageDetails']['menu'] : array();
                    

                    $subscreen->menu = $menudata;

                } else {

                    if (!empty($logo)) {
                        $imgname = 'Logo';
                        $logoresult = $this->moveUploadedFile($parentUrl, $logo, $imgname);
                        $logoUrl = $baseurl . $logoresult;
                        $subscreen->logo = $logoUrl;
                    }
                    if (!empty($bgimg)) {
                        $imgname = 'Background';
                        $bgersult = $this->moveUploadedFile($parentUrl, $bgimg, $imgname);
                        $backgruondimg = $baseurl . $bgersult;
                        $subscreen->bg_img = $backgruondimg;
                    }
                    if (!empty($welcome_body)) {

                        $subscreen->welcome_txt = $welcome_body;
                        $subscreen->welcome = $welcome_head;
                    }

                    $rewriteJson = $this->rewriteGuestJsonobject($logoUrl, $backgruondimg, $hotelid, $tempid);
                }

                // if ($return == 1) {
                //     $menudetails = new stdClass();
                //     $menudetails->menuid = '';
                //     $menudetails->title = '';
                //     $menuList[] = $menudetails;
                // }
            }


            //$subscreen->menu = $menuList;

            $jsonData = json_encode($homeSreen, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            if (!file_exists($JsonUrl)) {
                mkdir($basejsonurl, 0777, true);
                // Create the file and write the content
                // $result = file_put_contents($JsonUrl, $jsonData);
            }
            /*else{
                         
                     }*/
            file_put_contents($JsonUrl, $jsonData);
            return $homeSreen;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }



    public function gettemplateDetailsModel($hotelid, $tempid, $userid, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'gettemplateDetailsModel');

        try {
            $templateDetails = new stdClass();
            if ($hotelid == 0) {
                throw new WizardSetupException('Hotel Id Required', 401);
            }

            $templateQry = "select lc.brandid,(select bd.brandname from brand_details as bd where bd.id=lc.brandid) as brandname,lc.hotelname,lc.custmail,
            lc.location,lc.mobileno,lc.spocname,lc.address,lc.tempid,lc.channelfeedtype,tm.templatename,cf.channeltype
             FROM locations as lc 
             left join template as tm on tm.id = lc.tempid 
             left join channelfeed as cf on cf.id=lc.channelfeedtype where lc.id=" . $hotelid;

            $featureQury = "select fl.id,fl.name  from  hotelfeaturelist as hf  left join featurelist as fl on fl.id = hf.featureid
             where hf.hotelid=" . $hotelid;

            $channelQry = "select tvc.channelno,tvl.channelname,tvl.channellogo,tvc.channelip,tvc.channelport
            ,if(cg.categoryid is null,0,cg.categoryid) categoryid,if(cgc.categoryname is null,'Unknown',cgc.categoryname) 
            categoryname from tvchannels as tvc 
            left join tvchannelslist as tvl on tvc.channellistid = tvl.id
            left join channelgroup as cg on   cg.channelid=tvl.id and cg.hotelid = tvc.hotelid
            left join channelcategory as cgc on cgc.id = cg.categoryid
            where tvc.hotelid=" . $hotelid;

            $objLogger->info('Query : ' . $templateQry);
            $objLogger->info('Query : ' . $featureQury);
            $objLogger->info('Query : ' . $channelQry);

            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $templatelist = $dbObjt->getSingleDatasByObjects($templateQry, 'YES');
            $featurelist = $dbObjt->getSingleDatasByObjects($featureQury, 'YES');
            $channelList = $dbObjt->getSingleDatasByObjects($channelQry, 'YES');
            $HomeScreen = $this->getJsonDataModel($hotelid, $tempid, $userName,'home.json');
            $guestService = $this->getJsonDataModel($hotelid, $tempid, $userName,'guest_service.json');
            $hotelinfo = $this->getJsonDataModel($hotelid, $tempid, $userName,'hotel_info.json');
            

            if (!empty($templatelist)) {

                $templateDetails->template = $templatelist;
            } else {
                $templateDetails->template = '';
            }

            if (!empty($featurelist)) {
                $templateDetails->features = $featurelist;
            } else {
                $templateDetails->features = '';
            }

            if (!empty($channelList)) {
                $templateDetails->channels = $channelList;
            } else {
                $templateDetails->channels = '';
            }

            if (!empty($HomeScreen)) {
                $templateDetails->Homescreen = $HomeScreen;
            } else {
                $templateDetails->Homescreen = '';
            }
            $templateDetails->guestService = (!empty($guestService))?$guestService :'';

            $templateDetails->hotelInformation = (!empty($hotelinfo))?$hotelinfo :'';

            //print_R($templatelist);die();

            return $templateDetails;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            $objLogger->error("Error Trace String : " . $ex->getTraceAsString());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), 401);
            } else {
                throw new WizardSetupException('Channel credentials invalid', 401);
            }
        }
    }

    public function getJsonDataModel($hotelid, $templateid, $userName,$filename)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        try {

            $file = "../public/template/" . $hotelid . "/" . $templateid . "/".$filename;

            //print_r($file);die();
            $json_data = '';
            if (file_exists($file)) {
                $json_file = file_get_contents($file);
                //print_r($json_file);die();
                $json_data = json_decode($json_file, true);
            }


            //print_r($json_data);die();

            //if(count($file)>=1)
            $objLogger->info('Json Data : ' . json_encode($json_data));

            return $json_data;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    public function updatefeedmodel($feedtype, $hotelid, $userid, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        try {

            $action = 'UPDATEFEED';
            $sqlQuery = "CALL SP_WizardSetup('" . $action . "',0,$hotelid,$feedtype,$userid)";

            $objLogger->info('Query : ' . $sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $updateTemp = $dbObjt->getSingleDatasByObjects($sqlQuery);

            return $updateTemp;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }


    public function saveDetails($JWTdata, $userid, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        try {
            $data = json_decode(json_encode($JWTdata), false);

            $hotelid = isset($data->hotelid) ? $data->hotelid : '';
            $feedtype = isset($data->channelfeedtye) ? $data->channelfeedtye : '';
            $channel = isset($data->channelno) ? $data->channelno : '';


            $j = 0;
            foreach ($channel as $feed) {

                $channelno = isset($data->channelno) ? $data->channelno[$j] : '';
                $channelid = isset($data->channelid) ? $data->channelid[$j] : '';
                $channelcategoryid = isset($data->channelcategory) ? $data->channelcategory[$j] : '';
                $multicastip = isset($data->multicastip) ? $data->multicastip[$j] : '';
                $channelport = isset($data->portno) ? $data->portno[$j] : '';
                $channelfrequency = isset($data->channelfrequency) ? $data->channelfrequency[$j] : '';

                $sqlQuery = "call SP_AddandEdithotelChannelInfo('ADD',$channelno,'$multicastip' ,$channelport ,
                $channelcategoryid,1,$channelid,$feedtype,'$channelfrequency',0,$hotelid,$userid)";

                $objLogger->info('Query : ' . $sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);

                $j++;
            }
            if (!empty($insResult->msg)) {

                return $insResult;
            }
            //print_r($insResult);die();
            //$objLogger->info('update Return : '.json_encode($insResult));



            //return $insResult;

        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    public function updateChannelWizard($JWTdata, $tvchannelid, $userid, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        try {
            $data = json_decode(json_encode($JWTdata), false);

            $hotelid = isset($data->hotelid) ? $data->hotelid : '';
            $feedtype = isset($data->channelfeedtye) ? $data->channelfeedtye : '';
            $tvchannelid = isset($tvchannelid) ? $tvchannelid : '0';
            $channelno = isset($data->channelno) ? $data->channelno : '';
            $channelid = isset($data->channelid) ? $data->channelid : '';
            $channelcategoryid = isset($data->channelcategory) ? $data->channelcategory : '';
            $multicastip = isset($data->multicastip) ? $data->multicastip : '';
            $channelport = isset($data->portno) ? $data->portno : '';
            $channelfrequency = isset($data->channelfrequency) ? $data->channelfrequency : '';
            $status = isset($data->status) ? $data->status : '1';



            $sqlQuery = "call SP_AddandEdithotelChannelInfo('UPDATE',$channelno,'$multicastip' ,$channelport ,
                $channelcategoryid,$status,$channelid,$feedtype,'$channelfrequency',$tvchannelid,$hotelid,$userid)";

            $objLogger->info('Query : ' . $sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);

            if (!empty($insResult->msg)) {

                return $insResult;
            }
            //print_r($insResult);die();
            //$objLogger->info('update Return : '.json_encode($insResult));



            //return $insResult;

        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    /**
     * Summary of getinstemptblDetails
     * @param mixed $fileNamewithpath
     * @param mixed $hotelid
     * @param mixed $channelfeedtype
     * @param mixed $userid
     * @param mixed $userName
     * @throws \App\Exception\WizardSetup\WizardSetupException
     * @return array
     */
    public function getinstemptblDetails($fileNamewithpath, $hotelid, $channelfeedtype, $userid, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        try {

            $sqlQuery = "DELETE FROM tempchnlbulkupload";
            $objLogger->info('DelQuery : ' . $sqlQuery);
            $dbObjtDel = new DB($this->loggerFactory, $this->dBConFactory);
            $delResult = $dbObjtDel->insOrUpdteOrDetQuery($sqlQuery);
            if ($delResult)
                $objLogger->info('delete reslut : success');
            else
                $objLogger->info('delete reslut : failuare');

            $blkdata = array();
            $isAVilSuccessData = false;
            $objtCon = $this->dBConFactory->getConnection();


            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($fileNamewithpath);
            $d = $spreadsheet->getSheet(1)->toArray();

            $worksheet = $spreadsheet->getActiveSheet();

            foreach ($worksheet->getRowIterator() as $ChannelData) {

                if ($ChannelData->getRowIndex() == 1) {

                    $firstCell = $worksheet->getCellByColumnAndRow(1, 1)->getValue();
                    //print_r(($firstCell));die();
                    if (trim(strtoupper($firstCell)) == 'CHANNEL NO') {
                        continue;
                    } else {
                        throw new WizardSetupException('Please upload a valid files', 201);
                        exit();
                        break;
                    }
                }

                $rowIndex = $ChannelData->getRowIndex();
                $channelno = $worksheet->getCellByColumnAndRow(1, $rowIndex)->getValue();
                $channelname = $worksheet->getCellByColumnAndRow(2, $rowIndex)->getValue();

                $channeltype = $worksheet->getCellByColumnAndRow(3, $rowIndex)->getValue();
                $portno = $worksheet->getCellByColumnAndRow(4, $rowIndex)->getValue();

                $multicastip = $worksheet->getCellByColumnAndRow(5, $rowIndex)->getValue();
                $channelfrequency = $worksheet->getCellByColumnAndRow(6, $rowIndex)->getValue();
                $hotelname = $worksheet->getCellByColumnAndRow(7, $rowIndex)->getValue();


                if (empty($portno)) {
                    $insQuery = "INSERT INTO tempchnlbulkupload(channelno,channelname,channelcategory, portno, multicastip, 
                        channelfrequency,hotelid,channelfeedtype, createdOn, createdBy)
                        VALUES(" . $channelno . ", '" . $channelname . "', '" . $channeltype . "', NULL, '" . $multicastip . "', 
                        '" . $channelfrequency . "', '" . $hotelid . "'," . $channelfeedtype . ", SYSDATE(), " . $userid . ")";
                } else {
                    $insQuery = "INSERT INTO tempchnlbulkupload(channelno,channelname,channelcategory, portno, multicastip, 
                        channelfrequency,hotelid,channelfeedtype, createdOn, createdBy)
                        VALUES(" . $channelno . ", '" . $channelname . "', '" . $channeltype . "', " . $portno . ", '" . $multicastip . "', 
                        '" . $channelfrequency . "', '" . $hotelid . "'," . $channelfeedtype . ", SYSDATE(), " . $userid . ")";
                }


                $objLogger->info('insQuery : ' . $insQuery);

                $result = mysqli_query($objtCon, $insQuery);

                $errorMsg = mysqli_error($objtCon);
                if ($result) {
                    $isAVilSuccessData = true;
                    $objLogger->info('Result status : inserted successfully');
                } else {
                    $objLogger->info('Result status : not inserted');
                    $objLogger->info('errorMsg : ' . $errorMsg);
                }
            }

            $this->dBConFactory->close($objtCon);

            if ($isAVilSuccessData == true) {
                $blkdata = $this->bulkuploadToDB($hotelid, $userid);
                return $blkdata;
            } else {
                throw new WizardSetupException('Bulk upload failure', 401);
            }

            return $blkdata;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    public function bulkuploadToDB($hotelid, $userid)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userid, 'WizardSetupModel');
        $blkupldData = array();
        try {

            $success = 0;
            $failure = 0;

            $sqlQuery = "call SP_channelBulkUpload (" . $userid . ", " . $hotelid . ")";

            $objLogger->info('Query : ' . $sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getMultiDatasByArray($sqlQuery);
            //print_r($insResult);die();
            //$objLogger->info('update Return : '.json_encode($insResult));



            if (!empty($insResult)) {
                for ($i = 0; $i < count($insResult); $i++) {
                    $row = $insResult[$i];
                    if (!empty($row['ErrorStatus']) && strtoupper($row['ErrorStatus']) == 'SUCCESS') {
                        $success = $success + 1;
                    } else {
                        $failure = $failure + 1;
                    }
                }
            }

            $blkupldData['successcount'] = $success;
            $blkupldData['failuarecount'] = $failure;
            $blkupldData['data'] = $insResult;

            return $blkupldData;

        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    public function getTemplateName($tempateid, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        $sqlQuery = "select count(*) as cnt from template where id=" . $tempateid;

        $objLogger->info('Query : ' . $sqlQuery);
        $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
        $getSingleResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
        if ($getSingleResult) {

            if ($getSingleResult->cnt > 0) {
                $returnQry = "select templatename as name from template where id=" . $tempateid;
                $objLogger->info('Query : ' . $returnQry);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $getName = $dbObjt->getSingleDatasByObjects($returnQry);
                //print_r($getName);die();
                //echo($getName->name);die();
                return $getName->name;
            } else {
                return '';
            }
            // print_R($getSingleResult);die();
        }


    }

    public function moveUploadedFile($directory, $orginalName, $filename)
    {
        // print_r(($orginalName->getClientFilename()));die();
        $ImageName = $orginalName->getClientFilename();


        $ext = pathinfo($ImageName, PATHINFO_EXTENSION);

        $newName = $filename . '.' . $ext;

        if ($orginalName->getError() === UPLOAD_ERR_OK) {

            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $orginalName->moveTo($directory . DIRECTORY_SEPARATOR . $newName);

        }
        //echo $newName;die();
        //$orginalName->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $newName;
    }

    public function getCommonCount($name)
    {

        $Qry = '';
        if (strtolower($name) == 'channel') {
            $Qry = "select count(*) as cnt from tvchannelslist";
        } else {
            $Qry = "select count(*) as cnt from channelcategory";
        }

        $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);

        $getCount = $dbObjt->getSingleDatasByObjects($Qry);
        //print_r($getCount->cnt);die();
        if ($getCount) {
            return $getCount->cnt;
        } else {
            return '';
        }

    }

    public function getchannels()
    {

        $Qry = 'select channelname from tvchannelslist';


        $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);

        $getCount = $dbObjt->getMultiDatasByObjects($Qry);
        //print_r($getCount->cnt);die();
        if ($getCount) {
            return $getCount;
        } else {
            return '';
        }

    }

    public function getcategory()
    {

        $Qry = 'select categoryname from channelcategory';


        $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);

        $getCountList = $dbObjt->getMultiDatasByObjects($Qry);
        //print_r($getCount->cnt);die();
        if ($getCountList) {
            return $getCountList;
        } else {
            return '';
        }

    }

    public function gethomescreenObject($hotelid, $tempid, $userid, $userName)
    {

        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        try {

            $file = "../public/template/" . $hotelid . "/" . $tempid . "/home.json";

            //print_r($file);die();
            $json_data = '';
            if (file_exists($file)) {
                $json_file = file_get_contents($file);
                //print_r($json_file);die();
                $json_data = json_decode($json_file, true);
            } else {
                $json_data = "No Records Found";
            }

            $objLogger->info('Json Data : ' . json_encode($json_data));

            return $json_data;

        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }


    public function getGuestobject($hotelid, $tempid, $userid, $userName)
    {

        $objLogger = $this->loggerFactory->getFileObject('WizardSetupModel_' . $userName, 'WizardSetupModel');
        try {

            $file = "../public/uploads/menujson/guest_service.json";

            //print_r($file);die();
            $json_data = '';
            if (file_exists($file)) {
                $json_file = file_get_contents($file);
                //print_r($json_file);die();
                $json_data = json_decode($json_file, true);
            }

            $objLogger->info('Json Data : ' . json_encode($json_data));

            return $json_data;

        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 401);
            }
        }
    }

    public function rewriteGuestJsonobject($logoUrl, $backgruondimg, $hotelid, $templateid)
    {
        $home = "../public/uploads/menujson/guest_service.json";
        //$testPage = "../public/uploads/menujson";

        $json_data = '';
        // $files = glob($testPage.'/*json');
        // print_r($files);die();
        if (file_exists($home)) {
            $jsonobject = new stdClass();
            $json_new = new stdClass();

            $json_file = file_get_contents($home);

            $json_data = json_decode($json_file, true);

            if (isset($json_data['guestServices'])) {
                $jsonobject->guestServices = $json_new;
                $json_new->logo = $logoUrl;
                $json_new->home_bgimg = $backgruondimg;
                $json_new->bgimg = $backgruondimg;
                $json_new->title = $json_data['guestServices']['title'];
                $json_new->subtext = $json_data['guestServices']['subtext'];
                $sevicelist = array();

                $service = $json_data['guestServices']['Services'];
                for ($j = 0; $j < count($service); $j++) {

                    $submenu = new stdClass();
                    $data = $service[$j];
                    $submenu->servicename = $data['servicename'];
                    $submenu->servicelogo = $data['servicelogo'];
                    $submenu->servicelink = $data['servicelink'];
                    $submenu->serviceInfo = $data['serviceInfo'];
                    $sevicelist[] = $submenu;

                }
                $json_new->Services = $sevicelist;
                $json_new->Footer = $json_data['guestServices']['Footer'];
                $json_new->Remote = $json_data['guestServices']['Remote'];



                $jsonData = json_encode($jsonobject, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

                file_put_contents($home, $jsonData);

            }

        }
    }

    public function GethomescreenDetails($hotelid, $templateid, $varname)
    {
        $home = "../public/template/" . $hotelid . "/" . $templateid . "/home.json";
        //$testPage = "../public/uploads/menujson";

        $json_data = '';
        // $files = glob($testPage.'/*json');
        // print_r($files);die();
        $jsonobject = '';
        if (file_exists($home)) {
            $jsonobject = new stdClass();

            $json_file = file_get_contents($home);

            $json_data = json_decode($json_file, false);

            if (isset($json_data->HomePageDetails)) {
                $jsonobject = isset($json_data->HomePageDetails->$varname)?$json_data->HomePageDetails->$varname:'';
            } else {
                $jsonobject = '';
            }

        }
        
        $jsonData = json_encode($jsonobject, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        if(empty($jsonData)){
            return '';
        }else{
            return $jsonobject;
        }

        

    }

    public function ReadJsonFile($hotelid, $templateid, $varname,$filename,$objectName)
    {
        $home = "../public/template/" . $hotelid . "/" . $templateid . "/".$filename;
        //$testPage = "../public/uploads/menujson";
       

        $json_data = '';
        // $files = glob($testPage.'/*json');
        // print_r($files);die();
        $jsonobject = '';
        if (file_exists($home)) {
            $jsonobject = new stdClass();

            $json_file = file_get_contents($home);

            $json_data = json_decode($json_file, false);

            if (isset($json_data->$objectName)) {
                $jsonobject = isset($json_data->$objectName->$varname)?$json_data->$objectName->$varname:'';
            } else {
                $jsonobject = '';
            }

        }
        
        $jsonData = json_encode($jsonobject, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        if(empty($jsonData)){
            return '';
        }else{
            return $jsonobject;
        }

        

    }

    public function unique_key($array,$keyname){

        $new_array = array();
        foreach($array as $key=>$value){
     
            if(!isset($new_array[$value->$keyname])){
                $new_array[$value->$keyname] = $value;
               }  
       
        }
        $new_array = array_values($new_array);
        return $new_array;
       }

}