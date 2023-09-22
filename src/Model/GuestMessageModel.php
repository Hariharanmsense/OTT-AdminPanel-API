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
                    //$imgcnt = count($msg_img);
                    //for($m = 0;$m < count($msg_img) ;$m++) :
							
                        $guest_msgid = $insResult[0]->last_insertid;
                        $getimgCnt = 0;
                        //$ext = pathinfo($msg_img, PATHINFO_EXTENSION);
                        $imagename = $curryear . "_" . $guest_msgid . "_" . '0';
                        $folder = "uploads/msgimg/" . $curryear . "/" . $currDate . "/";
                        $path = $folder . $imagename;
                        if (!file_exists($folder)) {
                            mkdir($folder, 0777, true);
                        }

                        $bgersult = $this->moveUploadedFile($parentUrl, $msg_img, $imagename);

                        $UpdateQry = "update guest_message set imgcnt=".$imgcnt." where id=".$guest_msgid;

                        $update_result = $dbObjt->insOrUpdteOrDetQuery($UpdateQry);
                        $objLogger->info('Image Name : ' . $bgersult);
                        $objLogger->info('Update Image count Query : ' . $UpdateQry);
                        $objLogger->info('Query : ' . json_encode($update_result));
                        
                //endfor;

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
            $sqlQuery = "CALL SP_AddandEditGuestMessage('" . $action . "', $hotelid, 0,'', '', 
            '', 0, NULL,0,$userId)";

            $objLogger->info('Query : ' . $sqlQuery);
            $dbObjt = $this->Dbobj;
            $insResult = $dbObjt->getMultiDatasByObjects($sqlQuery);
            $objLogger->info('List Return : ' . json_encode($insResult));
            $objLogger->info("======= END Guest Message Model (Getguestmsglist) ================");
            return $insResult;
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