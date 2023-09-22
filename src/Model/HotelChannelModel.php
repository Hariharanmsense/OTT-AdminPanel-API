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
    
    public function ViewChannellist($hotelid,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('HotelChannelModel_' . $userName, 'ViewChannellist');
       
        try 
        {
            $action = "VIEW";
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',0,'', 0, 0,0,0,0,0,0,$hotelid,0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $ListChannel = $dbObjt->getMultiDatasByObjects($sqlQuery);
               
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
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }

    }


   public function create($channelno,$channelip,$channelport,$channelcategory, $hotelid, $channelid,$chnlfrequency,$userid,$userName){
    $objLogger = $this->loggerFactory->getFileObject('HotelChannelModel_' . $userName, 'create');
        try 
        {
            $action = "ADD";
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',$channelno,'$channelip' ,$channelport ,$channelcategory,1,0,0,$chnlfrequency,$channelid,$hotelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                
               
                if(!empty($user->msg)){
                    if($user->status == 'FAILURE'){
                        throw new ChannelException($user->msg, 201);
                    }else{
                        return $user;
                    }        
                    
                }
                else{
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 201);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            //$objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }

    public function getoneModel($channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->getFileObject('HotelChannelModel_' . $userName, 'getoneModel');
        try 
        {
            $action = "GETONE";
                                   
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',0,'' ,0 ,0,0,0,0,0,$channelid,0,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 201);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." 1Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }


    public function update($channelno,$channelip,$channelport,$channelcategory, $hotelid, $actstat,$channelid,$HotelChannelid,$chnlfrequency,$userid,$userName){

        $objLogger = $this->loggerFactory->getFileObject('HotelChannelModel_' . $userName, 'update');
        try 
        {
            $action = "UPDATE";
            
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',$channelno,'$channelip' ,$channelport ,$channelcategory,$actstat,$channelid,0,$chnlfrequency,$HotelChannelid,$hotelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    if($user->status == 'FAILURE'){
                        throw new ChannelException($user->msg, 201);
                    }else{
                        return $user;
                    }        
                    //return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new ChannelException('Channel  credentials invalid. ', 201);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." 1Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }

    public function delete($channelid,$userid,$userName){

        
        $objLogger = $this->loggerFactory->getFileObject('HotelChannelModel_'.$userName, 'delete');
        try 
        {
            $action = "DELETE";
                        
           
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',0,'' ,0 ,0,0,0,0,0,$channelid,0,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    if($user->status == 'FAILURE'){
                        throw new ChannelException($user->msg, 201);
                    }else{
                        return $user;
                    }    
                }
                else{

                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 201);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            //$objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }

        
    }

    public function assginMenu($channelcategory, $hotelId, $addlist, $removelist, $auditBy,$userName){
        $objLogger = $this->loggerFactory->getFileObject('HotelChannelModel_' . $userName, 'assginMenu');
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
                    $chncategory = explode(',',$channelcategory);
                    for ($i=0; $i < count( $chncategory); $i++) { 
                        $sqlQuery = " INSERT INTO tempchannelgroup(channelid,categoryid, hotelid) 
							  VALUES(".$channelid.", ".$chncategory[$i].", '".$hotelId."') ";

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
                    // $sqlQuery = " INSERT INTO tempchannelgroup(channelid,categoryid, hotelid) 
					// 		  VALUES(".$channelid.", ".$channelcategory.", '".$hotelId."') ";
                    
                    // $objLogger->info('Insert Query : '.$sqlQuery);
                    // $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                    // $result = $dbObjt->insOrUpdteOrDetQuery($sqlQuery);
                    // if($result){
                    //     $objLogger->info('inserted successfully'); 
                    // }
                    // else {
                    //     $objLogger->info('not inserted'); 
                    // }
                    
                }
                $action = 'ADD';
                $sqlPrco = "CALL SP_Assignchannels( ".$hotelId.", '".$channelcategory."', ".$auditBy.",'".$action."') ";
                $objLogger->info('Query : '.$sqlPrco); 
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $insResult = $dbObjt->getSingleDatasByObjects($sqlPrco);
                $objLogger->info('Insert Return : '.json_encode($insResult));
                if($insResult->ErrorCode == '00'){

                    return 'SUCCESS';
                }
                else {

                    throw new ChannelException($insResult->Result, 201);
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

                    throw new ChannelException($insResult->Result, 201);
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
                throw new ChannelException('Invalid Access', 201);
            }
        }
    }
	
	
	public function getchannellistModel($hotelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('HotelChannnelModel_'.$userName.'.log')->createInstance('HotelChannelModel');
        try 
        {
            $action = "GETCHANNELLIST";
                                   
            $sqlQuery = "call SP_AddandEdithotelChannelInfo('$action',0,'' ,0 ,0,0,0,0,0,0,$hotelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getMultiDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 201);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." 1Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }

}
