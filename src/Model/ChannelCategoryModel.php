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
    
    public function viewCategoryllist($hotelid,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('ChannelCategoryModel_' . $userName, 'viewCategoryllist');
        $objLogger->info("======= START Channel Category Model (viewCategoryllist) ================");
        try 
        {

            $action = "VIEW";
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',$hotelid,'', 0, 0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $ListChannel = $dbObjt->getMultiDatasByObjects($sqlQuery);
                $objLogger->info('List Return : ' . json_encode($ListChannel));
                $objLogger->info("======= END Channel Category Model (viewCategoryllist) ================");
               
                if(!empty($ListChannel)){
                    return $ListChannel;
                }
                else{
                    if (empty($ListChannel)) {
                        throw new ChannelException('Channel credentials invalid. ', 201);
                    }
                }

        }catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->info("======= END Channel Category Model (viewCategoryllist) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }

    }

   public function create($hotelid,  $categoryname,$userid,$userName){
    $objLogger = $this->loggerFactory->getFileObject('ChannelCategoryModel_' . $userName, 'create');
    $objLogger->info("======= START Channel Category Model (create) ================");
        try 
        {
            $action = "ADD";
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',$hotelid,'$categoryname' ,0 ,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                
                $objLogger->info('List Return : ' . json_encode($user));
                $objLogger->info("======= END Channel Category Model (create) ================");
                if(!empty($user->msg)){
                
                    return $user;
                }
                else{
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 201);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->info("======= END Channel Category Model (create) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }

    public function getoneModel($categoryid,$userid,$userName){

        $objLogger = $this->loggerFactory->getFileObject('ChannelCategoryModel_' . $userName, 'getoneModel');
        $objLogger->info("======= START Channel Category Model (getoneModel) ================");
        try 
        {
            $action = "GETONE";
                                   
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',0, '', $categoryid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                $objLogger->info('List Return : ' . json_encode($user));
                $objLogger->info("======= END Channel Category Model (getoneModel) ================");
               
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
            $objLogger->info("======= END Channel Category Model (getoneModel) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }


    public function update($categoryid,$hotelid,$categoryname,$userid,$userName){

        
        $objLogger = $this->loggerFactory->getFileObject('ChannelCategoryModel_' . $userName, 'update');
        $objLogger->info("======= START Channel Category Model (update) ================");
        try 
        {
            $action = "UPDATE";
            
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',$hotelid, '$categoryname', $categoryid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                $objLogger->info('List Return : ' . json_encode($user));
                $objLogger->info("======= END Channel Category Model (update) ================");
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
            $objLogger->info("======= END Channel Category Model (update) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }

    public function delete($categoryid,$userid,$userName){

        
        $objLogger = $this->loggerFactory->getFileObject('ChannelCategoryModel_' . $userName, 'delete');
        $objLogger->info("======= START Channel Category Model (delete) ================");
        try 
        {
            $action = "DELETE";
                        
           
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',0, '', $categoryid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                $objLogger->info('List Return : ' . json_encode($user));
                $objLogger->info("======= END Channel Category Model (delete) ================");
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
            $objLogger->info("======= END Channel Category Model (delete) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }

}
