<?php

namespace App\Application\Actions\AdmnMenu;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\AdmnMenu\AdmnMenuException;
use App\Domain\Repository\AdmnMenu\AdmnMenuRepository;

final class AdmnMenuAction extends Action
{
     
    protected AdmnMenuRepository $admnMenuRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;

    public function __construct(LoggerFactory $loggerFactory, AdmnMenuRepository $admnMenuRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->admnMenuRepository = $admnMenuRepository;
        $this->dBConFactory = $dBConFactory;
		    
    }

    /**
     * @throws AdmnMenuException
     */
    public function assginMenu(Request $request, Response $response, array $args): Response
    {
      $objLogger = $this->loggerFactory->getFileObject('AdmnMenuAction', 'AdmnMenuAction');
      $objLogger->info("======= Start AdmnMenu Action (CREATE) ================");  
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
             throw new AdmnGrpsException('Invalid Parameter Missing.', 201);
          }
          $menuid = $JWTdata->menuid;
          $readStatus = $this->admnGrpsRepository->getMenuRightStatus($userid, $menuid);
          $objLogger->info("readStatus : ".$readStatus);
          if(empty($readStatus)){
            throw new AdmnGrpsException('Invalid Access.', 201);
          }
          */

          //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
          $hotelid = 0;
          $brandid = 0;        
          $insStatus = $this->admnMenuRepository->assginMenu($JWTdata, $userid);
          $objLogger->info("Insert Status : ".$insStatus);
          $objLogger->info("======= END AdmnMenu Action (CREATE) ================");
          if($insStatus == 'SUCCESS'){
            return $this->jsonResponse($response, 'Added Successfully', '', 200);
          }
          else {
            return $this->jsonResponse($response, 'Not Added.', '', 200);
          }
        } catch (AdmnGrpsException $ex) {
            
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END AdmnMenu Action (CREATE) ================");
            if(!empty($ex->getMessage())){
                throw new AdmnGrpsException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AdmnGrpsException(' Token invalid or Expired', 401);
            }
        }  
    }

}
