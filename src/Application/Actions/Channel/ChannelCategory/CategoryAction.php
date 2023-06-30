<?php
declare(strict_types=1);
namespace App\Application\Actions\Channel\ChannelCategory;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Channel\ChannelException;

use App\Domain\Repository\Channel\ChannelCategory\CategoryRepository;
use App\Application\Auth\JwtToken;


final class CategoryAction extends Action
{
    protected CategoryRepository $categoryRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;

    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, CategoryRepository $categoryRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->categoryRepository = $categoryRepository;
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
          $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('CategoryAction');
        try {  
         
          $objLogger->info("======= Start Category Action (Single One) ================");
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
        
          $viewbranddata = $this->categoryRepository->ViewCategorylist($jsndata);       
          $objLogger->info("======= END Channel Action (CREATE) ================");
          return $this->jsonResponse($response, 'Success',$viewbranddata, 200);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 401);
        }
    }

    public function create(Request $request, Response $response, array $args): Response{
        $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;
          $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('CategoryAction');
        try {   
          
          $objLogger->info("======= Start Category Action (Create) ================");
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
        
          $addCategory = $this->categoryRepository->create($jsndata);   
          $objLogger->info("======= END Channel Action (Create) ================");
          $returnmsg = $addCategory->msg;
          return $this->jsonResponse($response, $returnmsg,'', 200);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 401);
        }
    }

   public function getOneategorydetail(Request $request, Response $response, array $args): Response
    {
      $JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;      
      $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('CategoryAction');
        try {  
          
          $objLogger->info("======= Start Category Action (Single One) ================");
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
          $categoryid = isset($args['id'])?$args['id']:'0';
        
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }

          $edtbranddata = $this->categoryRepository->getOneCategory($categoryid,$userid,$userName); 
          $objLogger->info("======= END Category Action (Single One) ================");
          return $this->jsonResponse($response, 'Success',$edtbranddata, 200);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 401);
        }
    }

     public function update(Request $request, Response $response, array $args): Response
    {
        try {  
          $JWTdata = $this->getJsonFromParsedBodyData($request);
          $userName = $JWTdata->decoded->userName;
          $userid = $JWTdata->decoded->id;
          
          $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('CategoryAction');
          $objLogger->info("======= Start Category Action (UPDATE) ================");
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
          $categoryid = isset($args['id'])?$args['id']:'';
          $updatemodel = $this->categoryRepository->update($jsndata,$categoryid,$userid); 
    
          $updateMsg = $updatemodel->msg;
          $objLogger->info("======= END Category Action (UPDATE) ================");
          return $this->jsonResponse($response, $updateMsg,'', 200);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 401);
        }
    }
  
    
    public function delete(Request $request, Response $response, array $args): Response
    {
       $JWTdata = $this->getJsonFromParsedBodyData($request);
      $userName = $JWTdata->decoded->userName;      
      $objLogger = $this->loggerFactory->addFileHandler('CategoryModel_'.$userName.'.log')->createInstance('CategoryAction');
        try {  
          
          $objLogger->info("======= Start Category Action (Delete) ================");
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
          $categoryid = isset($args['id'])?$args['id']:'0';
        
         
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new ChannelException('JWT Token invalid or Expired.', 401);
          }

          $deletebrndData = $this->categoryRepository->delete($categoryid,$userid,$userName); 
          $objLogger->info("======= END Category  Action (Delete) ================");
          $deletemsg = $deletebrndData->msg;
          return $this->jsonResponse($response, $deletemsg,'', 200);

        } catch (ChannelException $e) {
            throw new ChannelException($e->getMessage(), 401);
        }
    }

}
?>