<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Channel\ChannelException;
use App\Model\DB;
use App\Model\HotelChannelModel;

class ChannelCategoryModel extends BaseModel
{
    
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    //protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    

    public function availablechnlcategory($hotelid,$categoryid,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('ChannelCategoryModel_'.$userName, 'availablechnlcategory');
        $objLogger->info("======= START Channel Category Model (availablechnlcategory) ================");
        try 
        {

            $action = "AVAILABLECHNL";
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',$hotelid,'', $categoryid, 0,0,'')";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $ListChannel = $dbObjt->getMultiDatasByObjects($sqlQuery);
                $objLogger->info('List Return : ' . json_encode($ListChannel));
                $objLogger->info("======= END Channel Category Model (availablechnlcategory) ================");
               
                    return $ListChannel;
                

        }catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->info("======= END Channel Category Model (availablechnlcategory) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }

    }

    public function assigncategorymodel($hotelid,$categoryid,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('ChannelCategoryModel_'.$userName, 'assigncategorymodel');
        $objLogger->info("======= START Channel Category Model (assigncategorymodel) ================");
        try 
        {

            $action = "ASSIGNEDCHANNELS";
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',$hotelid,'', $categoryid, 0,0,'')";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $ListChannel = $dbObjt->getMultiDatasByObjects($sqlQuery);
                $objLogger->info('List Return : ' . json_encode($ListChannel));
                $objLogger->info("======= END Channel Category Model (assigncategorymodel) ================");
               
                    return $ListChannel;
                

        }catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->info("======= END Channel Category Model (assigncategorymodel) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }

    }

    public function viewCategoryllist($hotelid,$menuid,$search_value,$userid,$userName,$action){
        $objLogger = $this->loggerFactory->getFileObject('ChannelCategoryModel_' . $userName, 'viewCategoryllist');
        $objLogger->info("======= START Channel Category Model (viewCategoryllist) ================");
        try 
        {
         
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',$hotelid,'', 0, $userid,$menuid,'".$search_value."')";
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

   public function create($hotelid,  $assignList,$categoryname,$menuid,$userid,$userName){
    $objLogger = $this->loggerFactory->getFileObject('ChannelCategoryModel_' . $userName, 'create');
    $objLogger->info("======= START Channel Category Model (create) ================");
        try 
        {
            $action = "ADD";
            $objLogger->info('Input ---- '."\r\n".
            "--------------  Hotelid :".$hotelid."\r\n".
            " --------- AssingList :".json_encode($assignList).
        "\n"."categoryname :".$categoryname);
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',$hotelid,'$categoryname' ,0 ,$userid,$menuid,'')";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                
                $objLogger->info('List Return : ' . json_encode($user));
                $objLogger->info("======= END Channel Category Model (create) ================");
                if(!empty($user->msg)){
                    if(isset($user->last_insert_id)){
                        if(!empty($assignList)){
                            $objLogger->info('Assign Channnels List Return : ' . json_encode($assignList));
                            $assignchannel = new HotelChannelModel($this->loggerFactory, $this->dBConFactory);
                            $assignchannels = $assignchannel->assginMenu($user->last_insert_id, $hotelid, $assignList, '', $userid,$userName);
                            $objLogger->info('Assign Channnels List Result Return : ' . json_encode($assignchannels));
                            $objLogger->info("======= END Channel Category Model (create) ================");
                        }
                    }
                    
                    
                  
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
                                   
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',0, '', $categoryid,$userid,0,'')";
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


    public function update($categoryid,$assignList,$hotelid,$categoryname,$menuid,$userid,$userName){

        
        $objLogger = $this->loggerFactory->getFileObject('ChannelCategoryModel_' . $userName, 'update');
        $objLogger->info("======= START Channel Category Model (update) ================");
        try 
        {
            $action = "UPDATE";
            
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',$hotelid, '$categoryname', $categoryid,$userid,$menuid,'')";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                $objLogger->info('List Return : ' . json_encode($user));
                
                if(!empty($user)){
                    if(!empty($assignList)){
                        $assignchannel = new HotelChannelModel($this->loggerFactory, $this->dBConFactory);
                        $assignchannels = $assignchannel->assginMenu($categoryid, $hotelid, $assignList, '', $userid,$userName);
                        $objLogger->info('Assign Channnels List Return : ' . json_encode($assignchannels));

                        $objLogger->info("======= END Channel Category Model (update) ================");
                    }
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
                        
           
            $sqlQuery = "call SP_AddandEditChannelcategory('$action',0, '', $categoryid,$userid,0,'')";
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
