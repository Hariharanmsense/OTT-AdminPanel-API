<?php
declare(strict_types=1);
namespace App\Application\Actions\Customer;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Customer\CustomerException;

use App\Domain\Repository\Customer\CustomerRepository;
use App\Application\Auth\JwtToken;


final class CustomerAction extends Action
{
    protected CustomerRepository $CustomerRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;
    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, CustomerRepository $CustomerRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->CustomerRepository = $CustomerRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }
	
	/**
     * @throws HotelException
     */
    
    public function gnrteHtlCde(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (Generated Hotel Code) ================"); 
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

        $JWTdata = $this->getJsonFromParsedBodyData($request);
        if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
        {
            throw new HotelException('JWT Token invalid or Expired.', 401);
        }
        $auditBy = $JWTdata->decoded->id;

        if(!isset($JWTdata->hotel_name) || empty($JWTdata->hotel_name)){
          throw new HotelException('Hotel Name is Invalid or Missing.', 201);
        }

        $hotelname		 = $JWTdata->hotel_name;

        if(!isset($JWTdata->brand_id) || empty($JWTdata->brand_id)){
          throw new HotelException('brand Id is Invalid or Missing.', 201);
        }

        $brandid		 = $JWTdata->brand_id;

        $suggestions = $this->hotelRepository->gnrteHtlCde($JWTdata, $brandid, $hotelname, $auditBy);
        $objLogger->info("======= END Hotel Action (Generated Hotel Code) ================");
        
        return $this->jsonResponse($response, 'Success', $suggestions, 200);

      } catch (HotelException $ex) {
              
        $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
        $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
        //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
        $objLogger->info("======= END Hotel Action (Generated Hotel Code) ================");
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
    public function excel(Request $request, Response $response, array $args): Response
    {
        $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
        $objLogger->info("======= Start Hotel Action (EXCEL) ================");
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

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new HotelException('JWT Token invalid or Expired.', 401);
          }
          $auditBy = $JWTdata->decoded->id;

          return $this->hotelRepository->excel($response, $JWTdata, $auditBy);


        } catch (HotelException $ex) {
              
          $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
          $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
          //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
          $objLogger->info("======= END Hotel Action (EXCEL) ================");
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
    
     public function bwStatus(Request $request, Response $response, array $args): Response
     {
        $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
        $objLogger->info("======= Start Hotel Action (BW Activate De-Activate) ================"); 
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
            
            $auditBy = $JWTdata->decoded->id;
  
            if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
              $objLogger->info("Hotel Unqiue Id : ".$args['id']);
            }
            else {
              throw new HotelException('Hotel Unqiue Id Empty.', 201);
            }
  
            if(!is_numeric($args['id'])){
              throw new HotelException('Invalid Parameter.', 201);
            }
  
            $resStatus = $this->hotelRepository->bwStatus($args['id'], $auditBy);
            
            $objLogger->info("update Status : ".$resStatus);
            $objLogger->info("======= END Hotel Action (BW Activate De-Activate) ================");
            return $this->jsonResponse($response, $resStatus, '', 200);
  
  
        }
        catch (HotelException $ex) {
                
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (BW Activate De-Activate) ================");
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
    
    public function alertEmailStatus(Request $request, Response $response, array $args): Response
    {
       $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
       $objLogger->info("======= Start Hotel Action (ALERT EMAIL Activate De-Activate) ================"); 
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
           
           $auditBy = $JWTdata->decoded->id;
 
           if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
             $objLogger->info("Hotel Unqiue Id : ".$args['id']);
           }
           else {
             throw new HotelException('Hotel Unqiue Id Empty.', 201);
           }
 
           if(!is_numeric($args['id'])){
             throw new HotelException('Invalid Parameter.', 201);
           }
 
           $resStatus = $this->hotelRepository->alertEmailStatus($args['id'], $auditBy);
           
           $objLogger->info("update Status : ".$resStatus);
           $objLogger->info("======= END Hotel Action (ALERT EMAIL Activate De-Activate) ================");
           return $this->jsonResponse($response, $resStatus, '', 200);
 
 
       }
       catch (HotelException $ex) {
               
           $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
           $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
           //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
           $objLogger->info("======= END Hotel Action (ALERT EMAIL Activate De-Activate) ================");
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
    
     public function icmpStatus(Request $request, Response $response, array $args): Response
     {
       $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
       $objLogger->info("======= Start Hotel Action (ICMP Activate De-Activate) ================"); 
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
           
           $auditBy = $JWTdata->decoded->id;
 
           if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
             $objLogger->info("Hotel Unqiue Id : ".$args['id']);
           }
           else {
             throw new HotelException('Hotel Unqiue Id Empty.', 201);
           }
 
           if(!is_numeric($args['id'])){
             throw new HotelException('Invalid Parameter.', 201);
           }
 
           $resStatus = $this->hotelRepository->icmpStatus($args['id'], $auditBy);
           
           $objLogger->info("update Status : ".$resStatus);
           $objLogger->info("======= END Hotel Action (ICMP Activate De-Activate) ================");
           return $this->jsonResponse($response, $resStatus, '', 200);
 
 
       }
       catch (HotelException $ex) {
               
           $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
           $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
           //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
           $objLogger->info("======= END Hotel Action (ICMP Activate De-Activate) ================");
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
    
     public function activeOrDeactive(Request $request, Response $response, array $args): Response
     {
       $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
       $objLogger->info("======= Start Hotel Action (Activate De-Activate) ================"); 
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
           
           $auditBy = $JWTdata->decoded->id;
 
           if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
             $objLogger->info("Hotel Unqiue Id : ".$args['id']);
           }
           else {
             throw new HotelException('Hotel Unqiue Id Empty.', 201);
           }
 
           if(!is_numeric($args['id'])){
             throw new HotelException('Invalid Parameter.', 201);
           }
 
           $resStatus = $this->hotelRepository->activeOrDeactive($args['id'], $auditBy);
           
           $objLogger->info("update Status : ".$resStatus);
           $objLogger->info("======= END Hotel Action (Activate De-Activate) ================");
           return $this->jsonResponse($response, $resStatus, '', 200);
 
 
       }
       catch (HotelException $ex) {
               
           $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
           $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
           //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
           $objLogger->info("======= END Hotel Action (Activate De-Activate) ================");
           if(!empty($ex->getMessage())){
               throw new HotelException($ex->getMessage(), $ex->getCode());
           }
           else {
               throw new HotelException(' Token invalid or Expired', 401);
           }
       }
     }

 
    /**
     * @throws CustomerException
     */
    public function gethotelsList(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (List) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new CustomerException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new CustomerException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new CustomerException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          $hotellist = $this->CustomerRepository->Viewhotellist($JWTdata);
          $objLogger->info("======= END Hotel Action (List) ================");
          
          return $this->jsonResponse($response, 'Success', $hotellist, 200);
        } catch (CustomerException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (List) ================");
            if(!empty($ex->getMessage())){
                throw new CustomerException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CustomerException(' Token invalid or Expired', 401);
            }
        }
    }

     /**
     * @throws CustomerException
     */
    public function create(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (CREATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new CustomerException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new CustomerException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new CustomerException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          $insStatus = $this->CustomerRepository->create($JWTdata);

          $objLogger->info("Insert Status : ".json_encode($insStatus));
          $objLogger->info("======= END Hotel Action (CREATE) ================");
            $returnmsg = $insStatus->msg;
            return $this->jsonResponse($response, $returnmsg, '', 200);
        } catch (CustomerException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (CREATE) ================");
            if(!empty($ex->getMessage())){
                throw new CustomerException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CustomerException(' Token invalid or Expired', 401);
            }
        }  
    }

    

     /**
     * @throws CustomerException
     */
    public function gethotelOne(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (Single One) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'GET'){
            throw new CustomerException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new CustomerException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new CustomerException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;


          $hotelid		 = isset($JWTdata->decoded->hotel_id)?$JWTdata->decoded->hotel_id:'1';
          $brandid		 = isset($JWTdata->decoded->brand_id)?$JWTdata->decoded->brand_idL:'2';

          $userName		 = isset($JWTdata->decoded->userName)?$JWTdata->decoded->userName:'';
        
          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("group id : ".$args['id']);
          }
          else {
            throw new CustomerException('Group Id Empty.', 201);
          }  
          

          $grpData = $this->CustomerRepository->getsinglehotel($args['id'], $userid,$userName);

          $objLogger->info("======= END Hotel Action (Single One) ================");
          
          return $this->jsonResponse($response, 'Success', $grpData, 200);
        } catch (CustomerException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (Single One) ================");
            if(!empty($ex->getMessage())){
                throw new CustomerException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CustomerException(' Token invalid or Expired', 401);
            }
        }  
    }

    /**
     * @throws CustomerException
     */
    public function update(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (UPDATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new CustomerException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new CustomerException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new CustomerException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("group id : ".$args['id']);
          }
          else {
            throw new CustomerException('Group Id Empty.', 201);
          }
          $hotelid = isset($args['id'])?$args['id']:'0';
          $insStatus = $this->CustomerRepository->update($JWTdata, $hotelid, $userid);
          //print_r($insStatus);die();
          $objLogger->info("Insert Status : ".json_encode($insStatus));
          $objLogger->info("======= END Hotel Action (UPDATE) ================");
    
            return $this->jsonResponse($response, $insStatus->msg, '', 200);
          
        } catch (CustomerException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (UPDATE) ================");
            if(!empty($ex->getMessage())){
                throw new CustomerException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CustomerException(' Token invalid or Expired', 401);
            }
        }  
    }


     /**
     * @throws CustomerException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelAction', 'HotelAction');
      $objLogger->info("======= Start Hotel Action (DELETE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new CustomerException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new CustomerException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new CustomerException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("group id : ".$args['id']);
          }
          else {
            throw new CustomerException('Group Id Empty.', 201);
          }
          $hotelid = isset($args['id'])?$args['id']:'0';
          $insStatus = $this->CustomerRepository->delete($JWTdata, $hotelid, $userid);
          //print_r($insStatus);die();
          $objLogger->info("Insert Status : ".json_encode($insStatus));
          $objLogger->info("======= END Hotel Action (DELETE) ================");
    
            return $this->jsonResponse($response, $insStatus->msg, '', 200);
          
        } catch (CustomerException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Hotel Action (DELETE) ================");
            if(!empty($ex->getMessage())){
                throw new CustomerException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CustomerException(' Token invalid or Expired', 401);
            }
        }  
    }


}
?>