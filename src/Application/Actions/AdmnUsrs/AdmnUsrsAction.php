<?php
declare(strict_types=1);
namespace App\Application\Actions\AdmnUsrs;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\AdmnUsrs\AdmnUsrsException;
use App\Domain\Repository\AdmnUsrs\AdmnUsrsRepository;
use App\Application\Auth\Crypto;


final class AdmnUsrsAction extends Action
{
    protected AdmnUsrsRepository $admnUsrsRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
	  protected Crypto $crypto;

    public function __construct(Crypto $crypto, LoggerFactory $loggerFactory, AdmnUsrsRepository $admnUsrsRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->admnUsrsRepository = $admnUsrsRepository;
        $this->dBConFactory = $dBConFactory;
		    $this->crypto = $crypto;
    }


    
    /**
     * @throws AdmnUsrsException
     */
    public function activeOrDeactiveUser(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsAction');
      $objLogger->info("======= Start AdmnUsrs Action (Activate De-Activate User) ================"); 
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new AdmnUsrsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnUsrsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new AdmnUsrsException('JWT Token invalid or Expired.', 401);
          }
          $auditBy = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
            throw new AdmnUsrsException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->admnUsrsRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new AdmnUsrsException('Invalid Access.', 401);
          }
          */

          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;

          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("admin user id : ".$args['id']);
          }
          else {
            throw new AdmnUsrsException('admin user Id Empty.', 201);
          }

          if(!is_numeric($args['id'])){
            throw new AdmnUsrsException('Invalid Parameter.', 201);
          }

          $resStatus = $this->admnUsrsRepository->activeOrDeactiveUser($args['id'], $auditBy);
          
          $objLogger->info("update Status : ".$resStatus);
          $objLogger->info("======= END AdmnUsrs Action (Activate De-Activate User) ================");
          return $this->jsonResponse($response, $resStatus, '', 200);


      }
      catch (AdmnUsrsException $ex) {
              
          $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
          $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
          //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
          $objLogger->info("======= END AdmnUsrs Action (resetPassword) ================");
          if(!empty($ex->getMessage())){
              throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
          }
          else {
              throw new AdmnUsrsException(' Token invalid or Expired', 401);
          }
      }
    }

    /**
     * @throws AdmnUsrsException
     */
    public function resetPassword(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsAction');
      $objLogger->info("======= Start AdmnUsrs Action (resetPassword) ================"); 
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new AdmnUsrsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnUsrsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));

          $JWTdata = $this->getJsonFromParsedBodyData($request);

          $resStatus = $this->admnUsrsRepository->resetPassword($JWTdata);
          $objLogger->info("======= END AdmnUsrs Action (resetPassword) ================");
          
    

          $objLogger->info("update Status : ".$resStatus);
          $objLogger->info("======= END AdmnUsrs Action (resetPassword) ================");
          if($resStatus == 'SUCCESS'){
            return $this->jsonResponse($response, 'Password updated successfully', '', 200);
          }
          else {
            return $this->jsonResponse($response, 'Password Not updated.', '', 200);
          }


      }
      catch (AdmnUsrsException $ex) {
              
          $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
          $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
          //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
          $objLogger->info("======= END AdmnUsrs Action (resetPassword) ================");
          if(!empty($ex->getMessage())){
              throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
          }
          else {
              throw new AdmnUsrsException(' Token invalid or Expired', 401);
          }
      }
    }
    
    /**
     * @throws AdmnUsrsException
     */
    public function forgotPassword(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsAction');
      $objLogger->info("======= Start AdmnUsrs Action (forgotPassword) ================"); 
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new AdmnUsrsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnUsrsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));

          $JWTdata = $this->getJsonFromParsedBodyData($request);

          $usrData = $this->admnUsrsRepository->forgotPassword($JWTdata);
          $objLogger->info("======= END AdmnUsrs Action (Single One) ================");
          
          return $this->jsonResponse($response, 'Success', $usrData, 200);


      }
      catch (AdmnUsrsException $ex) {
              
          $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
          $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
          //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
          $objLogger->info("======= END AdmnUsrs Action (UPDATE) ================");
          if(!empty($ex->getMessage())){
              throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
          }
          else {
              throw new AdmnUsrsException(' Token invalid or Expired', 401);
          }
      }
    }

    /**
     * @throws AdmnUsrsException
     */
    public function update(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsAction');
      $objLogger->info("======= Start AdmnUsrs Action (UPDATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new AdmnUsrsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnUsrsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new AdmnUsrsException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new AdmnUsrsException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->admnUsrsRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new AdmnUsrsException('Invalid Access.', 401);
          }
          */

          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;

          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("admin user id : ".$args['id']);
          }
          else {
            throw new AdmnUsrsException('admin user Id Empty.', 201);
          }

          if(!is_numeric($args['id'])){
            throw new AdmnUsrsException('Invalid Parameter.', 201);
          }
        
          $insStatus = $this->admnUsrsRepository->update($JWTdata, $args['id'], $userid, $hotelid, $brandid);
          $objLogger->info("Insert Status : ".$insStatus);
          $objLogger->info("======= END AdmnUsrs Action (UPDATE) ================");
          if($insStatus == 'SUCCESS'){
            return $this->jsonResponse($response, 'Admin User Updated Successfully', '', 200);
          }
          else {
            return $this->jsonResponse($response, 'Admin User Not Added.', '', 200);
          }
        } catch (AdmnUsrsException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END AdmnUsrs Action (UPDATE) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException(' Token invalid or Expired', 401);
            }
        }  
    }

    /**
     * @throws AdmnUsrsException
     */
    public function create(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsAction');
      $objLogger->info("======= Start AdmnUsrs Action (CREATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new AdmnUsrsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnUsrsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new AdmnUsrsException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new AdmnUsrsException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->admnUsrsRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new AdmnUsrsException('Invalid Access.', 401);
          }
          */

          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;
        
          $insStatus = $this->admnUsrsRepository->create($JWTdata, $userid, $hotelid, $brandid);
          $objLogger->info("Insert Status : ".$insStatus);
          $objLogger->info("======= END AdmnUsrs Action (CREATE) ================");
          if($insStatus == 'SUCCESS'){
            return $this->jsonResponse($response, 'Admin User Added Successfully', '', 200);
          }
          else {
            return $this->jsonResponse($response, 'Admin User Not Added.', '', 200);
          }
        } catch (AdmnUsrsException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END AdmnUsrs Action (CREATE) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException(' Token invalid or Expired', 401);
            }
        }  
    }

     /**
     * @throws AdmnUsrsException
     */
    public function getUsrOne(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsAction');
      $objLogger->info("======= Start AdmnUsrs Action (Single One) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'GET'){
            throw new AdmnUsrsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnUsrsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new AdmnUsrsException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new AdmnUsrsException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->admnUsrsRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new AdmnUsrsException('Invalid Access.', 401);
          }
          */

          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;
        
          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("User Id : ".$args['id']);
          }
          else {
            throw new AdmnUsrsException('User Id Empty.', 201);
          }  
          
          if(!is_numeric($args['id'])){
            throw new AdmnUsrsException('Invalid Parameter.', 201);
          }

          $usrData = $this->admnUsrsRepository->getUsrOne($args['id'], $userid, $hotelid, $brandid);
          $objLogger->info("======= END AdmnUsrs Action (Single One) ================");
          
          return $this->jsonResponse($response, 'Success', $usrData, 200);
        } catch (AdmnUsrsException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END AdmnUsrs Action (Single One) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException(' Token invalid or Expired', 401);
            }
        }  
    }
     /**
     * @throws AdmnUsrsException
     */
    public function getusrsList(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnUsrsAction', 'AdmnUsrsAction');
      $objLogger->info("======= Start AdmnUsrs Action (List) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new AdmnUsrsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnUsrsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new AdmnUsrsException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new AdmnUsrsException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          */
          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;
          /*
          $readStatus = $this->admnUsrsRepository->getMenuRightStatus($userid, $menuid);

          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new AdmnUsrsException('Invalid Access.', 401);
          }
          */
		  
          $usrslst = $this->admnUsrsRepository->getUsrsList($JWTdata, $userid, $hotelid, $brandid);
          $objLogger->info("======= END AdmnUsrs Action (List) ================");
          
          return $this->jsonResponse($response, 'Success', $usrslst, 200);
        } catch (AdmnUsrsException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END AdmnUsrs Action (List) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnUsrsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnUsrsException(' Token invalid or Expired', 401);
            }
        }
    }
    

}
?>