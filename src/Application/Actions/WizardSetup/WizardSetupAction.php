<?php
declare(strict_types=1);
namespace App\Application\Actions\WizardSetup;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


use App\Application\Actions\Action;
use App\Exception\WizardSetup\WizardSetupException;
use App\Domain\Repository\WizardSetup\WizardSetupRepository;
use App\Application\Auth\JwtToken;

final class WizardSetupAction extends Action
{
    protected WizardSetupRepository $WizardSetupRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;

    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, WizardSetupRepository $WizardSetupRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->WizardSetupRepository = $WizardSetupRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }

     

    /**
     * @throws WizardSetupException
     */

    public function updatetemplate(Request $request, Response $response, array $args) : Response
    {
        $objLogger = $this->loggerFactory->getFileObject('WizardSetupAction', 'WizardSetupAction');
        $objLogger->info("======= Start WizardSetup Action (Update Template) ================");  
        try {
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'PUT'){
              throw new WizardSetupException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new WizardSetupException('Invalid ContentType', 500);
            }
			

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new WizardSetupException('JWT Token invalid or Expired.', 401);
            }

            $userId = $JWTdata->decoded->id;
            $userName = $JWTdata->decoded->userName;
            $updatetemplate = $this->WizardSetupRepository->getAlltemplate($JWTdata,$userId,$userName);
            $objLogger->info("======= END WizardSetup Action (Update Template) ================");
            return $this->jsonResponse($response, 'Success', $updatetemplate->msg, 200);

        }catch (WizardSetupException $ex) {
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END WizardSetup Action (Update Template) ================");
            if(!empty($ex->getMessage())){
                throw new WizardSetupException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new WizardSetupException(' Token invalid or Expired', 401);
            }
        }
    }

    /**
     * @throws WizardSetupException
     */

     public function create(Request $request, Response $response, array $args) : Response
     {
         $objLogger = $this->loggerFactory->getFileObject('WizardSetupAction', 'WizardSetupAction');
         $objLogger->info("======= Start WizardSetup Action (Add Features to hotel) ================");  
         try {
             $method = $request->getMethod();
             $objLogger->info("method : ".$method);
             if(strtoupper($method) != 'POST'){
               throw new WizardSetupException('Invalid Method', 500);
             }
 
             $contentType = $request->getHeaderLine('content-type');
             $objLogger->info("contentType : ".$contentType);
             if(strtoupper($contentType) != 'APPLICATION/JSON'){
                 throw new WizardSetupException('Invalid ContentType', 500);
             }
             
 
             $jsndata = $this->getParsedBodyData($request);
             $objLogger->info("Input Data : ".json_encode($jsndata));
 
             $JWTdata = $this->getJsonFromParsedBodyData($request);
             if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
             {
                 throw new WizardSetupException('JWT Token invalid or Expired.', 401);
             }
 
             $userId = $JWTdata->decoded->id;
             $userName = $JWTdata->decoded->userName;
             $addFeauters= $this->WizardSetupRepository->AddFeatures($JWTdata,$userId,$userName);
             $objLogger->info("======= END WizardSetup Action (Add Features to hotel) ================");
			 if(!empty($addFeauters)){
				 return $this->jsonResponse($response, 'Success', $addFeauters->msg, 200);
			 }else{
				 return $this->jsonResponse($response, 'Success', 'No Data found', 201);
			 }
			 
             
 
         }catch (WizardSetupException $ex) {
             $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
             $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
             //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
             $objLogger->info("======= END WizardSetup Action (Add Features to hotel) ================");
             if(!empty($ex->getMessage())){
                 throw new WizardSetupException($ex->getMessage(), $ex->getCode());
             }
             else {
                 throw new WizardSetupException(' Token invalid or Expired', 401);
             }
         }
    }

    /**
     * @throws WizardSetupException
     */

     public function GenerateJsonFile(Request $request, Response $response, array $args) : Response
     {
         $objLogger = $this->loggerFactory->getFileObject('WizardSetupAction', 'WizardSetupAction');
         $objLogger->info("======= Start WizardSetup Action (Add Features to hotel) ================");  
         try {
             $method = $request->getMethod();
             $objLogger->info("method : ".$method);
             if(strtoupper($method) != 'POST'){
               throw new WizardSetupException('Invalid Method', 500);
             }
 
          
 
             $jsndata = $this->getParsedBodyData($request);
             $objLogger->info("Input Data : ".json_encode($jsndata));
 
             $JWTdata = $this->getJsonFromParsedBodyData($request);
             if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
             {
                 throw new WizardSetupException('JWT Token invalid or Expired.', 401);
             }
             $file = $request->getUploadedFiles();
             $logo = isset($file['logo']) ? $file['logo'] :'';
             $bgimg = isset($file['bgimg'])? $file['bgimg']:'';
             $menuicon = isset($file['menuicon'])? $file['menuicon']:'';
             $allowed = array('jpeg', 'png', 'jpg');
             $fixedLogowidth = 150;
             $fixedlogoheight = 130;
             $logowidth = '';
             $logoheight = '';
             $logoValidation = isset($_FILES["logo"]["tmp_name"])?$_FILES['logo']['tmp_name'] :'';
             if(!empty($logoValidation)){
                $logoresloution = @getimagesize($logoValidation);
                $logowidth = $logoresloution[0];
                $logoheight = $logoresloution[1];
             }
             $ext = pathinfo($logo->getClientFilename(), PATHINFO_EXTENSION);
            //print_R($logoValidation);die();
            if (!in_array($ext, $allowed)) {
                throw new WizardSetupException('Please upload a valid logo image', 201);
            }
            /*if(($fixedLogowidth < $logowidth) || ($fixedlogoheight < $logoheight ) || (($fixedLogowidth < $logowidth)&&($fixedlogoheight < $logoheight))){
				throw new WizardSetupException('Logo size must 150*130.', 201);
			}*/

            $bgimgwidth = '';
            $bgimgheight = '';
            $staticbgwidth = 1920; 
            $staticbgheight = 1080;
            $bgValidation = isset($_FILES["bgimg"]["tmp_name"])?$_FILES['bgimg']['tmp_name'] :'';
             if(!empty($logoValidation)){
                $bgimgresloution = @getimagesize($bgValidation);
                $bgimgwidth = $bgimgresloution[0];
                $logoheight = $bgimgresloution[1];
             }
             $bgext = pathinfo($bgimg->getClientFilename(), PATHINFO_EXTENSION);
             if (!in_array($bgext, $allowed)) {
                 throw new WizardSetupException('Please upload a valid file', 201);
             }

            /*if(($staticbgwidth < $bgimgwidth) || ($staticbgheight < $bgimgheight ) || (($staticbgwidth < $bgimgwidth)&&($staticbgheight < $bgimgheight))){
				throw new WizardSetupException('Background Image size must 1920*1080.', 201);
			}*/
           
             $userId = $JWTdata->decoded->id;
             $userName = $JWTdata->decoded->userName;
             //print_r($bgimg);die();
			//$createJson= $this->WizardSetupRepository->createJsonFile($JWTdata,$logo,$bgimg,$menuicon,$userId,$userName);
            $createJson= $this->WizardSetupRepository->createJsonFile($JWTdata,$logo,$bgimg,$menuicon,$userId,$userName);
             $objLogger->info("======= END WizardSetup Action (Add Features to hotel) ================");
             return $this->jsonResponse($response, 'Success', $createJson, 200);
 
         }catch (WizardSetupException $ex) {
             $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
             $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
             //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
             $objLogger->info("======= END WizardSetup Action (Add Features to hotel) ================");
             if(!empty($ex->getMessage())){
                 throw new WizardSetupException($ex->getMessage(), $ex->getCode());
             }
             else {
                 throw new WizardSetupException(' Token invalid or Expired', 401);
             }
         }
    }

    public function GetTemplateDetails(Request $request, Response $response, array $args) : Response
     {
         $objLogger = $this->loggerFactory->getFileObject('WizardSetupAction', 'WizardSetupAction');
         $objLogger->info("======= Start WizardSetup Action (Get Template Details for hotel) ================");  
         try {
             $method = $request->getMethod();
             $objLogger->info("method : ".$method);
             if(strtoupper($method) != 'POST'){
               throw new WizardSetupException('Invalid Method', 500);
             }
 
          
 
             $jsndata = $this->getParsedBodyData($request);
             $objLogger->info("Input Data : ".json_encode($jsndata));
 
             $JWTdata = $this->getJsonFromParsedBodyData($request);
             if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
             {
                 throw new WizardSetupException('JWT Token invalid or Expired.', 401);
             }
             
             $userId = $JWTdata->decoded->id;
             $userName = $JWTdata->decoded->userName;
             $hotelid = isset($JWTdata->hotelid)?$JWTdata->hotelid:'0';
             $tempid = isset($JWTdata->tempid)?$JWTdata->tempid:'0';
          
             $createJson= $this->WizardSetupRepository->gettemplateDetails($hotelid,$tempid,$userId,$userName);
             $objLogger->info("======= END WizardSetup Action (Get Template Details for hotel) ================");
             return $this->jsonResponse($response, 'Success', $createJson, 200);
 
         }catch (WizardSetupException $ex) {
             $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
             $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
             //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
             $objLogger->info("======= END WizardSetup Action (Get Template Details for hotel) ================");
             if(!empty($ex->getMessage())){
                 throw new WizardSetupException($ex->getMessage(), $ex->getCode());
             }
             else {
                 throw new WizardSetupException(' Token invalid or Expired', 401);
             }
         }
    }

    /**
     * @throws WizardSetupException
     */

     public function updatefeedtype(Request $request, Response $response, array $args) : Response
     {
         $objLogger = $this->loggerFactory->getFileObject('WizardSetupAction', 'WizardSetupAction');
         $objLogger->info("======= Start WizardSetup Action (Update feed) ================");  
         try {
             $method = $request->getMethod();
             $objLogger->info("method : ".$method);
             if(strtoupper($method) != 'PUT'){
               throw new WizardSetupException('Invalid Method', 500);
             }
 
             $contentType = $request->getHeaderLine('content-type');
             $objLogger->info("contentType : ".$contentType);
             if(strtoupper($contentType) != 'APPLICATION/JSON'){
                 throw new WizardSetupException('Invalid ContentType', 500);
             }
             
 
             $jsndata = $this->getParsedBodyData($request);
             $objLogger->info("Input Data : ".json_encode($jsndata));
 
             $JWTdata = $this->getJsonFromParsedBodyData($request);
             if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
             {
                 throw new WizardSetupException('JWT Token invalid or Expired.', 401);
             }
 
             $userId = $JWTdata->decoded->id;
             $userName = $JWTdata->decoded->userName;
             $updatefeed = $this->WizardSetupRepository->updatefeed($JWTdata,$userId,$userName);
             $objLogger->info("======= END WizardSetup Action (Update feed) ================");
             return $this->jsonResponse($response, 'Success', $updatefeed->msg, 200);
 
         }catch (WizardSetupException $ex) {
             $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
             $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
             //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
             $objLogger->info("======= END WizardSetup Action (Update feed) ================");
             if(!empty($ex->getMessage())){
                 throw new WizardSetupException($ex->getMessage(), $ex->getCode());
             }
             else {
                 throw new WizardSetupException(' Token invalid or Expired', 401);
             }
         }
     }

     /**
     * @throws WizardSetupException
     */

     public function bulkUpload(Request $request, Response $response, array $args) : Response
     {
         $objLogger = $this->loggerFactory->getFileObject('WizardSetupAction', 'WizardSetupAction');
         $objLogger->info("======= Start WizardSetup Action (Bulk Upload For hotel) ================");  
         try {
             $method = $request->getMethod();
             $objLogger->info("method : ".$method);
             if(strtoupper($method) != 'POST'){
               throw new WizardSetupException('Invalid Method', 500);
             }
 
          
 
             $jsndata = $this->getParsedBodyData($request);
             $objLogger->info("Input Data : ".json_encode($jsndata));
 
             $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new WizardSetupException('JWT Token invalid or Expired.', 401);
            }
            $file = $request->getUploadedFiles();
            $bulkuploadfile = isset($file['excelfile']) ? $file['excelfile'] :'';
            
            
            if(empty($bulkuploadfile)){
                throw new WizardSetupException('Please upload.', 201);
              }
            
             $userId = $JWTdata->decoded->id;
             $userName = $JWTdata->decoded->userName;
             //print_r($bgimg);die();
			//$createJson= $this->WizardSetupRepository->createJsonFile($JWTdata,$logo,$bgimg,$menuicon,$userId,$userName);
            $createJson= $this->WizardSetupRepository->bulkUploadrepository($JWTdata,$bulkuploadfile,$userId,$userName);
             $objLogger->info("======= END WizardSetup Action (Bulk Upload For hotel) ================");
             return $this->jsonResponse($response, 'Success', $createJson, 200);
 
         }catch (WizardSetupException $ex) {
             $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
             $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
             //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
             $objLogger->info("======= END WizardSetup Action (Bulk Upload For hotel) ================");
             if(!empty($ex->getMessage())){
                 throw new WizardSetupException($ex->getMessage(), $ex->getCode());
             }
             else {
                 throw new WizardSetupException(' Token invalid or Expired', 401);
             }
         }
    }
}