<?php
declare(strict_types=1);
namespace App\Application\Actions\Channel;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Channel\ChannelException;

use App\Domain\Repository\Channel\ChannelRepository;
use App\Application\Auth\JwtToken;


final class ChannelAction extends Action
{
    protected ChannelRepository $channelrepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;
    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, ChannelRepository $channelrepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->channelrepository = $channelrepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }

    public function channelExcel(Request $request, Response $response, array $args): Response
    {
        $objLogger = $this->loggerFactory->getFileObject('ChannelAction', 'excel');
        
        try {
          $objLogger->info("======= Start Channel Action (EXCEL) ================");
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

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }
          $auditBy = $JWTdata->decoded->id;
          $userName = $JWTdata->decoded->userName;
          $objLogger->info("======= END Channel Action (EXCEL) ================");
          return $this->channelrepository->excel($response,$JWTdata, $auditBy,$userName);


        } catch (ChannelException $ex) {
              
          $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
          $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
          //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
          $objLogger->info("======= END Channel Action (EXCEL) ================");
          if(!empty($ex->getMessage())){
              throw new ChannelException($ex->getMessage(), $ex->getCode());
          }
          else {
              throw new ChannelException(' Token invalid or Expired', 401);
          }
        } 
    }
    /**
     * @throws ChannelException
     */
    public function getchannelsList(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('ChannelAction', 'ChannelAction');
      $objLogger->info("======= Start Channel Action (List) ================");  
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
          $userName = $JWTdata->decoded->userName;  

          $hotellist = $this->channelrepository->ViewChannellist($JWTdata,$userid,$userName);
          $objLogger->info("======= END Channel Action (List) ================");
          
          return $this->jsonResponse($response, 'Success', $hotellist, 200);
        } catch (ChannelException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Channel Action (List) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException(' Token invalid or Expired', 401);
            }
        }
    }

     /**
     * @throws ChannelException
     */
    public function create(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('ChannelAction', 'ChannelAction');
      $objLogger->info("======= Start Channel Action (CREATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new ChannelException('Invalid Method', 500);
          }
		
          $uploadedFiles = $request->getUploadedFiles();
          $channelimg = isset($uploadedFiles['channelimg'])?$uploadedFiles['channelimg'] :'';		  
          $jsndata = $this->getParsedBodyData($request);

          $objLogger->info("Input Data : ".json_encode($jsndata));	   

          $JWTdata = $this->getJsonFromParsedBodyData($request);
		  		  
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;
          $userName = $JWTdata->decoded->userName;  

            
        $temp_filename = isset($_FILES["channelimg"]["tmp_name"])?$_FILES["channelimg"]["tmp_name"]:'';
      
        
        
        $staticwidth = 600;
        $staticheight = 292;		   
        $width = 0;
        $height = 0;
        $fileinfo = '';
        if(!empty($temp_filename)){
          $fileinfo = @getimagesize($temp_filename);
          $width = $fileinfo[0];
          $height = $fileinfo[1];
        }
        
			
			if(($staticwidth < $width) || ($staticheight < $height ) || (($staticwidth < $width)&&($staticheight < $height))){
				throw new ChannelException('Channel logo size must 600*290.', 201);
			}
			
          

          $insStatus = $this->channelrepository->create($JWTdata,$channelimg,$userid,$userName);

          $objLogger->info("Insert Status : ".json_encode($insStatus));
          $objLogger->info("======= END Channel Action (CREATE) ================");
            $returnmsg = $insStatus->msg;
            return $this->jsonResponse($response, $returnmsg, '', 200);
        } catch (ChannelException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Channel Action (CREATE) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException(' Token invalid or Expired', 201);
            }
        }  
    }
    

     /**
     * @throws ChannelException
     */
    public function getOnechanneldetail(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('ChannelAction', 'ChannelAction');
      $objLogger->info("======= Start Channel Action (Single One) ================");  
      try {  

              

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
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $input = $request->getParsedBody();
          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;

          $userName		 = isset($JWTdata->decoded->userName)?$JWTdata->decoded->userName:'';
        
          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("group id : ".$args['id']);
          }
          else {
            throw new ChannelException('Group Id Empty.', 201);
          }  
          

          $grpData = $this->channelrepository->getOneChannel($args['id'], $userid,$userName);

          $objLogger->info("======= END Channel Action (Single One) ================");
          
          return $this->jsonResponse($response, 'Success', $grpData, 200);
        } catch (ChannelException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Channel Action (Single One) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException(' Token invalid or Expired', 401);
            }
        }  
    }

    /**
     * @throws ChannelException
     */
    public function update(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('ChannelAction', 'ChannelAction');
      $objLogger->info("======= Start Channel Action (UPDATE) ================");  
      try {  
          
          $method = $request->getMethod();
          $objLogger->info("method : ".$method);
          if(strtoupper($method) != 'POST'){
            throw new ChannelException('Invalid Method', 500);
          }
		

          $JWTdata = $this->getJsonFromParsedBodyData($request);
		 
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }
          $userid = $JWTdata->decoded->id;
          $userName		 = isset($JWTdata->decoded->userName)?$JWTdata->decoded->userName:'';
		      $uploadedFiles = $request->getUploadedFiles();
		     $channelimg = isset($uploadedFiles['channelimg']) ?$uploadedFiles['channelimg'] :'';

          if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
            $objLogger->info("Channel id : ".$args['id']);
          }
          else {
            throw new ChannelException('Channel Id Empty.', 201);
          }
		  
		  $temp_filename = isset($_FILES["channelimg"]["tmp_name"])?$_FILES["channelimg"]["tmp_name"]:'';
		  //$fileinfo = @getimagesize($temp_filename);
		 // print_r($fileinfo);die();
		  
		  
			$staticwidth = 600;
			$staticheight = 292;		   
			$width = 0;
			$height = 0;
			$fileinfo = '';
			if(!empty($temp_filename)){
				$fileinfo = @getimagesize($temp_filename);
				$width = $fileinfo[0];
				$height = $fileinfo[1];
			}
			
			//echo($staticwidth.'<'.$width.'&&'.$staticheight.'<'.$height);die();
			
			if(($staticwidth < $width) || ($staticheight < $height ) || (($staticwidth < $width)&&($staticheight < $height))){
				throw new ChannelException('Channel logo size must 600*290.', 201);
			}
			
		  
          $channelid = isset($args['id'])?$args['id']:'0';
          $insStatus = $this->channelrepository->update($JWTdata, $channelid, $userid,$userName,$channelimg);
          //print_r($insStatus);die();
          $objLogger->info("Insert Status : ".json_encode($insStatus));
          $objLogger->info("======= END Channel Action (UPDATE) ================");
    
            return $this->jsonResponse($response, $insStatus->msg, '', 200);
          
        } catch (ChannelException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Channel Action (UPDATE) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new ChannelException(' Token invalid or Expired', 401);
            }
        }  
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
           
      $objLogger = $this->loggerFactory->addFileHandler('ChannelAction.log')->createInstance('ChannelAction');
        try {  
          
          $objLogger->info("======= Start Edit Brand Action ================");
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
          $channelid = isset($args['id'])?$args['id']:'0';
        
          $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;  
          $userid = $JWTdata->decoded->id;
 
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }

          $deletechannel = $this->channelrepository->delete($channelid,$userid,$userName); 
          $objLogger->info("======= END Edit Brand Action ================");
          $deletemsg = $deletechannel->msg;
          return $this->jsonResponse($response, $deletemsg,'', 201);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 401);
        }
    }


}
?>