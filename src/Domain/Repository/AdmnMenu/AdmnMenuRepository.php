<?php

namespace App\Domain\Repository\AdmnMenu;

use App\Domain\Service\AdmnMenu\AdmnMenuService;
use App\Exception\AdmnMenu\AdmnMenuException;
use App\Model\AdmnMenuModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;

class AdmnMenuRepository extends BaseRepository implements AdmnMenuService
{
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function assginMenu($input, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnMenuAction_'.$auditBy, 'AdmnMenuRepository');
        $objLogger->info("======= Start AdmnMenu Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
        
            $data = json_decode(json_encode($input), false);
            $groupId = isset($data->groupId)?trim($data->groupId):'';
            $hotelId = isset($data->hotelId)?trim($data->hotelId):'';
            $readMenus = isset($data->readMenus)?trim($data->readMenus):'';
            $writeMenus = isset($data->writeMenus)?trim($data->writeMenus):'';
        

            if(empty($groupId)){
                throw new AdmnMenuException('groupId Empty', 201);
            }

            if(empty($hotelId)){
                throw new AdmnMenuException('hotelId Empty', 201);
            }

            $admnMenuModel = new AdmnMenuModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $admnMenuModel->assginMenu($groupId, $hotelId, $readMenus, $writeMenus, $auditBy);
            $objLogger->info("Insert Status : ".$insStatus);
            $objLogger->info("======= End AdmnMenu Repository ================");
            return $insStatus;
        }
        catch (AdmnMenuException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End AdmnMenu Repository ================");
            if(!empty($ex->getMessage())){
                throw new AdmnMenuException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnMenuException('Invalid Access', 401);
            }
        }
    }

}
