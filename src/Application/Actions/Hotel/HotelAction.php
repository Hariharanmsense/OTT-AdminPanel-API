<?php
declare(strict_types=1);
namespace App\Application\Actions\Hotel;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Hotel\HotelException;

use App\Domain\Repository\Hotel\HotelRepository;
use App\Application\Auth\JwtToken;


final class HotelAction extends Action
{
    protected HotelRepository $hotelRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;
    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, HotelRepository $hotelRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->hotelRepository = $hotelRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }

    /**
     * @throws HotelException
     */
    public function gethotelsList(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (List) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new HotelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new HotelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new HotelException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          $hotellist = $this->hotelRepository->Viewhotellist($JWTdata);
          $objLogger->info("======= END Hotel Action (List) ================");
          
          return $this->jsonResponse($response, 'Success', $hotellist, 200);
        } catch (HotelException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (List) ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException(' Token invalid or Expired', 401);
            }
        }
    }

     /**
     * @throws HotelException
     */
    public function create(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (CREATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new HotelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new HotelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new HotelException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          $insStatus = $this->hotelRepository->create($JWTdata);

          $objLogger->info("Insert Status : ".json_encode($insStatus));
          $objLogger->info("======= END Hotel Action (CREATE) ================");
            $returnmsg = $insStatus->msg;
            return $this->jsonResponse($response, $returnmsg, '', 200);
        } catch (HotelException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (CREATE) ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException(' Token invalid or Expired', 401);
            }
        }  
    }

    

     /**
     * @throws HotelException
     */
    public function gethotelOne(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (Single One) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'GET'){
            throw new HotelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new HotelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new HotelException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;


          $userName		 = isset($JWTdata->decoded->userName)?$JWTdata->decoded->userName:'';
        
          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("group id : ".$args['id']);
          }
          else {
            throw new HotelException('Group Id Empty.', 201);
          }  
            $custid = isset($args['id'])?$args['id']:'0';          

          $grpData = $this->hotelRepository->getsinglehotel($custid, $userid,$userName);

          $objLogger->info("======= END Hotel Action (Single One) ================");
          
          return $this->jsonResponse($response, 'Success', $grpData, 200);
        } catch (HotelException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (Single One) ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException(' Token invalid or Expired', 401);
            }
        }  
    }

    /**
     * @throws HotelException
     */
    public function update(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (UPDATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new HotelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new HotelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new HotelException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("group id : ".$args['id']);
          }
          else {
            throw new HotelException('Group Id Empty.', 201);
          }
          $hotelid = isset($args['id'])?$args['id']:'0';
          $insStatus = $this->hotelRepository->update($JWTdata, $hotelid, $userid);
          //print_r($insStatus);die();
          $objLogger->info("Insert Status : ".json_encode($insStatus));
          $objLogger->info("======= END Hotel Action (UPDATE) ================");
    
            return $this->jsonResponse($response, $insStatus->msg, '', 200);
          
        } catch (HotelException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (UPDATE) ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException(' Token invalid or Expired', 401);
            }
        }  
    }


     /**
     * @throws HotelException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (DELETE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'DELETE'){
            throw new HotelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new HotelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new HotelException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("group id : ".$args['id']);
          }
          else {
            throw new HotelException('Group Id Empty.', 201);
          }
          $hotelid = isset($args['id'])?$args['id']:'0';
          $userName = isset($JWTdata->decoded->userName)? $JWTdata->decoded->userName :'';
          $insStatus = $this->hotelRepository->delete($hotelid, $userid,$userName);
          //print_r($insStatus);die();
          $objLogger->info("Insert Status : ".json_encode($insStatus));
          $objLogger->info("======= END Hotel Action (DELETE) ================");
    
            return $this->jsonResponse($response, $insStatus->msg, '', 200);
          
        } catch (HotelException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (DELETE) ================");
            if(!empty($ex->getMessage())){
                throw new HotelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new HotelException(' Token invalid or Expired', 401);
            }
        }  
    }


}
?>