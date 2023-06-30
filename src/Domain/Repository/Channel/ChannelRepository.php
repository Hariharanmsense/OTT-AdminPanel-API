<?php

declare(strict_types=1);

namespace App\Domain\Repository\Channel;

use App\Domain\Service\Channel\ChannelService;
use App\Exception\Channel\ChannelException;
use App\Model\ChannelModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use PhpParser\Node\Stmt\Catch_;

class ChannelRepository extends BaseRepository implements ChannelService
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

    /*View Channel list details */

    public function ViewChannellist($inputData){

        $userName = isset($inputData->decoded->userName)?$inputData->decoded->userName:"";    

        $objLogger = $this->loggerFactory->addFileHandler('ChannelModel_'.$userName.'.log')->createInstance('ChannelRepository');
        try{
           
            $userid = isset($inputData->decoded->id)?$inputData->decoded->id:"";
            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
           
            
            $ChannelModel = new ChannelModel($this->loggerFactory, $this->dBConFactory);
            $viewChannels = $ChannelModel->viewchannellist($userid,$userName);
            return $viewChannels;
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Channel credentials invalid', 201);
            }
        }
    }
    public function create($inputdata,$channelimg){
       
        $userName = isset($inputdata->decoded->userName)?$inputdata->decoded->userName:"";
        
        $objLogger = $this->loggerFactory->addFileHandler('ChannelModel_'.$userName.'.log')->createInstance('BrandRepository');
        try{
            

            //print_r($channelimg);die();

            $userid = isset($inputdata->decoded->id)?$inputdata->decoded->id:"";
           
            $channelname = isset($inputdata->channelname)?($inputdata->channelname):"";
            //$channelcategory = isset($inputdata->categoryid)?$inputdata->categoryid:"0";
            $channelimg = isset($channelimg)? ($channelimg):"";


            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
            if(empty($channelname)){
                throw new ChannelException('Channel Name required', 201);
            }

            if(empty($channelimg)){
                throw new ChannelException('Channel image required', 201);
            }
            
            
            $AddChannelModel = new ChannelModel($this->loggerFactory, $this->dBConFactory);
            
            $user = $AddChannelModel->createchannel($channelname, $channelimg,$userid,$userName);
            
            return $user;
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Channel credentials invalid', 201);
            }
        }
    }


    public function getOneChannel($channelid, $userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('ChannelAction_'.$userName, 'ChannelRepository');
        $objLogger->info("======= Start Channel Repository ================");
        try{
  
            $getoneChannelModel = new ChannelModel($this->loggerFactory, $this->dBConFactory);
            $grpData = $getoneChannelModel->getoneModel($channelid, $userid,$userName);
            $objLogger->info("======= End Channel Repository ================");
            return $grpData;
        }
        catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Channel Repository ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException('Invalid Access', 401);
            }
        }
    }


    public function update($inputdata,$channelid,$userid,$channelimg){
        $userName = isset($inputdata->decoded->userName)?$inputdata->decoded->userName:"";
        $objLogger = $this->loggerFactory->getFileObject('ChannelAction_'.$userName, 'ChannelRepository');
        $objLogger->info("======= Start Channel Repository ================");
        $objLogger->info("Input Data : ".json_encode($inputdata));
        try{

            
            $userid = isset($inputdata->decoded->id)?$inputdata->decoded->id:"";
            $channelname = isset($inputdata->channelname)?addslashes($inputdata->channelname):"";
            $channelimg = isset($channelimg)? ($channelimg):"";
			$channelid = isset($channelid)?$channelid:'0';

    


            if($channelid == 0){
                throw new ChannelException('Channel id required', 201);
            }

            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
            if(empty($channelname)){
                throw new ChannelException('Channel Name required', 201);
            }

            /*if(empty($channelimg)){
                throw new ChannelException('Channel image required', 201);
            }*/

            $updateChannel = new ChannelModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $updateChannel->update($channelname, $channelimg,$channelid,$userid,$userName);
            
            $objLogger->info("Update Status : ".json_encode($insStatus));
            $objLogger->info("======= End Channel Repository ================");
            return $insStatus;
        }
        catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Channel Repository ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException('Invalid Access', 401);
            }
        }
    }

    public function delete($channelid, $userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('ChannelAction_'.$userName, 'ChannelRepository');
        $objLogger->info("======= Start Channel Repository ================");
        try{
  
            $getoneChannelModel = new ChannelModel($this->loggerFactory, $this->dBConFactory);
            $grpData = $getoneChannelModel->deleteModel($channelid, $userid,$userName);
            $objLogger->info("======= End Channel Repository ================");
            return $grpData;
        }
        catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Channel Repository ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException('Invalid Access', 401);
            }
        }
    }
   
    function validChr($str) {
        return preg_match('/^[\W\d]+$/',$str);
    }

}

