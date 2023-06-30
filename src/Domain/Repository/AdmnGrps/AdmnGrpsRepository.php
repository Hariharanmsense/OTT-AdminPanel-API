<?php

declare(strict_types=1);

namespace App\Domain\Repository\AdmnGrps;

use App\Domain\Service\AdmnGrps\AdmnGrpsService;
use App\Exception\AdmnGrps\AdmnGrpsException;
use App\Model\AdmnGrpModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;

class AdmnGrpsRepository extends BaseRepository implements AdmnGrpsService
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

    public function update($input, $groupid, $userid, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            if(empty($groupid)){
                throw new AdmnGrpsException('Group Id Empty', 201);
            }

            $data = json_decode(json_encode($input), false);
            $groupName = isset($data->groupName)?$data->groupName : '';

            if(empty($groupName)){
                throw new AdmnGrpsException('Group Name Empty', 201);
            }

            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnGrpModel->update($groupid, $groupName, $userid, $hotelid, $brandid);
            $objLogger->info("Insert Status : ".$insStatus);
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $insStatus;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 401);
            }
        }
    }

    public function create($input, $userid, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            $data = json_decode(json_encode($input), false);
            $groupName = isset($data->groupName)?$data->groupName : '';

            if(empty($groupName)){
                throw new AdmnGrpsException('Group Name Empty', 201);
            }

            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnGrpModel->create($groupName, $userid, $hotelid, $brandid);
            $objLogger->info("Insert Status : ".$insStatus);
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $insStatus;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 401);
            }
        }
    }

    public function getGrpOne($groupid, $userid, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository ================");
        try{
  
            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $grpData = $admnGrpModel->getGrpOne($groupid, $userid, $hotelid, $brandid);
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $grpData;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 401);
            }
        }
    }

    public function getGrpList($input, $userid, $hotelid, $brandid){
        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            $data            = json_decode(json_encode($input), false);
            $searchValue     = isset($data->searchValue)?$data->searchValue : '';
            $itemperPage     = isset($data->pageSize)?$data->pageSize  : '10';
            $currentPage     = isset($data->pageIndex)?$data->pageIndex  : '0'; 
            $sortName        = isset($data->sortName)?$data->sortName  : ''; 
            $sortOrder       = isset($data->sortOrder)?$data->sortOrder  : 'DESC'; 
            
            $limitFrom       = ($currentPage) * $itemperPage; 

            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $grplst = $admnGrpModel->getGrpList($userid, $hotelid, $brandid, $searchValue, $itemperPage, $currentPage, $sortName, $sortOrder, $limitFrom);
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $grplst;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 401);
            }
        }
    }

    public function getMenuRightStatus($userid, $menuid){

        $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction_'.$userid, 'AdmnGrpsRepository');
        $objLogger->info("======= Start AdmnGrps Repository ================");
        try{
            
            $admnGrpModel = new AdmnGrpModel($this->loggerFactory, $this->dBConFactory);
            $status = $admnGrpModel->getMenuRightStatus($userid, $menuid);
            $objLogger->info("Status : ".$status);
            if(empty($status)){
                throw new AdmnGrpsException('Invalid Access', 401);
            }
            $objLogger->info("======= End AdmnGrps Repository ================");
            return $status;
        }
        catch (AdmnGrpsException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnGrps Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException('Invalid Access', 401);
            }
        }
    }
}
