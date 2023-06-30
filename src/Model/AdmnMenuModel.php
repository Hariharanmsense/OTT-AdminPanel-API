<?php

namespace App\Model;

use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\AdmnMenu\AdmnMenuException;
use App\Model\DB;
use App\Model\CommonModel;


class AdmnMenuModel extends BaseModel
{
    
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

   

    public function assginMenu($groupId, $hotelId, $readMenus, $writeMenus, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('AdmnMenuAction_'.$auditBy, 'AdmnMenuModel');
        try{

            $sqlDelete = "DELETE FROM tempadminmenugrp";
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $deleteResult = $dbObjt->insOrUpdteOrDetQuery($sqlDelete);

            if($deleteResult){
                $objLogger->info('temp adminmeugroup deleted successfully'); 
            }
            else {
                $objLogger->info('temp adminmeugroup not deleted'); 
            }

            $toRedOnlyMenuIds = array();
            if(!empty($readMenus)){
                $toRedOnlyMenuIds = explode(",",$readMenus);
            }

            $toRedWriteMenuIds = array();
            if(!empty($writeMenus)){
                $toRedWriteMenuIds = explode(",",$writeMenus);
            }

            $toMenuIds = array();
		    $toMenuIds = array_merge($toRedOnlyMenuIds, $toRedWriteMenuIds);
            if(is_array($toMenuIds) && count($toMenuIds) >=1){
                foreach($toMenuIds as $MenuId){
                    if(in_array($MenuId, $toRedOnlyMenuIds)){
                        $menuRight = 1;
                    }
                    else {
                        $menuRight = 2;
                    }

                    $sqlQuery = " INSERT INTO tempadminmenugrp(GroupId, MenuId, ReadWriteAccess, HotelId) 
							  VALUES('".$groupId."', '".$MenuId."', '".$menuRight."', '".$hotelId."') ";
                    
                    $objLogger->info('Insert Query : '.$sqlQuery);
                    $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                    $result = $dbObjt->insOrUpdteOrDetQuery($sqlQuery);
                    if($result){
                        $objLogger->info('inserted successfully'); 
                    }
                    else {
                        $objLogger->info('not inserted'); 
                    }
                }
                $action = 'ADD';
                $sqlPrco = "CALL SP_AssignMenuConfig(".$groupId.", ".$hotelId.", ".$auditBy.", '".$action."') ";
                $objLogger->info('Query : '.$sqlPrco); 
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $insResult = $dbObjt->getSingleDatasByObjects($sqlPrco);
                $objLogger->info('Insert Return : '.json_encode($insResult));
                if($insResult->ErrorCode == '00'){

                    return 'SUCCESS';
                }
                else {

                    throw new AdmnUsrsException($insResult->Result, 401);
                }
            }
            else {

                $action = 'DELETE';
                $sqlPrco = "CALL SP_AssignMenuConfig(".$groupId.", ".$hotelId.", ".$auditBy.", '".$action."') ";
                $objLogger->info('Query : '.$sqlPrco); 
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $insResult = $dbObjt->getSingleDatasByObjects($sqlPrco);
                $objLogger->info('DELETE Return : '.json_encode($insResult));
                if($insResult->ErrorCode == '00'){

                    return 'SUCCESS';
                }
                else {

                    throw new AdmnUsrsException($insResult->Result, 401);
                }
            }
        }
        catch (AdmnMenuException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new AdmnMenuException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnMenuException('Invalid Access', 401);
            }
        }
    }
}
