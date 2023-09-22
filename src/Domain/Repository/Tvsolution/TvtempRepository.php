<?php

namespace App\Domain\Repository\Tvsolution;
use App\Exception\Tvsolution\TvtempException;
use App\Model\TvtempModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Domain\Service\Tvsolution\TvtempService;

class TvtempRepository extends BaseRepository implements TvtempService
{
    
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function getAlltemplate($inputdata,$auditBy){
        $objLogger = $this->loggerFactory->getFileObject('TvAction_'.$auditBy, 'TvtempRepository');
        $objLogger->info("======= Start TV Repository (Get All Template List)================");
        try{
            $templatemodel = new TvtempModel($this->loggerFactory, $this->dBConFactory);
            $action = 'LIST';
            $templist = $templatemodel->getAllListtemplate($auditBy, $action);
            $objLogger->info("======= End TV Repository (Get All Template List)================");
            return $templist;
        }
        catch(TvtempException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End TV Repository (Get All Template List)================");

            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException('Invalid Access', 401);
            }
        }
    }
	
	public function getallchannelfeed($inputdata,$auditBy){
        $objLogger = $this->loggerFactory->getFileObject('TvAction_'.$auditBy, 'TvtempRepository');
        $objLogger->info("======= Start TV Repository (Get All ChannelFeed List) ================");
        try{
            $templatemodel = new TvtempModel($this->loggerFactory, $this->dBConFactory);
            $action = 'CHNLTYPE';
            $templist = $templatemodel->getallchannelfeedsmodel($auditBy, $action);
            $objLogger->info("======= End TV Repository (Get All ChannelFeed List) ================");
            return $templist;
        }
        catch(TvtempException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End TV Repository (Get All ChannelFeed List) ================");

            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException('Invalid Access', 401);
            }
        }
    }
	
	public function getallfeatures($inputdata,$auditBy){
        $objLogger = $this->loggerFactory->getFileObject('TvAction_'.$auditBy, 'TvtempRepository');
        $objLogger->info("======= Start TV Repository (Get All Feature List)================");
        try{
			$featurelist = new \stdClass();
            $templatemodel = new TvtempModel($this->loggerFactory, $this->dBConFactory);
            $action = 'FEATURE';
            $templist = $templatemodel->getallfeaturesModel($auditBy, $action);
            $objLogger->info("======= End TV Repository (Get All Feature List)================");
			$featurelist->chkstat = false;
            return $templist;
        }
        catch(TvtempException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End TV Repository (Get All Feature List)================");

            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException('Invalid Access', 401);
            }
        }
    }

    public function getJsonfile($templateid,$auditBy,$userName){
        $objLogger = $this->loggerFactory->getFileObject('TvAction_'.$userName, 'TvtempRepository');
        $objLogger->info("======= Start TV Repository (Read Json File )================");
        try{
            if($templateid == 0){
                throw new TvtempException("Template id required",201);
            }
            $templatemodel = new TvtempModel($this->loggerFactory, $this->dBConFactory);
           
            $templist = $templatemodel->getJsonDataModel($auditBy, $templateid,$userName);
            $objLogger->info("======= End TV Repository (Read Json File )================");
            return $templist;
        }
        catch(TvtempException $ex){

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End TV Repository (Read Json File )================");

            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException('Invalid Access', 401);
            }
        }
    }
}
