<?php
declare(strict_types=1);
namespace App\Application\Actions\Brand;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Brand\BrandException;

use App\Domain\Repository\Brand\BrandRepository;
use App\Application\Auth\JwtToken;


final class BrandAction extends Action
{
    protected BrandRepository $brandRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;

    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, BrandRepository $brandRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->brandRepository = $brandRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }

     /**
     * @throws BrandException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
          $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;          
          $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandAction');
        try {  
         
          $objLogger->info("======= Start Brand Action (VIEW)================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new BrandException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new BrandException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new BrandException('JWT Token invalid or Expired.', 401);
          }
        
          $viewbranddata = $this->brandRepository->ViewbrandListRepository($JWTdata);       
          $objLogger->info("======= END Brand Action (VIEW)================");
          return $this->jsonResponse($response, 'Success',$viewbranddata, 201);

        } catch (BrandException $e) {
            throw new BrandException($e->getMessage(), 401);
        }
    }

    public function AddBrandAction(Request $request, Response $response, array $args): Response{
        $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;
          $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandAction');
        try {   
          
          $objLogger->info("======= Start Brand Action (ADD)================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new BrandException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new BrandException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new BrandException('JWT Token invalid or Expired.', 401);
          }
        
          $addBrandData = $this->brandRepository->AddnewBrand($jsndata);   
          $objLogger->info("======= END Brand Action (ADD)================");
          $returnmsg = $addBrandData->msg;
          return $this->jsonResponse($response, $returnmsg,'', 201);

        } catch (BrandException $e) {
            throw new BrandException($e->getMessage(), 401);
        }
    }

    public function EditViewAction(Request $request, Response $response, array $args): Response
    {
      $JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;      
      $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandAction');
        try {  
          
          $objLogger->info("======= Start Brand Action (EDIT)================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'GET'){
            throw new BrandException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new BrandException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $brandid = isset($args['id'])?$args['id']:'0';
        
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new BrandException('JWT Token invalid or Expired.', 401);
          }

          $edtbranddata = $this->brandRepository->EditViewRepository($brandid,$jsndata); 
          $objLogger->info("======= END Brand Action (EDIT)================");
          return $this->jsonResponse($response, 'Success',$edtbranddata, 200);

        } catch (BrandException $e) {
            throw new BrandException($e->getMessage(), 401);
        }
    }

    public function UpdateBrandAction(Request $request, Response $response, array $args): Response
    {
        try {  
          $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;
          
          $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandAction');
          $objLogger->info("======= Start Brand Action (UPDATE)================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new BrandException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new BrandException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new BrandException('JWT Token invalid or Expired.', 401);
          }

          $updatebrnd = $this->brandRepository->UpdateRepository($jsndata); 
          //print_r($updatebrnd);die();
          $objLogger->info("======= End Brand Action (UPDATE)================");
          $updateMsg = $updatebrnd->msg;
          return $this->jsonResponse($response, $updateMsg,'', 201);

        } catch (BrandException $e) {
            throw new BrandException($e->getMessage(), 401);
        }
    }
  
    
    public function DeleteBrandAction(Request $request, Response $response, array $args): Response
    {
       $JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;      
      $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandAction');
        try {  
          
          $objLogger->info("======= Start Brand Action (DELETE)================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'DELETE'){
            throw new BrandException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new BrandException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $brandid = isset($args['id'])?$args['id']:'0';
        
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new BrandException('JWT Token invalid or Expired.', 401);
          }

          $deletebrndData = $this->brandRepository->DeleteBrandRepository($brandid,$jsndata); 
          $objLogger->info("======= END Brand Action (DELETE)================");
          $deletemsg = $deletebrndData->msg;
          return $this->jsonResponse($response, $deletemsg,'', 200);

        } catch (BrandException $e) {
            throw new BrandException($e->getMessage(), 401);
        }
    }

    public function activeordeactive(Request $request, Response $response, array $args): Response
    {
       $JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;      
      $objLogger = $this->loggerFactory->addFileHandler('BrandModel_'.$userName.'.log')->createInstance('BrandAction');
        try {  
          
          $objLogger->info("======= Start Brand Action (ACTIVE OR DEACTIVE)================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new BrandException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new BrandException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
        
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new BrandException('JWT Token invalid or Expired.', 401);
          }
          $brandid = isset($args['id'])?$args['id']:'0';
          $actordeact = $this->brandRepository->actordeact($jsndata,$brandid); 
          $objLogger->info("======= END Brand Action (ACTIVE OR DEACTIVE)================");
          $deletemsg = $actordeact->msg;
          return $this->jsonResponse($response, $deletemsg,'', 200);

        } catch (BrandException $e) {
            throw new BrandException($e->getMessage(), 401);
        }
    }
	
	 public function excel(Request $request, Response $response, array $args): Response
    {
		$JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;
      $objLogger = $this->loggerFactory->getFileObject('BrandModel_'.$userName, 'BrandAction');
      $objLogger->info("======= Start Brand Action (EXCEL) ================");
      try {
        $method = $request->getMethod();
        $objLogger->info("method : ".$method);
        if(strtoupper($method) != 'POST'){
          throw new BrandException('Invalid Method', 500);
        }

        $contentType = $request->getHeaderLine('content-type');
        $objLogger->info("contentType : ".$contentType);
        if(strtoupper($contentType) != 'APPLICATION/JSON'){
          throw new BrandException('Invalid ContentType', 500);
        }

        $jsndata = $this->getParsedBodyData($request);
        $objLogger->info("Input Data : ".json_encode($jsndata));

        $JWTdata = $this->getJsonFromParsedBodyData($request);
        if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
        {
            throw new BrandException('JWT Token invalid or Expired.', 401);
        }
        
		$auditBy = $JWTdata->decoded->id;
		
		//print_R($JWTdata->decoded->hotelId);die();

        if(!isset($JWTdata->decoded->hotelId) || empty($JWTdata->decoded->hotelId)){
          throw new BrandException('Hotel Id is Invalid or Missing.', 201);
        }
	
        $hotelid = $JWTdata->decoded->hotelId;		
        $brandid = 0;
		
        return $this->brandRepository->excel($response, $JWTdata, $auditBy, $hotelid, $brandid);


      } catch (BrandException $ex) {
            
        $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
        $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
        //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
        $objLogger->info("======= END Brand Action (EXCEL) ================");
        if(!empty($ex->getMessage())){
            throw new BrandException($ex->getMessage(), $ex->getCode());
        }
        else {
            throw new BrandException(' Token invalid or Expired', 401);
        }
    } 
    }

}
?>