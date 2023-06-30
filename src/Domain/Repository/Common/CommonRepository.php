<?php

namespace App\Domain\Repository\Common;
use App\Exception\Common\CommonException;
use App\Model\CommonModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Domain\Service\Common\CommonService;

class CommonRepository extends BaseRepository implements CommonService
{
    
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function getAllTimeZone($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'TIMEZONEALL';
            $tzones = $commonModel->getAllListData($auditBy, $action, $brandid, $hotelid);
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
                throw new CommonException('Invalid Access', 401);
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
                throw new CommonException('Invalid Access Status', 401);
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
                throw new CommonException('Invalid Access', 401);
            }
        }
    }

    public function getAllAssignMenus($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'ASSIGNMENUALL';
            $menulst = $commonModel->getAllListData($auditBy, $action, $brandid, $hotelid);
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
                throw new CommonException('Invalid Access', 401);
            }
        }
    }

    public function getAllHotel($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $action = 'HOTELALL';
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $hotellst = $commonModel->getAllListData($auditBy, $action, $brandid, $hotelid);
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
                throw new CommonException('Invalid Access', 401);
            }
        }
    }

    public function getAllBrand($auditBy, $brandid, $hotelid){
        $objLogger = $this->loggerFactory->getFileObject('CommonAction_'.$auditBy, 'CommonRepository');
        $objLogger->info("======= Start Common Repository ================");
        try{
            $commonModel = new CommonModel($this->loggerFactory, $this->dBConFactory);
            $action = 'BRANDALL';
            $brandlst = $commonModel->getAllListData($auditBy, $action, $brandid, $hotelid);
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
                throw new CommonException('Invalid Access', 401);
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
                throw new CommonException('Invalid Access', 401);
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
            
                $action = 'SIDEBARSUBMENUALL';
                $sideMenu = $commonModel->getAllListDataZeroRecords($menuid, $auditBy, $action, $brandid, $hotelid);
                $subMenu = array();
                foreach($sideMenu as $cmenu){
                    $childMenuId = $cmenu['menuId'];
                    $action = 'MENUACCESSDETAILS';
                    $accessdetails = $commonModel->getAllListDataZeroRecords($childMenuId, $auditBy, $action, $brandid, $hotelid);
                    $cmenu['accessDetails'] = $accessdetails;
                    $subMenu[] = $cmenu;
                }
                $menu['subMenu'] = $subMenu;
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
                throw new CommonException('Invalid Access', 401);
            }
        }
    }
}
