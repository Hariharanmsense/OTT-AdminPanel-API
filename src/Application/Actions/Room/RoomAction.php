<?php
declare(strict_types=1);
namespace App\Application\Actions\Room;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Room\RoomException;

use App\Domain\Repository\Room\RoomRepository;
use App\Application\Auth\JwtToken;


final class RoomAction extends Action
{
    protected RoomRepository $roomRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;

    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, RoomRepository $roomRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->roomRepository = $roomRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }

     /**
     * @throws RoomException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
          $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;          
          $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('BrandAction');
        try {  
         
          $objLogger->info("======= Start Room Action (VIEW)================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new RoomException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new RoomException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new RoomException('JWT Token invalid or Expired.', 401);
          }
        
          $viewbranddata = $this->roomRepository->Viewroomlist($jsndata);       
          $objLogger->info("======= END Room Action  (VIEW) ================");
          return $this->jsonResponse($response, 'Success',$viewbranddata, 200);

        } catch (RoomException $e) {
            throw new RoomException($e->getMessage(), 401);
        }
    }

    public function create(Request $request, Response $response, array $args): Response{
        $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;
          $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('BrandAction');
        try {   
          
          $objLogger->info("======= Start Room Action (CREATE) ================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new RoomException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new RoomException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new RoomException('JWT Token invalid or Expired.', 401);
          }
        
          $addBrandData = $this->roomRepository->create($jsndata);   
          $objLogger->info("======= END Room Action (CREATE)================");
          $returnmsg = $addBrandData->msg;
          return $this->jsonResponse($response, $returnmsg,'', 200);

        } catch (RoomException $e) {
            throw new RoomException($e->getMessage(), 401);
        }
    }

    public function getOneroom(Request $request, Response $response, array $args): Response
    {
      $JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;      
      $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('BrandAction');
        try {  
          
          $objLogger->info("======= Start Room Action  (GETONE) ================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'GET'){
            throw new RoomException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new RoomException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $brandid = isset($args['id'])?$args['id']:'0';
        
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new RoomException('JWT Token invalid or Expired.', 401);
          }
          
          $edtbranddata = $this->roomRepository->getOne($brandid,$jsndata,$userName); 
          $objLogger->info("======= END Room Action (GETONE) ================");
          return $this->jsonResponse($response, 'Success',$edtbranddata, 200);

        } catch (RoomException $e) {
            throw new RoomException($e->getMessage(), 401);
        }
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        try {  
          $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;
          
          $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('BrandAction');
          $objLogger->info("======= Start Room Action (UPDATE) ================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new RoomException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new RoomException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $roomid = isset($args['id'])? $args['id']:'0';
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new RoomException('JWT Token invalid or Expired.', 401);
          }

          $updatebrnd = $this->roomRepository->update($jsndata,$roomid); 
          //print_r($updatebrnd);die();
          $objLogger->info("======= END Room Action (UPDATE) ================");
          $updateMsg = $updatebrnd->msg;
          return $this->jsonResponse($response, $updateMsg,'', 200);

        } catch (RoomException $e) {
            throw new RoomException($e->getMessage(), 401);
        }
    }
  
    
    public function delete(Request $request, Response $response, array $args): Response
    {
       $JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;      
      $objLogger = $this->loggerFactory->addFileHandler('RoomModel_'.$userName.'.log')->createInstance('BrandAction');
        try {  
          
          $objLogger->info("======= Start Room Action (DELETE)================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'DELETE'){
            throw new RoomException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new RoomException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $brandid = isset($args['id'])?$args['id']:'0';
        
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new RoomException('JWT Token invalid or Expired.', 401);
          }

          $deletebrndData = $this->roomRepository->delete($brandid,$jsndata); 
          $objLogger->info("======= END Room Action (DELETE)================");
          $deletemsg = $deletebrndData->msg;
          return $this->jsonResponse($response, $deletemsg,'', 200);

        } catch (RoomException $e) {
            throw new RoomException($e->getMessage(), 401);
        }
    }

}
?>