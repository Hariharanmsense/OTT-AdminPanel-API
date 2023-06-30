<?php
declare(strict_types=1);
namespace App\Application\Actions\Tvsolution;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Tvsolution\TvtempException;
use App\Domain\Repository\Tvsolution\TvtempRepository;
use App\Application\Auth\JwtToken;

final class TVtempAction extends Action
{
    protected TvtempRepository $TvtempRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;

    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, TvtempRepository $TvtempRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->TvtempRepository = $TvtempRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }


    /**
     * @throws TvtempException
     */
    public function getalltemplates(Request $request, Response $response, array $args) : Response
    {
        $objLogger = $this->loggerFactory->getFileObject('TvAction', 'TVtempAction');
        $objLogger->info("======= Start Tv Action (Get All Template List) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new TvtempException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new TvtempException('Invalid ContentType', 500);
            }
			

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new TvtempException('JWT Token invalid or Expired.', 401);
            }

            $auditBy = $JWTdata->decoded->userName;
			

            $templatelist = $this->TvtempRepository->getAlltemplate($JWTdata,$auditBy);
            $objLogger->info("======= END Tv Action (Get All Template List) ================");
            return $this->jsonResponse($response, 'Success', $templatelist, 200);   

        } catch (TvtempException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Tv Action (Get All Template List) ================");
            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException(' Token invalid or Expired', 401);
            }
        }
    }
	
	public function getallchannelfeed(Request $request, Response $response, array $args) : Response
    {
        $objLogger = $this->loggerFactory->getFileObject('TvAction', 'TVtempAction');
        $objLogger->info("======= Start Tv Action (Get All Template List) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new TvtempException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new TvtempException('Invalid ContentType', 500);
            }
			

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new TvtempException('JWT Token invalid or Expired.', 401);
            }

            $auditBy = $JWTdata->decoded->userName;
			

            $templatelist = $this->TvtempRepository->getallchannelfeed($JWTdata,$auditBy);
            $objLogger->info("======= END Tv Action (Get All Template List) ================");
            return $this->jsonResponse($response, 'Success', $templatelist, 200);   

        } catch (TvtempException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Tv Action (Get All Template List) ================");
            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException(' Token invalid or Expired', 401);
            }
        }
    }
	
	
	
	public function getallfeatures(Request $request, Response $response, array $args) : Response
    {
        $objLogger = $this->loggerFactory->getFileObject('TvAction', 'TVtempAction');
        $objLogger->info("======= Start Tv Action (Get All Feature List) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new TvtempException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new TvtempException('Invalid ContentType', 500);
            }
			

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new TvtempException('JWT Token invalid or Expired.', 401);
            }

            $auditBy = $JWTdata->decoded->userName;
			

            $templatelist = $this->TvtempRepository->getallfeatures($JWTdata,$auditBy);
            $objLogger->info("======= END Tv Action (Get All Feature List) ================");
            return $this->jsonResponse($response, 'Success', $templatelist, 200);   

        } catch (TvtempException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Tv Action (Get All Feature List) ================");
            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException(' Token invalid or Expired', 401);
            }
        }
    }

    public function getJsonObjects(Request $request, Response $response, array $args) : Response
    {
        $objLogger = $this->loggerFactory->getFileObject('TvAction', 'TVtempAction');
        $objLogger->info("======= Start Tv Action (Read Json File) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new TvtempException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new TvtempException('Invalid ContentType', 500);
            }
			

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new TvtempException('JWT Token invalid or Expired.', 401);
            }
            $auditBy = $JWTdata->decoded->id;
            $userName = $JWTdata->decoded->userName;
			$templateid = isset($args['id']) ? $args['id']: '0';

            $templatelist = $this->TvtempRepository->getJsonfile($templateid,$auditBy,$userName);
            $objLogger->info("======= END Tv Action (Read Json File ) ================");
            return $this->jsonResponse($response, 'Success', $templatelist, 200);   

        } catch (TvtempException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Tv Action (Read Json File ) ================");
            if(!empty($ex->getMessage())){
                throw new TvtempException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new TvtempException(' Token invalid or Expired', 401);
            }
        }
    }

   
}
