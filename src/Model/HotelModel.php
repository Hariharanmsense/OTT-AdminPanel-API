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
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'HotelModel');
        try{
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
            return $sgarray;

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    

    public function bwStatus($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'HotelModel');
        try{

            $action = 'BWACTIVEORDEACTIVE';
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','','0',$hotelId,'',0,0,0,0,'',$auditBy)";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Update Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                
                return $insResult->msg;
            }
            else {
                throw new HotelException($insResult->msg, 201);
            }

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function alertEmailStatus($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'HotelModel');
        try{

            $action = 'ALRTEMLACTIVEORDEACTIVE';
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','','0',$hotelId,'',0,0,0,0,'',$auditBy)";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Update Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                
                return $insResult->msg;
            }
            else {
                throw new HotelException($insResult->msg, 201);
            }

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function icmpStatus($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'HotelModel');
        try{

            $action = 'ICMPACTIVEORDEACTIVE';
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','','0',$hotelId,'',0,0,0,0,'',$auditBy)";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Update Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                
                return $insResult->msg;
            }
            else {
                throw new HotelException($insResult->msg, 201);
            }

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }

    public function activeOrDeactive($hotelId, $auditBy){
        $objLogger = $this->loggerFactory->getFileObject('HotelAction_'.$auditBy, 'HotelModel');
        try{
			

            $action = 'ACTIVEORDEACTIVE';
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','',0,'',0,$hotelId,$auditBy)";
            $objLogger->info('Query : '.$sqlQuery);
            $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
            $insResult = $dbObjt->getSingleDatasByObjects($sqlQuery);
            $objLogger->info('Update Return : '.json_encode($insResult));
            if($insResult->ErrorCode == '00'){
                
                return $insResult->msg;
            }
            else {
                throw new HotelException($insResult->msg, 201);
            }

        }
        catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException('Invalid Access', 201);
            }
        }
    }
    
    public function ViewhotelList($hotelid,$userid,$username,$brandid){
        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$username.'.log')->createInstance('Hotelmodel');
        try 
        {

            $action = "VIEW";
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','',$brandid,$hotelid,0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $brandDetails = $dbObjt->getMultiDatasByObjects($sqlQuery);
               
                if(!empty($brandDetails)){
                    return $brandDetails;
                }
                else{
                    if (empty($brandDetails)) {
                        throw new HotelException('Hotel  credentials invalid. ', 200);
                    }
                }

        }catch (HotelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }

    }

    public function createhotel($brandid,$hotelname,$location,$mail,$spocname,$mobileno,$address,$userid,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('Hotelmodel');
        try 
        {
            $action = "ADD";
            $sqlQuery = "call SP_AddandEditCustomer('$action','$hotelname','$location','$spocname','$mail','$mobileno','$address',$brandid,'0',$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                
               
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
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }

    public function gethoteloneModel($hotelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('Hotelmodel');
        try 
        {
            $action = "GETONE";
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','','0',$hotelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
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
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }


    public function update($hotelid,$brandid,$hotelname,$location,$mail,$spocname,$mobileno,$address,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('Hotelmodel');
        try 
        {
            $action = "UPDATE";
            $sqlQuery = "call SP_AddandEditCustomer('$action','$hotelname','$location','$spocname','$mail','$mobileno','$address',$brandid,$hotelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
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
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }

    
    public function delete($hotelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('Hotelmodel_'.$userName.'.log')->createInstance('Hotelmodel');
        try 
        {
            $action = "DELETE";
			
            $sqlQuery = "call SP_AddandEditCustomer('$action','','','','','','',0,$hotelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);               
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
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), 401);
            }
            else {
                throw new HotelException('Database Error', 401);
            }
        }
    }
}
