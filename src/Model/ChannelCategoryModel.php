<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Channel\ChannelException;
use App\Model\DB;
class ChannelCategoryModel extends BaseModel
{
    
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }
    
    public function viewCategoryllist($userid,$username){
        $objLogger = $this->loggerFactory->addFileHandler($username.'_ChannelModel.log')->createInstance('ChannelModel');
        try 
        {

            $action = "VIEW";
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',0,'', 0, 0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $ListChannel = $dbObjt->getMultiDatasByObjects($sqlQuery);
               
                if(!empty($ListChannel)){
                    return $ListChannel;
                }
                else{
                    if (empty($ListChannel)) {
                        throw new ChannelException('Channel credentials invalid. ', 200);
                    }
                }

        }catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }

    }

   public function create($hotelid,  $categoryname,$userid,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('ChannelModel');
        try 
        {
            $action = "ADD";
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',$hotelid,'$categoryname' ,0 ,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                
               
                if(!empty($user->msg)){
                
                    return $user;
                }
                else{
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 200);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }
    }

    public function getoneModel($categoryid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('ChannelModel');
        try 
        {
            $action = "GETONE";
                                   
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',0, '', $categoryid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 200);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." 1Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }
    }


    public function update($categoryid,$hotelid,$categoryname,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('ChannelModel');
        try 
        {
            $action = "UPDATE";
            
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',$hotelid, '$categoryname', $categoryid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new ChannelException('Channel  credentials invalid. ', 200);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." 1Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }
    }

    public function delete($categoryid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('ChannelModel');
        try 
        {
            $action = "DELETE";
                        
           
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',0, '', $categoryid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{

                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 200);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." 1Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }
    }

}
