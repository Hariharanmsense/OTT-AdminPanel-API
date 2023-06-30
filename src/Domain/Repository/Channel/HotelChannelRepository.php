<?php

declare(strict_types=1);

namespace App\Domain\Repository\Channel;

use App\Domain\Service\Channel\HotelChannelService;
use App\Exception\Channel\ChannelException;
use App\Model\HotelChannelModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use PhpParser\Node\Stmt\Catch_;

class HotelChannelRepository extends BaseRepository implements HotelChannelService
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

    public function ViewChannellist($inputdata){
        $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
        $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelRepository');
        try{
            //$brandData = new \stdClass();
            
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";

            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
           
            
            $Hotelchannel = new HotelChannelModel($this->loggerFactory, $this->dBConFactory);
			
            $viewChannel = $Hotelchannel->ViewChannellist($userid,$userName);
			

            return $viewChannel;
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
    public function create($inputdata){
        $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
        $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelRepository');
        try{
            //$addBrandData = new \stdClass();
            $hotelid = isset($inputdata['hotelid'])?($inputdata['hotelid']):"0";
            $channelno = isset($inputdata['channelno'])?($inputdata['channelno']):"0";
            $channelip = isset($inputdata['channelip'])?($inputdata['channelip']):"";
            $channelport = isset($inputdata['channelport'])?($inputdata['channelport']):"0";
            $channelcategory = isset($inputdata['channelcategory'])?($inputdata['channelcategory']):"0";
            $channelid = isset($inputdata['channelid'])?($inputdata['channelid']):"0";
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";
            
            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }

            if($hotelid == 0){
                throw new ChannelException('Hotel required', 201);
            }
            if($channelid == 0){
                throw new ChannelException('Channel required', 201);
            }
            if($channelno == 0){
                throw new ChannelException('Channel No required', 201);
            }
            if(empty($channelip)){
                throw new ChannelException('Channel Ip Address required', 201);
            }
            if($channelport == 0){
                throw new ChannelException('Channel Port required', 201);
            }
            if($channelcategory == 0){
                throw new ChannelException('Channel Category required', 201);
            }
           
            
 
            $AddHotelchannel = new HotelChannelModel($this->loggerFactory, $this->dBConFactory);
            $user = $AddHotelchannel->create($channelno,$channelip,$channelport,$channelcategory, $hotelid, $channelid,$userid,$userName);
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


    public function getOneHotelChannel($channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelRepository');
    
        try{    

        $editModel = new HotelChannelModel($this->loggerFactory, $this->dBConFactory);
        $edituser = $editModel->getoneModel($channelid,$userid,$userName);
        return $edituser;

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


    public function update($inputdata,$HotelChannelid,$userid){
            $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
            $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelRepository');
        
            try{
            
            //$UpdataData = new \stdClass();
            
            $hotelid = isset($inputdata['hotelid'])?($inputdata['hotelid']):"0";
            $channelno = isset($inputdata['channelno'])?($inputdata['channelno']):"0";
            $channelip = isset($inputdata['channelip'])?($inputdata['channelip']):"";
            $channelport = isset($inputdata['channelport'])?($inputdata['channelport']):"0";
            $channelcategory = isset($inputdata['channelcategory'])?($inputdata['channelcategory']):"0";
            $channelid = isset($HotelChannelid)?($HotelChannelid):"0";
            $actstat = isset($inputdata['isactive'])?($inputdata['isactive']):"0";
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";
            
            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }

            if($hotelid == 0){
                throw new ChannelException('Hotel required', 201);
            }
            if($channelid == 0){
                throw new ChannelException('Channel required', 201);
            }
            if($channelno == 0){
                throw new ChannelException('Channel No required', 201);
            }
            if(empty($channelip)){
                throw new ChannelException('Channel Ip Address required', 201);
            }
            if($channelport == 0){
                throw new ChannelException('Channel Port required', 201);
            }
            if($channelcategory == 0){
                throw new ChannelException('Channel Category required', 201);
            }
            if($actstat == 0){
                throw new ChannelException('Channel Status required', 201);
            }
            
            
            $Hotelchannel = new HotelChannelModel($this->loggerFactory, $this->dBConFactory);
            $updateuser = $Hotelchannel->update($channelno,$channelip,$channelport,$channelcategory, $hotelid,$actstat, $channelid,$userid,$userName);
            //$UpdataData->userData = $updateuser;
            return $updateuser;
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

    public function delete($channelid,$userid,$userName){
        
        $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelRepository');
    
        try{

            if($channelid == 0){
                throw new ChannelException('Channel Id required', 201);
            }

            $Hotelchannel = new HotelChannelModel($this->loggerFactory, $this->dBConFactory);
            $deleteDetails = $Hotelchannel->delete($channelid,$userid,$userName);
            //$delteData->userData = $deleteDetails;
            return $deleteDetails;
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

    public function assginMenu($input, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelChannelModel_'.$auditBy, 'HotelChannelRepository');
        $objLogger->info("======= Start HotelChannel Repository ================");
        $objLogger->info("Input Data : ".json_encode($input));
        try{
			
            $data = json_decode(json_encode($input), false);
            //$groupId = isset($data->channelid)?trim($data->groupId):'';
            $hotelId = isset($data->hotelid)?($data->hotelid):'0';
            //$readMenus = isset($data->readMenus)?trim($data->readMenus):'';
            $addlist = isset($data->addlist)?($data->addlist):'0';
			$removelist = isset($data->removelist)?($data->removelist):'0';
			$channelcategory = isset($data->channelcategory)?$data->channelcategory :'0';
        
	
            /*if(empty($groupId)){
                throw new ChannelException('groupId Empty', 401);
            }*/

            if(($hotelId == 0)){
                throw new ChannelException('hotelId Empty', 201);
            }
			if(($addlist == 0)){
                throw new ChannelException('groupId Empty', 201);
            }
			if(($channelcategory == 0)){
                throw new ChannelException('groupId Empty', 201);
            }
            $assignhotel = new HotelChannelModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $assignhotel->assginMenu($channelcategory, $hotelId, $addlist, $removelist, $auditBy);
            $objLogger->info("Insert Status : ".$insStatus);
            $objLogger->info("======= End HotelChannel Repository ================");
            return $insStatus;
        }
        catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End HotelChannel Repository ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException('Invalid Access', 401);
            }
        }
    }
		
	    public function getOverallchannellist($hotelid,$userid,$userName){

			$objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelRepository');
    
			try{    
				//$logDetails = new \stdClass();
				if($hotelid == 0){
					throw new ChannelException('Hotel Id Required', 201);
				}
			$getOverallchannellistmodel = new HotelChannelModel($this->loggerFactory, $this->dBConFactory);
			$GetoverAllList = $getOverallchannellistmodel->getchannellistModel($hotelid,$userid,$userName);
			
			
			$categoriesList =array();
			$listArray = array();
			$channelist = array();
			$i = 0 ;
			foreach($GetoverAllList as $list){
				$logDetails = new \stdClass();
				$logDetails->channelname = $list->channelname;
				$logDetails->channellogo = $list->channellogo;
				$logDetails->channelno = $list->channelno;
				$logDetails->channelip = $list->channelip;
				
				if(!in_array($list->categoryname, $categoriesList)){
					$categoriesList[]=$list->categoryname;
					$listArray [$list->categoryname][] = $logDetails;
					
				}
				else{
					$listArray [$list->categoryname][]  = $logDetails;
				}
				$i++;
			}
			
			return $listArray;
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

    function validChr($str) {
        return preg_match('/^[\W\d]+$/',$str);
    }

}

