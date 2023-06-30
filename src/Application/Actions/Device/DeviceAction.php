<?php
declare(strict_types=1);
namespace App\Application\Actions\Device;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Device\DeviceException;
use App\Domain\Repository\Device\DeviceRepository;
use App\Application\Auth\Crypto;


final class DeviceAction extends Action
{
    protected DeviceRepository $deviceRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
	  protected Crypto $crypto;

    public function __construct(Crypto $crypto, LoggerFactory $loggerFactory, DeviceRepository $deviceRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->deviceRepository = $deviceRepository;
        $this->dBConFactory = $dBConFactory;
		    $this->crypto = $crypto;
    }

    /**
     * @throws DeviceException
     */
    public function bulkUpload(Request $request, Response $response, array $args): Response
    {
        $objLogger = $this->loggerFactory->getFileObject('DeviceAction', 'DeviceAction');
        $objLogger->info("======= Start Device Action (bulkUpload) ================");  
        try 
        {
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new DeviceException('Invalid Method', 500);
          }

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new DeviceException('JWT Token invalid or Expired.', 401);
          }
          $auditBy = $JWTdata->decoded->id;

          $files = $request->getUploadedFiles();
          if(empty($files)){
            throw new DeviceException('Please upload a valid files.', 201);
          }

          $uploadedFile = $files['uploadFile'];

          /*
          print_r($uploadedFile);
          echo "<br/>";
          echo ($uploadedFile->getClientMediaType());
          echo "<br/>";
          echo ($uploadedFile->getSize());
          echo "<br/>";
          echo  $tmp_name = $_FILES["uploadFile"]["tmp_name"];
          echo "<br/>";
          echo ($uploadedFile->getClientFilename());
          die();
          */
          $blkdata = $this->deviceRepository->bulkUpload($JWTdata, $uploadedFile, $auditBy);
          //$objLogger->info("Update Status : ".$insStatus);
          $objLogger->info("======= END Device Action (bulkUpload) ================");
          return $this->jsonResponse($response, 'Success', $blkdata, 200);

        } catch (DeviceException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Device Action (bulkUpload) ================");
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException(' Token invalid or Expired', 401);
            }
        }
    }

    /**
     * @throws DeviceException
     */
    public function update(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('DeviceAction', 'DeviceAction');
      $objLogger->info("======= Start Device Action (UPDATE) ================");  
      try 
      {  
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new DeviceException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new DeviceException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new DeviceException('JWT Token invalid or Expired.', 401);
          }
          $auditBy = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new DeviceException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->deviceRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new DeviceException('Invalid Access.', 401);
          }
          */
          /*
          if(!isset($JWTdata->hotel_id) || empty($JWTdata->hotel_id)){
            throw new DeviceException('Hotel Id is Invalid or Missing.', 401);
          }

          if(!isset($JWTdata->brand_id) || empty($JWTdata->brand_id)){
            throw new DeviceException('Brand Id is Invalid or Missing.', 401);
          }

          $hotelid		 = $JWTdata->hotel_id;
          $brandid		 = $JWTdata->brand_id;
          */

          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("Device id : ".$args['id']);
          }
          else {
            throw new DeviceException('Device Id Empty.', 201);
          }

          if(!is_numeric($args['id'])){
            throw new DeviceException('Invalid Parameter.', 201);
          }
        
          $insStatus = $this->deviceRepository->update($JWTdata, $args['id'], $auditBy);
          $objLogger->info("Update Status : ".$insStatus);
          $objLogger->info("======= END Device Action (UPDATE) ================");
          if($insStatus == 'SUCCESS'){
            return $this->jsonResponse($response, 'Device Updated Successfully', '', 200);
          }
          else {
            return $this->jsonResponse($response, 'Device Not Updated.', '', 200);
          }
      } catch (DeviceException $ex) {
          
          $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
          $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
          //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
          $objLogger->info("======= END Device Action (UPDATE) ================");
          if(!empty($ex->getMessage())){
              throw new DeviceException($ex->getMessage(), $ex->getCode());
          }
          else {
              throw new DeviceException(' Token invalid or Expired', 401);
          }
      }  
    }

    /**
     * @throws DeviceException
     */
    public function create(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('DeviceAction', 'DeviceAction');
      $objLogger->info("======= Start Device Action (CREATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new DeviceException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new DeviceException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new DeviceException('JWT Token invalid or Expired.', 401);
          }
          $auditBy = $JWTdata->decoded->id;
          $userName = $JWTdata->decoded->userName;

          /*
          if(!isset($JWTdata->menuid)){
             throw new DeviceException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->deviceRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new DeviceException('Invalid Access.', 401);
          }
          */

          $insStatus = $this->deviceRepository->create($JWTdata, $auditBy,$userName);
          $objLogger->info("Insert Status : ".$insStatus);
          $objLogger->info("======= END Device Action (CREATE) ================");
          if($insStatus == 'SUCCESS'){
            return $this->jsonResponse($response, 'Asset Added Successfully', '', 200);
          }
          else {
            return $this->jsonResponse($response, 'Asset Not Added.', '', 200);
          }
        } catch (DeviceException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Device Action (CREATE) ================");
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException(' Token invalid or Expired', 401);
            }
        }  
    }

     /**
     * @throws DeviceException
     */
    public function getUsrOne(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('DeviceAction', 'DeviceAction');
      $objLogger->info("======= Start Device Action (Single One) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'GET'){
            throw new DeviceException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new DeviceException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new DeviceException('JWT Token invalid or Expired.', 401);
          }
          $auditBy = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new DeviceException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->deviceRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new DeviceException('Invalid Access.', 401);
          }
          */

          
        
          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("User Id : ".$args['id']);
          }
          else {
            throw new DeviceException('User Id Empty.', 201);
          }  
          
          if(!is_numeric($args['id'])){
            throw new DeviceException('Invalid Parameter.', 201);
          }

          $deviceData = $this->deviceRepository->getUsrOne($args['id'], $auditBy);
          $objLogger->info("======= END Device Action (Single One) ================");
          
          return $this->jsonResponse($response, 'Success', $deviceData, 200);
        } catch (DeviceException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Device Action (Single One) ================");
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException(' Token invalid or Expired', 401);
            }
        }  
    }
     /**
     * @throws DeviceException
     */
    public function getDeviceList(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('DeviceAction', 'DeviceAction');
      $objLogger->info("======= Start Device Action (List) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new DeviceException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new DeviceException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new DeviceException('JWT Token invalid or Expired.', 401);
          }

          $userid = $JWTdata->decoded->id;
          $userName = $JWTdata->decoded->userName;

          if(!isset($JWTdata->hotel_id) || empty($JWTdata->hotel_id)){
            throw new DeviceException('Hotel Id is Invalid or Missing.', 201);
          }

          $hotelid	= $JWTdata->hotel_id;

          
        
          if(!isset($JWTdata->startDate) || empty($JWTdata->startDate)){
            throw new DeviceException('Start Date is Invalid or Missing.', 201);
          }

          if(!isset($JWTdata->endDate) || empty($JWTdata->endDate)){
            throw new DeviceException('End Date is Invalid or Missing.', 201);
          }

          $startDate = $JWTdata->startDate;
          $endDate = $JWTdata->endDate;
          /*
          if(!isset($JWTdata->menuid)){
             throw new DeviceException('Invalid Parameter Missing.', 201);
          }
          $menuid = $JWTdata->menuid;
          */
          
          //$hotelid = 0;
          //$brandid = 0;
          /*
          $readStatus = $this->deviceRepository->getMenuRightStatus($userid, $menuid);

          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new DeviceException('Invalid Access.', 401);
          }
          */

          

          $devlist = $this->deviceRepository->getDeviceList($JWTdata, $userid, $userName,$hotelid, $startDate, $endDate);
          $objLogger->info("======= END Device Action (List) ================");
          
          return $this->jsonResponse($response, 'Success', $devlist, 200);
        } catch (DeviceException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Device Action (List) ================");
            if(!empty($ex->getMessage())){
                throw new DeviceException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new DeviceException(' Token invalid or Expired', 401);
            }
        }
    }
    

}
?>