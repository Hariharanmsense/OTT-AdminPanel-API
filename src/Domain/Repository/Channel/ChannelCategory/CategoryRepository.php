<?php

declare(strict_types=1);

namespace App\Domain\Repository\Channel\ChannelCategory;

use App\Domain\Service\Channel\ChannelCategory\CategoryService;
use App\Exception\Channel\ChannelException;
use App\Model\ChannelCategoryModel;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Application\Auth\JwtToken;
use PhpParser\Node\Stmt\Catch_;

class CategoryRepository extends BaseRepository implements CategoryService
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

    public function ViewCategorylist($inputdata){
        $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
        $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('CategoryRepository');
        try{
            //$brandData = new \stdClass();
            
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";

            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }
           
            
            $ChannelCategoryModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
			
            $viewChannel = $ChannelCategoryModel->viewCategoryllist($userid,$userName);
			

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
        $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('CategoryRepository');
        try{
            //$addBrandData = new \stdClass();

            $hotelid = isset($inputdata['hotelid'])?($inputdata['hotelid']):"0";
            $categoryname = isset($inputdata['categoryname'])?addslashes($inputdata['categoryname']):"";
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";

            if(empty($userid)){
                throw new ChannelException('User id required', 201);
            }

            if($hotelid == 0){
                throw new ChannelException('Hotel required', 201);
            }
            if(empty($categoryname)){
                throw new ChannelException('Category Name id required', 201);
            }
 
            $AddChannelCategoryModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
            $user = $AddChannelCategoryModel->create($hotelid,$categoryname,$userid,$userName);
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


    public function getOneCategory($categoryid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('CategoryRepository');
    
        try{    

        $editModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
        $edituser = $editModel->getoneModel($categoryid,$userid,$userName);
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


    public function update($inputdata,$categoryid,$userid){
            $userName = isset($inputdata['decoded']->userName)?$inputdata['decoded']->userName:"";
            $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('CategoryRepository');
        
            try{
            
            //$UpdataData = new \stdClass();
            
            $hotelid = isset($inputdata['hotelid'])?($inputdata['hotelid']):"0";
            $categoryname = isset($inputdata['categoryname'])?addslashes($inputdata['categoryname']):"";
            $userid = isset($inputdata['decoded']->id)?$inputdata['decoded']->id:"";
           
            if($hotelid == 0){
                throw new ChannelException('Hotel required', 201);
            }
            if(empty($categoryname)){
                throw new ChannelException('Category Name id required', 201);
            }
            
            
            $ChannelCategoryModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
            $updateuser = $ChannelCategoryModel->update($categoryid,$hotelid,$categoryname,$userid,$userName);
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

    public function delete($categoryid,$userid,$userName){
        
        $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('CategoryRepository');
    
        try{

            if($categoryid == 0){
                throw new ChannelException('Category Id required', 201);
            }

            $ChannelCategoryModel = new ChannelCategoryModel($this->loggerFactory, $this->dBConFactory);
            $deleteDetails = $ChannelCategoryModel->delete($categoryid,$userid,$userName);
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

    function validChr($str) {
        return preg_match('/^[\W\d]+$/',$str);
    }

}

