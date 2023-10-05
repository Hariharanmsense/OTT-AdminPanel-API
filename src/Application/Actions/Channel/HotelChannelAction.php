<?php
declare(strict_types=1);
namespace App\Application\Actions\Channel;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Channel\ChannelException;

use App\Domain\Repository\Channel\HotelChannelRepository;
use App\Application\Auth\JwtToken;


final class HotelChannelAction extends Action
{
    protected HotelChannelRepository $channelRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;

    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, HotelChannelRepository $channelRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->channelRepository = $channelRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }

     /**
     * @throws ChannelException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
          $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;          
          $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelAction');
        try {  
         
          $objLogger->info("======= Start HotelChannel Action (Single One) ================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new ChannelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new ChannelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }
        
          $viewbranddata = $this->channelRepository->ViewChannellist($jsndata);       
          $objLogger->info("======= END Channel Action (CREATE) ================");
          return $this->jsonResponse($response, 'Success',$viewbranddata, 200);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 201);
        }
    }

    public function create(Request $request, Response $response, array $args): Response{
        $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;
          $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelAction');
        try {   
          
          $objLogger->info("======= Start HotelChannel Action (Create) ================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new ChannelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new ChannelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }
        
          $addHotelChannel = $this->channelRepository->create($jsndata);   
          $objLogger->info("======= END Channel Action (Create) ================");
          $returnmsg = $addHotelChannel->msg;
          $returncode = isset($addHotelChannel->status)?$addHotelChannel->status:'';

          if($returncode == 'Success' ):
            return $this->jsonResponse($response, $returnmsg,'', 200);
          else:
            return $this->jsonResponse($response, $returnmsg,'', 201);
          endif;

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 201);
        }
    }

   public function getOneHotelchannel(Request $request, Response $response, array $args): Response
    {
      $JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;      
      $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelAction');
        try {  
          
          $objLogger->info("======= Start HotelChannel Action (Single One) ================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'GET'){
            throw new ChannelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new ChannelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $userid = $JWTdata->decoded->id;  
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $HotelChannelid = isset($args['id'])?$args['id']:'0';
        
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }

          $edtbranddata = $this->channelRepository->getOneHotelChannel($HotelChannelid,$userid,$userName); 
          $objLogger->info("======= END HotelChannel Action (Single One) ================");
          return $this->jsonResponse($response, 'Success',$edtbranddata, 200);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 201);
        }
    }

     public function update(Request $request, Response $response, array $args): Response
    {
        try {  
          $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;
          $userid = $JWTdata->decoded->id;
          
          $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelAction');
          $objLogger->info("======= Start HotelChannel Action (UPDATE) ================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'PUT'){
            throw new ChannelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new ChannelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }
          $HotelChannelid = isset($args['id'])?$args['id']:'';
          $updatemodel = $this->channelRepository->update($jsndata,$HotelChannelid,$userid); 
    
          $updateMsg = $updatemodel->msg;
          $objLogger->info("======= END HotelChannel Action (UPDATE) ================");
          return $this->jsonResponse($response, $updateMsg,'', 200);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 201);
        }
    }
  
    
    public function delete(Request $request, Response $response, array $args): Response
    {
       $JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;      
      $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelAction');
        try {  
          
          $objLogger->info("======= Start HotelChannel Action (Delete) ================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'DELETE'){
            throw new ChannelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new ChannelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $userid = $JWTdata->decoded->id;
          $HotelChannelid = isset($args['id'])?$args['id']:'0';
        
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }

          $deletebrndData = $this->channelRepository->delete($HotelChannelid,$userid,$userName); 
          $objLogger->info("======= END HotelChannel  Action (Delete) ================");
          $deletemsg = $deletebrndData->msg;
          return $this->jsonResponse($response, $deletemsg,'', 200);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 201);
        }
    }

    public function assginMenu(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('HotelChannelModel', 'HotelChannelAction');
      $objLogger->info("======= Start HotelChannel Action (ASSIGN MENUS) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new ChannelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new ChannelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          /*
          if(!isset($JWTdata->menuid)){
             throw new ChannelException('Invalid Parameter Missing.', 401);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->admnGrpsRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new ChannelException('Invalid Access.', 401);
          }
          */

          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;
          $userName = $JWTdata->decoded->userName;
        
          $insStatus = $this->channelRepository->assginMenu($JWTdata,$userName, $userid);
          $objLogger->info("Insert Status : ".$insStatus);
          $objLogger->info("======= END HotelChannel Action (ASSING MENUS) ================");
          if($insStatus == 'SUCCESS'){
            return $this->jsonResponse($response, 'Added Successfully', '', 200);
          }
          else {
            return $this->jsonResponse($response, 'Not Added.', '', 200);
          }
        } catch (ChannelException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END HotelChannel Action (ASSIGN MENUS) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException(' Token invalid or Expired', 401);
            }
        }  
    }

	public function getOverallchannellist(Request $request, Response $response, array $args): Response
    {
		 //print_r("HAI");die();
      $JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;      
      $objLogger = $this->loggerFactory->addFileHandler('HotelChannelModel_'.$userName.'.log')->createInstance('HotelChannelAction');
	 
        try {  
          
          $objLogger->info("======= Start HotelChannel Action (Single One) ================");
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new ChannelException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('content-type');
          $objLogger->info("contentType : ".$contentType);
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new ChannelException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $userid = $JWTdata->decoded->id;  
          $objLogger->info("Input Data : ".json_encode($jsndata));		  
		      $hotelid = isset($JWTdata->hotelid)?$JWTdata->hotelid:'0';	   
		               
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }
		  
          $edtbranddata = $this->channelRepository->getOverallchannellist($hotelid,$userid,$userName); 
          $objLogger->info("======= END HotelChannel Action (Single One) ================");
          return $this->jsonResponse($response, 'Success',$edtbranddata, 200);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 201);
        }
    }

}
?>