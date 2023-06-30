<?php

declare(strict_types=1);

namespace App\Domain\Repository\Hotel;

use App\Domain\Service\Hotel\HotelService;
use App\Exception\Hotel\HotelException;
use App\Model\HotelModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use PhpParser\Node\Stmt\Catch_;

class HotelRepository extends BaseRepository implements HotelService
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

    public function Viewhotellist($inputData){


        $userName = isset($inputData->decoded->userName)?$inputData->decoded->userName:"";    

        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('HotelRepository');
        try{
           
            $userid = isset($inputData->decoded->id)?$inputData->decoded->id:"";
            $brandid = isset($inputData->decoded->brandId)?$inputData->decoded->brandId:"0";
            // $hotelid = isset($inputData->decoded->hotelId)?$inputData->decoded->hotelId:"";
            // $hotelname = isset($inputData['hotelname'])?$inputData['hotelname']:"";

            if(empty($userid)){
                throw new HotelException('User id required', 201);
            }
           
            
            $Hotelmodel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $viewHotels = $Hotelmodel->ViewhotelList($userid,$userName,$brandid);
            return $viewHotels;
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Hotel credentials invalid', 201);
            }
        }
    }
    public function create($inputdata){
       
        $userName = isset($inputdata->decoded->userName)?$inputdata->decoded->userName:"";
        
        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('BrandRepository');
        try{

            $userid = isset($inputdata->decoded->id)?$inputdata->decoded->id:"";
            $brandid = isset($inputdata->brandid)?$inputdata->brandid:"0";
            $hotelname = isset($inputdata->hotelname)?addslashes($inputdata->hotelname):"";
            $location = isset($inputdata->location)?addslashes($inputdata->location):"";
            $mail = isset($inputdata->email)?$inputdata->email:"";
            $mobileno = isset($inputdata->mobileno)?$inputdata->mobileno:"";
            $address = isset($inputdata->address) ? addslashes($inputdata->address):"";
            $spocname = isset($inputdata->spocname)?addslashes($inputdata->spocname):"";
            
          


            if(empty($userid)){
                throw new HotelException('User id required', 201);
            }
            if($brandid == 0){
                throw new HotelException('Brand required', 201);
            }
            if(empty($hotelname)){
                throw new HotelException('Hotel Name required', 201);
            }
            if(empty($location)){
                throw new HotelException('Location required', 201);
            }
            if(empty($mail)){
                throw new HotelException('Email required', 201);
            }
            if(empty($mobileno)){
                throw new HotelException('MobileNo required', 201);
            }

            if(empty($address)){
                throw new HotelException('Address required', 201);
            }
            
            
            $AddHotelmodel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            
            $user = $AddHotelmodel->createhotel($brandid,$hotelname,$location,$mail,$spocname,$mobileno,$address,$userid,$userName);
            
            //$addHoteldata->userData = $user;

            //print_r($addHoteldata);die();
            return $user;
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Hotel credentials invalid', 201);
            }
        }
    }


    public function getsinglehotel($custid, $userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$userName, 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository ================");
        try{
  
            $getonehotelmodel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $grpData = $getonehotelmodel->gethoteloneModel($custid, $userid,$userName);
            $objLogger->info("======= End Hotel Repository ================");
            return $grpData;
        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 401);
            }
        }
    }


    public function update($inputdata,$hotelid){
        $userName = isset($inputdata->decoded->userName)?$inputdata->decoded->userName:"";
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$userName, 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository ================");
        $objLogger->info("Input Data : ".json_encode($inputdata));
        try{

            
            // $data = json_decode(json_encode($inputdata), false);
             
            // $groupName = isset($data->groupName)?$data->groupName : '';

            $userid = isset($inputdata->decoded->id)?$inputdata->decoded->id:"";
            $brandid = isset($inputdata->brandid)?$inputdata->brandid:"0";
            $hotelname = isset($inputdata->hotelname)?addslashes($inputdata->hotelname):"";
            $location = isset($inputdata->location)?addslashes($inputdata->location):"";
            $mail = isset($inputdata->email)?$inputdata->email:"";
            $mobileno = isset($inputdata->mobileno)?$inputdata->mobileno:"";
            $address = isset($inputdata->address) ? addslashes($inputdata->address):"";
            $spocname = isset($inputdata->spocname)?addslashes($inputdata->spocname):"";

            //print_r($inputdata);die();
            
          


            if(empty($userid)){
                throw new HotelException('User id required', 201);
            }
            if($brandid == 0){
                throw new HotelException('Brand required', 201);
            }
            if(empty($hotelname)){
                throw new HotelException('Hotel Name required', 201);
            }
            if(empty($location)){
                throw new HotelException('Location required', 201);
            }
            if(empty($mail)){
                throw new HotelException('Email required', 201);
            }
            if(empty($mobileno)){
                throw new HotelException('MobileNo required', 201);
            }

            if(empty($address)){
                throw new HotelException('Address required', 201);
            }

            $updateHotel = new HotelModel($this->loggerFactory, $this->dBConFactory);
            $insStatus = $updateHotel->update($hotelid,$brandid,$hotelname,$location,$mail,$spocname,$mobileno,$address,$userid,$userName);
            
            $objLogger->info("Insert Status : ".json_encode($insStatus));
            $objLogger->info("======= End Hotel Repository ================");
            return $insStatus;
        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 401);
            }
        }
    }

    public function delete($hotelid, $userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$userName, 'HotelRepository');
        $objLogger->info("======= Start Hotel Repository ================");
        try{
  
            $deletecustomer = new HotelModel($this->loggerFactory, $this->dBConFactory);
           
            $grpData = $deletecustomer->delete($hotelid, $userid,$userName);
            $objLogger->info("======= End Hotel Repository ================");
            return $grpData;
        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= End Hotel Repository ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 401);
            }
        }
    }
   
    function validChr($str) {
        return preg_match('/^[\W\d]+$/',$str);
    }

}

