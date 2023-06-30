<?php

declare(strict_types=1);

namespace App\Domain\Repository\Room;

use App\Domain\Service\Room\RoomService;
use App\Exception\Room\RoomException;
use App\Model\RoomModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use PhpParser\Node\Stmt\Catch_;

class RoomRepository extends BaseRepository implements RoomService
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

    public function viewroomlist($inputdata){
        $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
        $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('RoomRepository');
        try{

            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";

            if(empty($userid)){
                throw new RoomException('User id required', 201);
            }
                      
            $roomModel = new RoomModel($this->loggerFactory, $this->dBConFactory);
			
            $viewrooms = $roomModel->viewrommlist($userid,$userName);

            return $viewrooms;

        } catch (RoomException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new RoomException($ex->getMessage(), 401);
            }
            else {
                throw new RoomException('Room credentials invalid', 401);
            }
        }
    }
    public function create($inputdata){
        $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
        $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('RoomRepository');
        try{

            $hotelid = isset($inputdata['hotelid'])?($inputdata['hotelid']):"0";
            $roomno = isset($inputdata['roomno'])?($inputdata['roomno']):"0";
            $roomcategory = isset($inputdata['roomcategory'])?($inputdata['roomcategory']):"0";

            
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";

            if(empty($userid)){
                throw new RoomException('User id required', 201);
            }

            if($hotelid == 0){
                throw new RoomException('Hotel required', 201);
            }
            if($roomno == 0){
                throw new RoomException('Roomno required', 201);
            }
            if($roomcategory == 0){
                throw new RoomException('Room category required', 201);
            }
           
            
            $AddRoomModel = new RoomModel($this->loggerFactory, $this->dBConFactory);
            $user = $AddRoomModel->addroom($hotelid,$roomno,$roomcategory,$userid,$userName);
            return $user;
        } catch (RoomException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new RoomException($ex->getMessage(), 401);
            }
            else {
                throw new RoomException('Room credentials invalid', 401);
            }
        }
    }


    public function getOne($roomid,$inputdata,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('RoomRepository');
    
        try{
        
        $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";

        $editModel = new RoomModel($this->loggerFactory, $this->dBConFactory);
        $editRoom = $editModel->EditViewModel($roomid,$userid,$userName);

        return $editRoom;
    } catch (RoomException $ex) {

        $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
        $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
        $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
        if(!empty($ex->getMessage())){
            throw new RoomException($ex->getMessage(), 401);
        }
        else {
            throw new RoomException('Room credentials invalid', 401);
        }
    }
}


    public function update($inputdata,$roomid){
            $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
            $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('RoomRepository');
        
            try{
            
                $hotelid = isset($inputdata['hotelid'])?($inputdata['hotelid']):"";
                $roomno = isset($inputdata['roomno'])?($inputdata['roomno']):"0";
                $roomcategory = isset($inputdata['roomcategory'])?($inputdata['roomcategory']):"0";
                $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";
    
                if(empty($userid)){
                    throw new RoomException('User id required', 201);
                }
    
                if($roomno == 0){
                    throw new RoomException('Roomno required', 201);
                }
                if($roomcategory == 0){
                    throw new RoomException('Room category required', 201);
                }
            
            $RoomModel = new RoomModel($this->loggerFactory, $this->dBConFactory);
            $updateuser = $RoomModel->updateroom($hotelid,$roomno,$roomcategory,$roomid,$userid,$userName);
            //$UpdataData->userData = $updateuser;
            return $updateuser;
        } catch (RoomException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new RoomException($ex->getMessage(), 401);
            }
            else {
                throw new RoomException('Room credentials invalid', 401);
            }
        }
    }

    public function delete($roomid,$inputdata){
        $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
        $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('RoomRepository');
    
        try{

            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";

            $RoomModel = new RoomModel($this->loggerFactory, $this->dBConFactory);
            $deluser = $RoomModel->DeleteRoomModel($userName,$roomid,$userid);
            return $deluser;
        } catch (RoomException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new RoomException($ex->getMessage(), 401);
            }
            else {
                throw new RoomException('Room credentials invalid', 401);
            }
        }
    }

}