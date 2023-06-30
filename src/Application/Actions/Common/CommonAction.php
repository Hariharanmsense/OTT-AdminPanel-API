<?php
declare(strict_types=1);
namespace App\Application\Actions\Common;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Common\CommonException;
use App\Domain\Repository\Common\CommonRepository;
use App\Application\Auth\JwtToken;

final class CommonAction extends Action
{
    protected CommonRepository $commonRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;

    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, CommonRepository $commonRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->commonRepository = $commonRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }


    /**
     * @throws CommonException
     */
    public function getAllTimeZone(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllBrand) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new CommonException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new CommonException('Invalid ContentType', 500);
            }

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new CommonException('JWT Token invalid or Expired.', 401);
            }

            $auditBy = $JWTdata->decoded->id;
             //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
            $hotelid = 0;
            $brandid = 0;

            $brandlst = $this->commonRepository->getAllTimeZone($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllBrand) ================");
            return $this->jsonResponse($response, 'Success', $brandlst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllBrand) ================");
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException(' Token invalid or Expired', 401);
            }
        }
    }

     /**
     * @throws CommonException
     */
    public function getAllBrand(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllBrand) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new CommonException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new CommonException('Invalid ContentType', 500);
            }

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new CommonException('JWT Token invalid or Expired.', 401);
            }

            $auditBy = $JWTdata->decoded->id;
             //$hotelid		 = $JWTdata->decoded->hotel_id;
          //$brandid		 = $JWTdata->decoded->brand_id;
            $hotelid = 0;
            $brandid = 0;

            $brandlst = $this->commonRepository->getAllBrand($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllBrand) ================");
            return $this->jsonResponse($response, 'Success', $brandlst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllBrand) ================");
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException(' Token invalid or Expired', 401);
            }
        }
    }

    

    /**
     * @throws CommonException
     */
    public function getAllReadWriteMenus(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllReadWriteMenus) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new CommonException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new CommonException('Invalid ContentType', 500);
            }

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new CommonException('JWT Token invalid or Expired.', 401);
            }

            $auditBy = $JWTdata->decoded->id;
            //$hotelid		 = $JWTdata->decoded->hotel_id;
            //$brandid		 = $JWTdata->decoded->brand_id;
            $hotelid = 0;
            $brandid = 0;

            $menulst = $this->commonRepository->getAllReadWriteMenus($jsndata, $auditBy);
            $objLogger->info("======= END Common Action (getAllReadWriteMenus) ================");
            return $this->jsonResponse($response, 'Success', $menulst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllReadWriteMenus) ================");
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException(' Token invalid or Expired', 401);
            }
        }
    }

    /**
     * @throws CommonException
     */
    public function getAllAssignMenus(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllAssignMenus) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new CommonException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new CommonException('Invalid ContentType', 500);
            }

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new CommonException('JWT Token invalid or Expired.', 401);
            }

            $auditBy = $JWTdata->decoded->id;
            //$hotelid		 = $JWTdata->decoded->hotel_id;
            //$brandid		 = $JWTdata->decoded->brand_id;
            $hotelid = 0;
            $brandid = 0;

            $menulst = $this->commonRepository->getAllAssignMenus($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllAssignMenus) ================");
            return $this->jsonResponse($response, 'Success', $menulst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllAssignMenus) ================");
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException(' Token invalid or Expired', 401);
            }
        }
    }

    /**
     * @throws CommonException
     */
    public function getAllHotel(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllHotel) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new CommonException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new CommonException('Invalid ContentType', 500);
            }

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new CommonException('JWT Token invalid or Expired.', 401);
            }

            $auditBy = $JWTdata->decoded->id;
            //$hotelid		 = $JWTdata->decoded->hotel_id;
            //$brandid		 = $JWTdata->decoded->brand_id;
            $hotelid = 0;
            $brandid = 0;

            $hotellst = $this->commonRepository->getAllHotel($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllHotel) ================");
            return $this->jsonResponse($response, 'Success', $hotellst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllHotel) ================");
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException(' Token invalid or Expired', 401);
            }
        }
    }
	
	/**
     * @throws CommonException
     */
    public function getMenuRightAccess(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getMenuRightAccess) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new CommonException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new CommonException('Invalid ContentType', 500);
            }

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new CommonException('JWT Token invalid or Expired.', 401);
            }

            $auditBy = $JWTdata->decoded->id;
            //$hotelid		 = $JWTdata->decoded->hotel_id;
            //$brandid		 = $JWTdata->decoded->brand_id;
            $hotelid = 0;
            $brandid = 0;
            /*
            if(!isset($JWTdata->menuId)){
                throw new CommonException('Invalid Parameter Missing.', 401);
             }
             $menuId = $JWTdata->menuId;
             */

            if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
                $objLogger->info("Menu id : ".$args['id']);
            }
            else {
                throw new CommonException('Menu Id Empty.', 201);
            }
            $sidebarlist = $this->commonRepository->getMenuRightAccess($args['id'], $auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getMenuRightAccess) ================");
            return $this->jsonResponse($response, 'Success', $sidebarlist, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getMenuRightAccess) ================");
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException(' Token invalid or Expired', 401);
            }
        }
    }

    /**
     * @throws CommonException
     */
    public function getAllSideBarMenu(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllSideBarMenu) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new CommonException('Invalid Method', 500);
            }

            $contentType = $request->getHeaderLine('content-type');
            $objLogger->info("contentType : ".$contentType);
            if(strtoupper($contentType) != 'APPLICATION/JSON'){
                throw new CommonException('Invalid ContentType', 500);
            }

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new CommonException('JWT Token invalid or Expired.', 401);
            }

            $auditBy = $JWTdata->decoded->id;
            //$hotelid		 = $JWTdata->decoded->hotel_id;
            //$brandid		 = $JWTdata->decoded->brand_id;
            $hotelid = 0;
            $brandid = 0;
            /*
            if(!isset($JWTdata->menuId)){
                throw new CommonException('Invalid Parameter Missing.', 401);
             }
             $menuId = $JWTdata->menuId;
             */
            $sidebarlist = $this->commonRepository->getAllSideBarMenu($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllSideBarMenu) ================");
            return $this->jsonResponse($response, 'Success', $sidebarlist, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllSideBarMenu) ================");
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException(' Token invalid or Expired', 401);
            }
        }
    }

}
