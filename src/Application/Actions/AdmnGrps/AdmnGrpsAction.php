<?php
declare(strict_types=1);
namespace App\Application\Actions\AdmnGrps;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\AdmnGrps\AdmnGrpsException;
use App\Domain\Repository\AdmnGrps\AdmnGrpsRepository;
use App\Application\Auth\JwtToken;


final class AdmnGrpsAction extends Action
{
    protected AdmnGrpsRepository $admnGrpsRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;

    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, AdmnGrpsRepository $admnGrpsRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->admnGrpsRepository = $admnGrpsRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }

    /**
     * @throws AdmnGrpsException
     */
    public function update(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction', 'AdmnGrpsAction');
      $objLogger->info("======= Start AdmnGrps Action (UPDATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new AdmnGrpsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnGrpsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new AdmnGrpsException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new AdmnGrpsException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->admnGrpsRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new AdmnGrpsException('Invalid Access.', 401);
          }
          */

          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;
          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("group id : ".$args['id']);
          }
          else {
            throw new AdmnGrpsException('Group Id Empty.', 201);
          }
        
          $insStatus = $this->admnGrpsRepository->update($JWTdata, $args['id'], $userid, $hotelid, $brandid);
          $objLogger->info("update Status : ".$insStatus);
          $objLogger->info("======= END AdmnGrps Action (UPDATE) ================");
          if($insStatus == 'SUCCESS'){
            return $this->jsonResponse($response, 'Group Updated Successfully', '', 200);
          }
          else {
            return $this->jsonResponse($response, 'Group Not Added.', '', 200);
          }
        } catch (AdmnGrpsException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END AdmnGrps Action (UPDATE) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException(' Token invalid or Expired', 401);
            }
        }  
    }

    /**
     * @throws AdmnGrpsException
     */
    public function create(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction', 'AdmnGrpsAction');
      $objLogger->info("======= Start AdmnGrps Action (CREATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new AdmnGrpsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnGrpsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new AdmnGrpsException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new AdmnGrpsException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->admnGrpsRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new AdmnGrpsException('Invalid Access.', 401);
          }
          */

          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;
        
          $insStatus = $this->admnGrpsRepository->create($JWTdata, $userid, $hotelid, $brandid);
          $objLogger->info("Insert Status : ".$insStatus);
          $objLogger->info("======= END AdmnGrps Action (CREATE) ================");
          if($insStatus == 'SUCCESS'){
            return $this->jsonResponse($response, 'Group Added Successfully', '', 200);
          }
          else {
            return $this->jsonResponse($response, 'Group Not Added.', '', 200);
          }
        } catch (AdmnGrpsException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END AdmnGrps Action (CREATE) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException(' Token invalid or Expired', 401);
            }
        }  
    }

     /**
     * @throws AdmnGrpsException
     */
    public function getGrpOne(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction', 'AdmnGrpsAction');
      $objLogger->info("======= Start AdmnGrps Action (Single One) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'GET'){
            throw new AdmnGrpsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnGrpsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new AdmnGrpsException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new AdmnGrpsException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->admnGrpsRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new AdmnGrpsException('Invalid Access.', 401);
          }
          */

          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;
        
          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("group id : ".$args['id']);
          }
          else {
            throw new AdmnGrpsException('Group Id Empty.', 201);
          }  
          

          $grpData = $this->admnGrpsRepository->getGrpOne($args['id'], $userid, $hotelid, $brandid);
          $objLogger->info("======= END AdmnGrps Action (Single One) ================");
          
          return $this->jsonResponse($response, 'Success', $grpData, 200);
        } catch (AdmnGrpsException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END AdmnGrps Action (Single One) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException(' Token invalid or Expired', 401);
            }
        }  
    }
     /**
     * @throws AdmnGrpsException
     */
    public function getGrpList(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnGrpsAction', 'AdmnGrpsAction');
      $objLogger->info("======= Start AdmnGrps Action (List) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new AdmnGrpsException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new AdmnGrpsException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new AdmnGrpsException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new AdmnGrpsException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          */
          
          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;
          /*
          $readStatus = $this->admnGrpsRepository->getMenuRightStatus($userid, $menuid);

          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new AdmnGrpsException('Invalid Access.', 401);
          }
          */
          $grplst = $this->admnGrpsRepository->getGrpList($JWTdata, $userid, $hotelid, $brandid);
          $objLogger->info("======= END AdmnGrps Action (List) ================");
          
          return $this->jsonResponse($response, 'Success', $grplst, 200);
        } catch (AdmnGrpsException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END AdmnGrps Action (List) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException(' Token invalid or Expired', 401);
            }
        }
    }
    

}
?>