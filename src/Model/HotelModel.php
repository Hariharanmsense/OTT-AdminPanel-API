<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Hotel\HotelException;
use App\Model\DB;
class HotelModel extends BaseModel
{
    
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }
    	
    public function gnrteHtlCde($auditBy, $brandid, $hotelname){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'gnrteHtlCde');
        try{
            $objLogger->info("======= START Hotel Model Action (gnrteHtlCde) ================"); 
            $sgarray = array();
            for($sg=0;$sg<2;$sg++){
                $sqlQuery = " CALL SP_HtlCdeSuggestion(".$brandid.", '".$hotelname."') ";
                $objLogger->info('suggest query : '.$sqlQuery);
                $objtCon = $this->dBConFactory->getConnection();
                $result = mysqli_query($objtCon, $sqlQuery);
                $errorMsg = mysqli_error($objtCon);
                $objLogger->info('Error Message : '.$errorMsg);
                $this->dBConFactory->close($objtCon);
                if($result){
                    $flg = 0;
                    While($row = mysqli_fetch_object($result)){
                        $flg = 1;
                        if($row->ErrorCode == '00'){
                            $sgarray[] = $row->hotelcode;
                        }
                    }
                }  
            }
            $objLogger->info('suggestion list : '.json_encode($sgarray));
            $objLogger->info("======= END Hotel Model Action (gnrteHtlCde) ================"); 
            return $sgarray;

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Hotel Model Action (gnrteHtlCde) ================"); 
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    

    public function bwStatus($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'bwStatus');
        try{
            $objLogger->info("======= START Hotel Model Action (bwStatus) ================"); 
            $action = 'BWACTIVEORDEACTIVE';
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','','0',$hotelId,'',0,0,0,0,'',$auditBy)";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Update Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                $objLogger->info("======= END Hotel Model Action (bwStatus) ================"); 
                return $insResult->msg;
            }
            else {
                throw new HotelException($insResult->msg, 201);
            }

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Hotel Model Action (bwStatus) ================"); 
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function alertEmailStatus($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'alertEmailStatus');
        try{
            $objLogger->info("======= START Hotel Model Action (alertEmailStatus) ================"); 
            $action = 'ALRTEMLACTIVEORDEACTIVE';
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','','0',$hotelId,'',0,0,0,0,'',$auditBy)";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Update Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                $objLogger->info("======= END Hotel Model Action (alertEmailStatus) ================"); 
                return $insResult->msg;
            }
            else {
                throw new HotelException($insResult->msg, 201);
            }

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Hotel Model Action (alertEmailStatus) ================"); 
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function icmpStatus($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'icmpStatus');
        try{
            $objLogger->info("======= START Hotel Model Action (icmpStatus) ================"); 
            $action = 'ICMPACTIVEORDEACTIVE';
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','','0',$hotelId,'',0,0,0,0,'',$auditBy)";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Update Return : '.json_encode($insResult));
           
            if($insResult->ErrorCode == '00'){
                $objLogger->info("======= END Hotel Model Action (icmpStatus) ================"); 
                return $insResult->msg;
            }
            else {
                throw new HotelException($insResult->msg, 201);
            }

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Hotel Model Action (icmpStatus) ================"); 
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function activeOrDeactive($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'activeOrDeactive');
        try{
			$objLogger->info("======= Start Hotel Model Action (activeOrDeactive) ================"); 

            $action = 'ACTIVEORDEACTIVE';
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','',0,'',0,$hotelId,$auditBy)";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Update Return : '.json_encode($insResult));
            
            if($insResult->ErrorCode == '00'){
                $objLogger->info("======= END Hotel Model Action (activeOrDeactive) ================"); 
                return $insResult->msg;
            }
            else {
                throw new HotelException($insResult->msg, 201);
            }

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Hotel Model Action (activeOrDeactive) ================"); 
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }
    
    public function ViewhotelList($hotelid,$brandid,$menuId,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$userName, 'ViewhotelList');
        try 
        {
            $objLogger->info("======= START Hotel Model Action (ViewhotelList) ================"); 
            $action = "VIEW";
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','',$brandid,$hotelid,'',0,$menuId)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $hotellist = $dbObjt->getMultiDatasByObjects($sqlQuery);
                
                if(!empty($hotellist)){
                    $objLogger->info("======= END Hotel Model Action (ViewhotelList) ================"); 
                    return $hotellist;
                }
                else{
                    if (empty($hotellist)) {
                        throw new HotelException('Hotel  credentials invalid. ', 201);
                    }
                }

        }catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Hotel Model Action (ViewhotelList) ================"); 
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }

    }

    public function createhotel($brandid,$hotelname,$location,$mail,$spocname,$mobileno,$address,$menuId,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$userName, 'createhotel');
        try 
        {
            $objLogger->info("======= Start Hotel Model Action (createhotel) ================"); 
            $action = "ADD";
            $sqlQuery = "call SP_AddandEditCustomer('$action','$hotelname','$location','$spocname','$mail','$mobileno','$address',$brandid,'0','',$userid,$menuId)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                
                $objLogger->info("======= Start Hotel Model Action (createhotel) ================"); 
                if(!empty($user->msg)){

                    return $user;
                }
                else{
                    if (empty($user)) {
                        throw new HotelException('Hotel  credentials invalid. ', 200);
                    }
                }
                
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Hotel Model Action (createhotel) ================"); 
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }

    public function gethoteloneModel($hotelid,$userid,$userName){

        $objLogger = $this->loggerFactory->getFileObject('Hotelmodel_'.$userName.'.log', 'gethoteloneModel');
        try 
        {
            $objLogger->info("======= Start Hotel Model Action (gethoteloneModel) ================"); 
            $action = "GETONE";
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','','0',$hotelid,'',$userid,0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                $objLogger->info("======= END Hotel Model Action (gethoteloneModel) ================"); 
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new HotelException('Hotel  credentials invalid. ', 200);
                    }
                }
                
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Hotel Model Action (gethoteloneModel) ================"); 
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }


    public function update($hotelid,$brandid,$hotelname,$location,$mail,$spocname,$mobileno,$address,$menuId,$userid,$userName){

        $objLogger = $this->loggerFactory->getFileObject('Hotelmodel_'.$userName.'.log', 'update');
        try 
        {
            $objLogger->info("======= Start Hotel Model Action (update) ================");  
            $action = "UPDATE";
            $sqlQuery = "call SP_AddandEditCustomer('$action','$hotelname','$location','$spocname','$mail','$mobileno','$address',$brandid,$hotelid,'',$userid,$menuId)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                $objLogger->info("======= END Hotel Model Action (update) ================");  
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new HotelException('Brand  credentials invalid. ', 200);
                    }
                }
                
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Hotel Model Action (update) ================");  
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }

    
    public function delete($hotelid,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('Hotelmodel_'.$userName.'.log', 'delete');
      

        try 
        {
            $objLogger->info("======= Start Hotel Model Action (delete) ================");  
            $action = "DELETE";
			
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','',0,$hotelid,'',$userid,0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);  
                $objLogger->info("======= END Hotel Model Action (delete) ================");               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new HotelException('Hotel  credentials invalid. ', 200);
                    }
                }
                
        } catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Hotel Model Action (delete) ================");  
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }
}
