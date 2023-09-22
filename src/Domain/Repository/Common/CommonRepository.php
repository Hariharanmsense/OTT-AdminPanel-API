<?php

namespace App\Domain\Repository\Common;
use App\Exception\Common\CommonException;
use App\Model\CommonModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Domain\Service\Common\CommonService;
use stdClass;

class CommonRepository extends BaseRepository implements CommonService
{
    
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function getAlllatestHotels($auditBy){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository (getAlllatestHotels) ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'ALLLATESTHOTEL';
            $sideMenus = $commonModel->getAllLstZroRecrds(0, $auditBy, $action);
            $objLogger->info("======= End Common Repository (getAlllatestHotels) ================");
            return $sideMenus;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository (getAlllatestHotels) ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllIneterfaceType($auditBy){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository (getAllIneterfaceType) ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'ALLINTERFACETYPE';
            $sideMenus = $commonModel->getAllLstZroRecrds(0, $auditBy, $action);
            $objLogger->info("======= End Common Repository (getAllIneterfaceType) ================");
            return $sideMenus;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository (getAllIneterfaceType) ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllAvaliableHotelByGroup($auditBy, $groupId){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository (getAllAvaliableHotelByGroup) ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'AVALIABLEHOTELALLBYGROUP';
            $sideMenus = $commonModel->getAllLstZroRecrds($groupId, $auditBy, $action);
            $objLogger->info("======= End Common Repository (getAllAvaliableHotelByGroup) ================");
            return $sideMenus;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository (getAllAvaliableHotelByGroup) ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllallowedHotelsByGroup($auditBy, $groupId){

        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository (getAllallowedHotelsByGroup) ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'ALLOWEDHOTELALLBYGROUP';
            $sideMenus = $commonModel->getAllLstZroRecrds($groupId, $auditBy, $action);
            $objLogger->info("======= End Common Repository (getAllallowedHotelsByGroup) ================");
            return $sideMenus;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository (getAllallowedHotelsByGroup) ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getMenuRightsByHotelMenu($auditBy, $menuid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository (getMenuRightsByHotelMenu) ================");
        try{

            if(empty($menuid)){
                throw new CommonException('Menu Id empty', 201);
            }

            if(empty($hotelid)){
                throw new CommonException('Hotel Id empty', 201);
            }

            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'SINGLEMENURIGHTS';
            $sideMenus = $commonModel->getSingleMenuRecrds($menuid, $hotelid, $auditBy, $action);
            $objLogger->info("======= End Common Repository (getMenuRightsByHotelMenu) ================");
            return $sideMenus;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository (getMenuRightsByHotelMenu) ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllallowedHotels($auditBy, $brandId){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository (getAllallowedHotels) ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'ALLOWEDHOTELALL';
            $sideMenus = $commonModel->getAllLstZroRecrds($brandId, $auditBy, $action);
            $objLogger->info("======= End Common Repository (getAllallowedHotels) ================");
            return $sideMenus;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository (getAllallowedHotels) ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllallowedBrands($auditBy){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository (getAllallowedBrands) ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'ALLOWEDBRANDALL';
            $sideMenus = $commonModel->getAllLstZroRecrds(0, $auditBy, $action);
            $objLogger->info("======= End Common Repository (getAllallowedBrands) ================");
            return $sideMenus;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository (getAllallowedBrands) ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllSubMenus($auditBy, $menuid, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'SIDEBARSUBMENUALL';
            $sideMenus = $commonModel->getAllListDataZeroRecords($menuid, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $sideMenus;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllInternetLists($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{

            if(empty($hotelid)){
                throw new CommonException('Hotel Id Empty', 201);
            }

            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'INTERNETALL';
            $internetlst = $commonModel->getAllListData($hotelid, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $internetlst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllTimeZone($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'TIMEZONEALL';
            $tzones = $commonModel->getAllListData(0, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $tzones;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllReadWriteMenus($input, $auditBy){

        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $data            = json_decode(json_encode($input), false);
            $accessStatus     = isset($data->accessStatus)?$data->accessStatus : 'RL';
            $groupId     = isset($data->groupId)?$data->groupId : '';
            $hotelId     = isset($data->hotelId)?$data->hotelId : '';

            if(empty($accessStatus)){
                throw new CommonException('Access Status Empty', 201);
            }

            if(empty($groupId)){
                throw new CommonException('Group Id Empty', 201);
            }

            if(empty($hotelId)){
                throw new CommonException('Hotel Id Empty', 201);
            }

            if($accessStatus != 'RL' && $accessStatus != 'RW'){
                throw new CommonException('Invalid Access Status', 201);
            }

            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $menulst = $commonModel->getAllReadWriteMenus($accessStatus, $groupId, $hotelId, $auditBy);
            $objLogger->info("======= End Common Repository ================");
            return $menulst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllAvailableMenus($input, $auditBy){

        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $data = json_decode(json_encode($input), false);
            $hotelId =isset($data->hotelId)?$data->hotelId:'';
            $groupId =isset($data->groupId)?$data->groupId:'';

            if(empty($hotelId)){
                throw new CommonException('Hotel Id is empty', 201);
            }

            if($groupId == ''){
                throw new CommonException('group Id is empty', 201);
            }

            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'AVAILABLEMENUS';
            $menulst = $commonModel->getAssignOrAvailMenus($hotelId, $groupId, $auditBy, $action);
            $objLogger->info("======= End Common Repository ================");
            return $menulst;

        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllAssignMenus($input, $auditBy){

        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $data = json_decode(json_encode($input), false);
            $hotelId =isset($data->hotelId)?$data->hotelId:'';
            $groupId =isset($data->groupId)?$data->groupId:'';

            if(empty($hotelId)){
                throw new CommonException('Hotel Id is empty', 201);
            }

            if(empty($groupId)){
                throw new CommonException('group Id is empty', 201);
            }

            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'ASSIGNEDMENUS';
            $menulst = $commonModel->getAssignOrAvailMenus($hotelId, $groupId, $auditBy, $action);
            $objLogger->info("======= End Common Repository ================");
            return $menulst;

        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllHotel($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $action = 'HOTELALL';
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $hotellst = $commonModel->getAllListData(0, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $hotellst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getMenuRightAccess($menuid, $auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $action = 'MENUACCESSDETAILS';
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $hotellst = $commonModel->getAllListData($menuid, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $hotellst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllSideBarMenu($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{

            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'SIDEBARMENUPARENTALL';
            $parentMenu = $commonModel->getAllListDataZeroRecords(0, $auditBy, $action, $brandid, $hotelid);
            $sidebarMenu = array();
            foreach($parentMenu as $menu){
                $menuid = $menu['menuId'];

                $action = 'MENUACCESSDETAILS';
                $accessdetails = $commonModel->getAllListDataZeroRecords($menuid, $auditBy, $action, $brandid, $hotelid);
                $menu['accessDetails'] = $accessdetails;
                $menu['matIcon'] = false;
                $menu['groupTitle'] = false;
                $action = 'SIDEBARSUBMENUALL';
                $sideMenu = $commonModel->getAllListDataZeroRecords($menuid, $auditBy, $action, $brandid, $hotelid);
                $subMenu = array();
                foreach($sideMenu as $cmenu){
                    $childMenuId = $cmenu['menuId'];
                    $action = 'MENUACCESSDETAILS';
                    $accessdetails = $commonModel->getAllListDataZeroRecords($childMenuId, $auditBy, $action, $brandid, $hotelid);
                    $cmenu['accessDetails'] = $accessdetails;
                    $cmenu['groupTitle'] = false;
                    $cmenu['class'] = 'ml-menu';
                    $subMenu[] = $cmenu;
                }
                if(!empty($subMenu)){
                    $menu['class'] = 'menu-toggle';
					$menu['subMenu'] = $subMenu;
				}
                else {
                    $menu['class'] = '';
                }
                $sidebarMenu[] = $menu;
            }

            //print_r(json_encode($sidebarMenu));die();
            $objLogger->info("======= End Common Repository ================");
            return $sidebarMenu;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllDeviceStatus($auditBy, $brandid, $hotelid) {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'DEVICESTATUSALL';
            $devicelst = $commonModel->getAllListData(0, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $devicelst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllIcmpPolicys($auditBy, $brandid, $hotelid) {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{

            if(empty($hotelid)){
                throw new CommonException('Hotel Id Empty', 201);
            }
            
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'ICMPPOLICYALL';
            $devicelst = $commonModel->getAllListData($hotelid, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $devicelst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllDeviceLocations($auditBy, $brandid, $hotelid) {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'DEVICELOCATIONALL';
            $devicelst = $commonModel->getAllListData(0, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $devicelst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllDeviceTypes($auditBy, $brandid, $hotelid) {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'DEVICETYPEALL';
            $devicelst = $commonModel->getAllListData(0, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $devicelst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllDevices($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'ASSETALL';
            $devicelst = $commonModel->getAllListData($hotelid, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $devicelst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }
	
	public function getAllGroup($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'GROUPALL';
            $brandlst = $commonModel->getAllListData(0, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $brandlst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllBrand($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'BRANDALL';
            $brandlst = $commonModel->getAllListData(0, $auditBy, $action, $brandid, $hotelid);
            $objLogger->info("======= End Common Repository ================");
            return $brandlst;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 201);
            }
        }
    }

    public function getAllottSideBarMenu($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{

            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'SIDEBARMENUPARENTALL';
            $parentMenu = $commonModel->getAllListDataZeroRecords(0, $auditBy, $action, $brandid, $hotelid);
            $sidebarMenu = array();
            $ottMainMenu  = new \stdClass();
            foreach($parentMenu as $menu){
                
                $ottsubMenu  = new \stdClass();
                //$ottMainMenu ->Menus = $ottsubMenu ;
                $ottsubMenu ->path = $menu['menuUrl'];
                $ottsubMenu ->title = $menu['menuTitle'];
                $ottsubMenu ->type = $menu['type'];
                $ottsubMenu ->icon = $menu['MenuIcon'];
                $ottsubMenu ->menuid = $menu['menuId'];
                if($menu['active'] == "0"){
                    $ottsubMenu ->active = false;
                }else{
                    $ottsubMenu ->active = true;
                }
                //$ottsubMenu ->active = $menu['active'];
                $menuid = $menu['menuId'];

                $action = 'MENUACCESSDETAILS';
                $accessdetails = $commonModel->getAllListDataZeroRecords($menuid, $auditBy, $action, $brandid, $hotelid);
                $menu['accessDetails'] = $accessdetails;
            
                $action = 'SIDEBARSUBMENUALL';
                $sideMenu = $commonModel->getAllListDataZeroRecords($menuid, $auditBy, $action, $brandid, $hotelid);
                $subMenu = array();
                
                foreach($sideMenu as $cmenu){
                    $child = new \stdClass();
                    $child ->path = $cmenu['Url'];
                    $child ->title = $cmenu['MenuTitle'];
                    $child ->type = $cmenu['type'];
                    $child ->menuid = $cmenu['menuId'];
					
					if($cmenu['active'] == "0"){
						$child ->active = false;
					}else{
						$child ->active = true;
					}
                    
                    $childMenuId = $cmenu['menuId'];
                    $action = 'MENUACCESSDETAILS';
                    $accessdetails = $commonModel->getAllListDataZeroRecords($childMenuId, $auditBy, $action, $brandid, $hotelid);
                    $cmenu['accessDetails'] = $accessdetails;
                    //$subMenu[] = $cmenu; 
                    

                    $ottsubMenu->children[] = $child;
                    //print_r($child);
                   // $subMenu[] =  $child;
                }
                //print_r($subMenu);die();
                //$menu['subMenu'] = $subMenu;
               // $ottsubMenu->children = $subMenu;
                //$sidebarMenu[] = $menu;
                $sidebarMenu[] = $ottsubMenu;
            }
            $ottMainMenu->Menu = $sidebarMenu;
            //print_r(json_encode($sidebarMenu));die();
            $objLogger->info("======= End Common Repository ================");
            return $ottMainMenu;
        }
        catch(CommonException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Common Repository ================");

            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException('Invalid Access', 401);
            }
        }
    }
}
