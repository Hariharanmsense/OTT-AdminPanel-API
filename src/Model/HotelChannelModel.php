<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Channel\ChannelException;
use App\Model\DB;
class HotelChannelModel extends BaseModel
{
    
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }
    
    public function ViewChannellist($userid,$username){
        $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$username.'.log')->createInstance('HotelChannelModel');
        try 
        {
            $action = "VIEW";
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',0,'', 0, 0,0,0,0,0)";
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


   public function create($channelno,$channelip,$channelport,$channelcategory, $hotelid, $channelid,$userid,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('HotelChannnelModel_'.$userName.'.log')->createInstance('HotelChannelModel');
        try 
        {
            $action = "ADD";
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',$channelno,'$channelip' ,$channelport ,$channelcategory,1,$channelid,$hotelid,$userid)";
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

    public function getoneModel($channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('HotelChannnelModel_'.$userName.'.log')->createInstance('HotelChannelModel');
        try 
        {
            $action = "GETONE";
                                   
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',0,'' ,0 ,0,0,$channelid,0,$userid)";
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


    public function update($channelno,$channelip,$channelport,$channelcategory, $hotelid, $actstat,$channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('HotelChannnelModel_'.$userName.'.log')->createInstance('HotelChannelModel');
        try 
        {
            $action = "UPDATE";
            
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',$channelno,'$channelip' ,$channelport ,$channelcategory,$actstat,$channelid,$hotelid,$userid)";
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

    public function delete($channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('HotelChannnelModel_'.$userName.'.log')->createInstance('HotelChannelModel');
        try 
        {
            $action = "DELETE";
                        
           
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',0,'' ,0 ,0,0,$channelid,0,$userid)";
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

    public function assginMenu($channelcategory, $hotelId, $addlist, $removelist, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelChannnelModel_'.$auditBy, 'HotelChannelModel');
        try{

            $sqlDelete = "DELETE FROM tempchannelgroup";
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $deleteResult = $dbObjt->insOrUpdteOrDetQuery($sqlDelete);

            if($deleteResult){
                $objLogger->info('temp channelgroup deleted successfully'); 
            }
            else {
                $objLogger->info('temp channelgroup not deleted'); 
            }

            $toRedOnlyMenuIds = array();
            if(!empty($removelist)){
                $toRedOnlyMenuIds = explode(",",$removelist);
            }


            $toRedWriteMenuIds = array();
            if(!empty($addlist)){
                $toRedWriteMenuIds = explode(",",$addlist);
            }

            $toMenuIds = array();
		    $toMenuIds = array_merge($toRedOnlyMenuIds, $toRedWriteMenuIds);
            if(is_array($toRedWriteMenuIds) && count($toRedWriteMenuIds) >=1){
                foreach($toRedWriteMenuIds as $channelid){
                    if(in_array($channelid, $toRedWriteMenuIds)){
                        $menuRight = 1;
                    }
                    else {
                        $menuRight = 2;
                    }

                    $sqlQuery = " INSERT INTO tempchannelgroup(channelid,categoryid, hotelid) 
							  VALUES(".$channelid.", ".$channelcategory.", '".$hotelId."') ";
                    
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
                $sqlPrco = "CALL SP_Assignchannels( ".$hotelId.", ".$channelcategory.", ".$auditBy.",'".$action."') ";
                $objLogger->info('Query : '.$sqlPrco); 
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $insResult = $dbObjt->getSingleDatasByObjects($sqlPrco);
                $objLogger->info('Insert Return : '.json_encode($insResult));
                if($insResult->ErrorCode == '00'){

                    return 'SUCCESS';
                }
                else {

                    throw new ChannelException($insResult->Result, 401);
                }
            }
            else {

                $action = 'DELETE';
                $sqlPrco = "CALL SP_Assignchannels(".$hotelId.", ".$auditBy.", '".$action."') ";
                $objLogger->info('Query : '.$sqlPrco); 
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $insResult = $dbObjt->getSingleDatasByObjects($sqlPrco);
                $objLogger->info('DELETE Return : '.json_encode($insResult));
                if($insResult->ErrorCode == '00'){

                    return 'SUCCESS';
                }
                else {

                    throw new ChannelException($insResult->Result, 401);
                }
            }
        }
        catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException('Invalid Access', 401);
            }
        }
    }
	
	
	public function getchannellistModel($hotelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('HotelChannnelModel_'.$userName.'.log')->createInstance('HotelChannelModel');
        try 
        {
            $action = "GETCHANNELLIST";
                                   
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',0,'' ,0 ,0,0,0,$hotelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getMultiDatasByObjects($sqlQuery);
               
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

}
