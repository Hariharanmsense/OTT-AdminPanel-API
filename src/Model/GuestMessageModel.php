<?php
namespace App\Model;

use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\WizardSetup\WizardSetupException;
use App\Model\DB;
use Slim\Http\UploadedFile;
use stdClass;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

//use PhpOffice\PhpSpreadsheet\Helper\Sample;

//use App\Model\HotelModel;

class GuestMessageModel extends BaseModel
{
    protected DBConFactory $dBConFactory;

    protected DB $Dbobj;
    protected LoggerFactory $loggerFactory;


    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory, DB $Dbobj)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
        $this->Dbobj = $Dbobj;

    }

    public function guestmsgdelete($hotelid, $msg_id, $userId, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageModel_' . $userName, 'guestmsgdelete');
        $objLogger->info("======= START Guest Message Model (guestmsgdelete) ================");
        try {

            $action = 'DELETE';
            $sqlQuery = "CALL SP_AddandEditGuestMessage('" . $action . "', $hotelid,$msg_id, '', '', 
            '', 0, '','',$userId)";

            $objLogger->info('Query : ' . $sqlQuery);
            $dbObjt = $this->Dbobj;
            $insResult = $dbObjt->getMultiDatasByObjects($sqlQuery);
            $objLogger->info('List Return : ' . json_encode($insResult));
            $objLogger->info("======= END Guest Message Model (guestmsgdelete) ================");

            return $insResult;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            $objLogger->info("======= END Guest Message Model (guestmsgdelete) ================");
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 201);
            }
        }
    }

    public function getoneroommodel($hotelid, $msg_id, $userId, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageModel_' . $userName, 'sendguestmsgmodel');
        $objLogger->info("======= START Guest Message Model (sendguestmsgmodel) ================");
        try {

            $action = 'GETONE';
            $sqlQuery = "CALL SP_AddandEditGuestMessage('" . $action . "', $hotelid,$msg_id, '', '', 
            '', 0, '','',$userId)";

            $objLogger->info('Query : ' . $sqlQuery);
            $dbObjt = $this->Dbobj;
            $insResult = $dbObjt->getMultiDatasByObjects($sqlQuery);
            $objLogger->info('List Return : ' . json_encode($insResult));
            $objLogger->info("======= END Guest Message Model (sendguestmsgmodel) ================");

            return $insResult;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            $objLogger->info("======= END Guest Message Model (sendguestmsgmodel) ================");
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 201);
            }
        }
    }


    /**
     * Summary of sendguestmsgmodel
     * @param mixed $hotelid
     * @param mixed $msgtitle
     * @param mixed $msgbody
     * @param mixed $validupto
     * @param mixed $expirydate
     * @param mixed $allowedroomlist
     * @param mixed $userId
     * @param mixed $userName
     * @throws \App\Exception\WizardSetup\WizardSetupException
     * @return array
     */
    public function sendguestmsgmodel($hotelid, $msgtitle, $msgbody, $msg_img, $validupto, $expirydate, $allowedroomlist, $userId, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageModel_' . $userName, 'sendguestmsgmodel');
        $objLogger->info("======= START Guest Message Model (sendguestmsgmodel) ================");
        try {

            $curryear = date('Y');
            $currDate = date("Y-m-d");
            $txtmsgimgfldr = "public/uploads/msgimg/" . $curryear . "/" . $currDate;
            $parentUrl = "../public/uploads/msgimg/" . $curryear . "/" . $currDate;

            $action = 'ADD';
            $sqlQuery = "CALL SP_AddandEditGuestMessage('" . $action . "', $hotelid,0, '" . $msgtitle . "', '" . $msgbody . "', 
            '" . $txtmsgimgfldr . "', $validupto, '" . $expirydate . "','" . $allowedroomlist . "',$userId)";

            $objLogger->info('Query : ' . $sqlQuery);
            $dbObjt = $this->Dbobj;
            $insResult = $dbObjt->getMultiDatasByObjects($sqlQuery);
            $objLogger->info('List Return : ' . json_encode($insResult));
            $objLogger->info("======= END Guest Message Model (sendguestmsgmodel) ================");

            // print_r($insResult[0]->ErrorCode);
            // die();

            if ($insResult[0]->ErrorCode == '00') {
                $imgcnt = 1;
                if (!empty($msg_img)) {
                    $imgcnt = count($msg_img);
                    for($m = 0;$m < count($msg_img) ;$m++) :
							
                        $guest_msgid = $insResult[0]->last_insertid;
                        $getimgCnt = 0;
                        //$ext = pathinfo($msg_img, PATHINFO_EXTENSION);
                        $imagename = $curryear . "_" . $guest_msgid . "_" . '0';
                        //$folder = "uploads/msgimg/" . $curryear . "/" . $currDate . "/";
                        $path = $txtmsgimgfldr . $imagename;
                        if (!file_exists($txtmsgimgfldr)) {
                            mkdir($txtmsgimgfldr, 0777, true);
                        }

                        $bgersult = $this->moveUploadedFile($parentUrl, $msg_img[$m], $imagename);                       
                        $objLogger->info('Image Name : ' . $bgersult);
                        
                        
                endfor;
                $UpdateQry = "update guest_message set imgcnt=".$imgcnt." where id=".$guest_msgid;

                $update_result = $dbObjt->insOrUpdteOrDetQuery($UpdateQry);
                $objLogger->info('Update Image count Query : ' . $UpdateQry);
                $objLogger->info('Query : ' . json_encode($update_result));

                }
                //echo $imgcnt;

            }
            // if($insResult)

            return $insResult;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            $objLogger->info("======= END Guest Message Model (sendguestmsgmodel) ================");
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 201);
            }
        }
    }

    /**
     * Summary of Getguestmsglist
     * @param mixed $hotelid
     * @param mixed $userId
     * @param mixed $userName
     * @throws \App\Exception\WizardSetup\WizardSetupException
     * @return bool|object|null
     */


    public function Getguestmsglist($hotelid, $userId, $userName)
    {
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageModel_' . $userName, 'Getguestmsglist');
        $objLogger->info("======= START Guest Message Model (Getguestmsglist) ================");
        try {

            $action = 'VIEW';
            $sqlQuery = "	SELECT t.id,t.msgtitle,t.msgbody,t.imgpath,t.imgcnt,t.totalcnt,t.Unreadcount,case 
            when (round((t.Unreadcount/t.totalcnt)*100))>=85 &&  (round((t.Unreadcount/t.totalcnt)*100))<=100 then 'UnRead'
            when (round((t.Unreadcount/t.totalcnt)*100))>=50 &&  (round((t.Unreadcount/t.totalcnt)*100))< 85   then 'Partially Read'
            when (round((t.Unreadcount/t.totalcnt)*100))< 50    then 'Read' end as 'ReadStatus',
            round((t.Unreadcount/t.totalcnt)*100) as 'Unreadpercent',
           t.createdOn,t.filepath
            FROM     (select gm.id,gm.msgtitle,gm.msgbody,gm.imgpath,gm.imgcnt,
                DATE_FORMAT(DATE_ADD(gm.createdOn, INTERVAL 330 MINUTE), '%Y-%m-%d %H:%i:%s') as 'createdOn', '' as filepath,(select count(*) from sendmessages where msgid = gm.id ) as 'totalcnt',	
                (select count(*) from sendmessages where msgid = gm.id and readOn is null) as 'Unreadcount' from guest_message as gm where gm.deletedOn is null order by gm.createdOn desc) t";


            // $sqlQuery = "CALL SP_AddandEditGuestMessage('" . $action . "', $hotelid, 0,'', '', 
            // '', 0, NULL,0,$userId)";

            $objLogger->info('Query : ' . $sqlQuery);
            $dbObjt = $this->Dbobj;
            $MessageResult = $dbObjt->getMultipleimageobjects($sqlQuery);
            //print_R($MessageResult);die();
            $objLogger->info('List Return : ' . json_encode($MessageResult));
            $objLogger->info("======= END Guest Message Model (Getguestmsglist) ================");
            return $MessageResult;
        } catch (WizardSetupException $ex) {

            $objLogger->error("Error Code : " . $ex->getCode() . "Error Message : " . $ex->getMessage());
            $objLogger->error("Error File : " . $ex->getFile() . "Error Line : " . $ex->getLine());
            $objLogger->info("======= END Guest Message Model (Getguestmsglist) ================");
            if (!empty($ex->getMessage())) {
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            } else {
                throw new WizardSetupException('Invalid Access', 201);
            }
        }
    }


}