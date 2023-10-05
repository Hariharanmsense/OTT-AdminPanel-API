<?php
namespace App\Domain\Repository\GuestMessage;
use App\Exception\GuestMessage\GuestMessageException;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory; 
use App\Domain\Repository\BaseRepository;
use App\Domain\Service\GuestMessage\GuestMessageService;
use App\Model\GuestMessageModel;

class GuestMessageRepository extends BaseRepository implements GuestMessageService
{
    protected GuestMessageModel $guestmodel;
    protected LoggerFactory $loggerFactory;
    
    protected DBConFactory $dBConFactory;
    

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory,GuestMessageModel $guestmodel)
    {
        $this->guestmodel = $guestmodel;
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function guestmsgdelete($input,$msg_id,$userId,$userName){
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageRepository_'.$userName.'.log', 'guestmsgdelete');
        $objLogger->info("======= Start Guest Message Repository (guestmsgdelete) ================");      
        try{    
        
                
                $hotelid = isset($input->hotel_id)?$input->hotel_id :'0';
                
                $getguestmsglist = $this->guestmodel;
                
                $getMsgList = $getguestmsglist->guestmsgdelete($hotelid,$msg_id, $userId,$userName);
                
                //$getMsgList = $createJsonFile->createJson($hotelid,$tempid, $welcome_body,$logo,$bgimg,$userId,$userName);
                $objLogger->info("======= END Guest Message Repository (guestmsgdelete) ================");
                
                return $getMsgList;
    
            }catch (GuestMessageException $ex) {
    
                $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
                $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
                $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
                $objLogger->info("======= END Guest Message Repository (guestmsgdelete) ================");
                if(!empty($ex->getMessage())){
                    throw new GuestMessageException($ex->getMessage(), 201);
                }
                else {
                    throw new GuestMessageException('Hotel credentials invalid', 201);
                }
            }
    }
    public function getoneRoomRepository($input,$msg_id,$userId,$userName){
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageRepository_'.$userName.'.log', 'getguestmsglist');
        $objLogger->info("======= Start Guest Message Repository (getoneRoomRepository) ================");      
        try{    
        
                
                $hotelid = isset($input->hotel_id)?$input->hotel_id :'0';
                
                $getguestmsglist = $this->guestmodel;
                
                $getMsgList = $getguestmsglist->getoneroommodel($hotelid,$msg_id, $userId,$userName);
                
                //$getMsgList = $createJsonFile->createJson($hotelid,$tempid, $welcome_body,$logo,$bgimg,$userId,$userName);
                $objLogger->info("======= END Guest Message Repository (getoneRoomRepository) ================");
                
                return $getMsgList;
    
            }catch (GuestMessageException $ex) {
    
                $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
                $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
                $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
                $objLogger->info("======= END Guest Message Repository (getoneRoomRepository) ================");
                if(!empty($ex->getMessage())){
                    throw new GuestMessageException($ex->getMessage(), 201);
                }
                else {
                    throw new GuestMessageException('Hotel credentials invalid', 201);
                }
            }
    }

    public function sendmessage($input,$msg_img,$userId,$userName){
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageRepository_'.$userName.'.log', 'getguestmsglist');
        $objLogger->info("======= Start Guest Message Repository (sendmessage) ================");      
        try{    
        
                $msgtitle = isset($input->msgtitle)?addslashes($input->msgtitle):"";
                $msgbody = isset($input->msgbody)?addslashes($input->msgbody):'';
                $validupto = isset($input->validupto)?$input->validupto:'';
                $expirydate = isset($input->expirydate)?$input->expirydate:'';
                $roomlist = isset($input->roomlist)?($input->roomlist):'';

                if(empty($msgtitle)){
                    throw new GuestMessageException("Message Tittle Required", 201);
                    //exit();
                }

                if(empty($msgbody)){
                    throw new GuestMessageException("Message Body Required", 201);
                    //exit();
                }
                if(empty($roomlist)){
                    throw new GuestMessageException("Rooms List Empty", 201);
                    //exit();
                }
                // if(empty($msg_img)){
                //     throw new GuestMessageException("Message Tittle Required", 201);
                //     exit();
                // }
                    // if(!empty($roomlist)){
                    //     $roomlist = implode(',',$roomlist);
                    // }
                    
                // $welcome_body = isset($input->content)?addslashes($input->content):'';
                // $menucontent = isset($input->menucontent)?($input->menucontent):'';
                // $menuid = isset($input->menuid)?($input->menuid):'';
                $hotelid = isset($input->hotel_id)?$input->hotel_id :'';
                if(empty($hotelid)){
                        throw new GuestMessageException("Hotel Id Required", 201);

                    }
                
                $getguestmsglist = $this->guestmodel;
                
                $getMsgList = $getguestmsglist->sendguestmsgmodel($hotelid,$msgtitle,$msgbody,$msg_img,$validupto,$expirydate,$roomlist, $userId,$userName);
                
                //$getMsgList = $createJsonFile->createJson($hotelid,$tempid, $welcome_body,$logo,$bgimg,$userId,$userName);
                 
                
                return $getMsgList;
    
            }catch (GuestMessageException $ex) {
    
                $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
                $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
                $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
                $objLogger->info("======= END Guest Message Repository (sendmessage) ================");
                if(!empty($ex->getMessage())){
                    throw new GuestMessageException($ex->getMessage(), 201);
                }
                else {
                    throw new GuestMessageException('Hotel credentials invalid', 201);
                }
            }
    }
    public function getguestmsglist($input,$userId,$userName){
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageRepository_'.$userName.'.log', 'getguestmsglist');
        $objLogger->info("======= Start Guest Message Repository (getguestmsglist) ================");  
        try{    

                $hotelid = isset($input->hotel_id)?$input->hotel_id :'';

                if($hotelid == ''){
                    throw new GuestMessageException("Hotel Id  Required", 201);
                    
                }
                
                $getguestmsglist = $this->guestmodel;
                
                $getMsgList = $getguestmsglist->Getguestmsglist($hotelid, $userId,$userName);

                $objLogger->info("======= END Guest Message Repository (getguestmsglist) ================");
                
                return $getMsgList;
    
            }catch (GuestMessageException $ex) {
    
                $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
                $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
                $objLogger->error("Error Trace String : ".$ex->getTraceAsString());

                $objLogger->info("======= END Guest Message Repository (sendmessage) ================");
                if(!empty($ex->getMessage())){
                    throw new GuestMessageException($ex->getMessage(), 201);
                }
                else {
                    throw new GuestMessageException('Hotel credentials invalid', 201);
                }
            }
    }
    

}