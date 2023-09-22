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
    
    public function getAlllatestHotels(Request $request, Response $response, array $args) : Response
    {
        try {

            $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
            $objLogger->info("======= Start Common Action (getAlllatestHotels) ================");  

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

            $interfacetypelst = $this->commonRepository->getAlllatestHotels($auditBy);
            $objLogger->info("======= END Common Action (getAlllatestHotels) ================");
            return $this->jsonResponse($response, 'Success', $interfacetypelst, 200);
        
        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAlllatestHotels) ================");
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
     public function getAllIneterfaceType(Request $request, Response $response, array $args) : Response
    {
        try {

            $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
            $objLogger->info("======= Start Common Action (getAllIneterfaceType) ================");  

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

            $interfacetypelst = $this->commonRepository->getAllIneterfaceType($auditBy);
            $objLogger->info("======= END Common Action (getAllIneterfaceType) ================");
            return $this->jsonResponse($response, 'Success', $interfacetypelst, 200);

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllIneterfaceType) ================");
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
    public function getAllAvaliableHotelByGroup(Request $request, Response $response, array $args) : Response
    {
        try {

            $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
            $objLogger->info("======= Start Common Action (getAllAvaliableHotelByGroup) ================");  

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
			
			if(!empty($args) && array_key_exists('id', $args) && $args['id'] != NULL && $args['id'] != "" ){
				$objLogger->info("Group Id : ".$args['id']);
			}
			else {
				throw new CommonException('Group Id Empty.', 201);
			}  
		  
			if(!is_numeric($args['id'])){
				throw new CommonException('Invalid Parameter.', 201);
			}

            $groupid = $args['id'];

            $devicelst = $this->commonRepository->getAllAvaliableHotelByGroup($auditBy, $groupid);
            $objLogger->info("======= END Common Action (getAllAvaliableHotelByGroup) ================");
            return $this->jsonResponse($response, 'Success', $devicelst, 200);

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllAvaliableHotelByGroup) ================");
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
    public function getAllAllowedHotelByGroup(Request $request, Response $response, array $args) : Response
    {
        try {

            $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
            $objLogger->info("======= Start Common Action (getAllAllowedHotelByGroup) ================");  

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
			
			if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
				$objLogger->info("Group Id : ".$args['id']);
			}
			else {
				throw new CommonException('Group Id Empty.', 201);
			}  
		  
			if(!is_numeric($args['id'])){
				throw new CommonException('Invalid Parameter.', 201);
			}

            $groupid = $args['id'];

            $devicelst = $this->commonRepository->getAllallowedHotelsByGroup($auditBy, $groupid);
            $objLogger->info("======= END Common Action (getAllAllowedHotelByGroup) ================");
            return $this->jsonResponse($response, 'Success', $devicelst, 200);

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllAllowedHotelByGroup) ================");
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

    public function getMenuRightsByHotelMenu(Request $request, Response $response, array $args) : Response
    {
        try {

            $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
            $objLogger->info("======= Start Common Action (getMenuRightsByHotelMenu) ================");  

            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'POST'){
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

            if(!isset($JWTdata->menuId) || empty($JWTdata->menuId))
            {
                throw new CommonException('Menu Id Empty.', 201);
            }

            $menuid = $JWTdata->menuId;

            if(!isset($JWTdata->hotel_id) || empty($JWTdata->hotel_id))
            {
                throw new CommonException('Hotel Id Empty.', 201);
            }

            $hotelid = $JWTdata->hotel_id;

            $devicelst = $this->commonRepository->getMenuRightsByHotelMenu($auditBy, $menuid, $hotelid);
            $objLogger->info("======= END Common Action (getMenuRightsByHotelMenu) ================");
            return $this->jsonResponse($response, 'Success', $devicelst, 200);


        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getMenuRightsByHotelMenu) ================");
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
    public function getAllallowedHotels(Request $request, Response $response, array $args) : Response
    {
        try {

            $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
            $objLogger->info("======= Start Common Action (getAllallowedHotels) ================");  

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
			
			if(!empty($args) && array_key_exists('id', $args)){
				$objLogger->info("Brand Id : ".$args['id']);
			}
			else {
				throw new CommonException('Brand Id Empty.', 201);
			}  
		  
			if(!is_numeric($args['id'])){
				throw new CommonException('Invalid Parameter.', 201);
			}

            $brandid = $args['id'];

            $devicelst = $this->commonRepository->getAllallowedHotels($auditBy, $brandid);
            $objLogger->info("======= END Common Action (getAllallowedHotels) ================");
            return $this->jsonResponse($response, 'Success', $devicelst, 200);

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllallowedHotels) ================");
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
    public function getAllallowedBrands(Request $request, Response $response, array $args) : Response
    {
        try {

            $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
            $objLogger->info("======= Start Common Action (getAllallowedBrands) ================");  

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

            $devicelst = $this->commonRepository->getAllallowedBrands($auditBy);
            $objLogger->info("======= END Common Action (getAllInternetLists) ================");
            return $this->jsonResponse($response, 'Success', $devicelst, 200);

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllallowedBrands) ================");
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
    public function getAllSubmenus(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllInternetLists) ================");  
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
            //$hotelid = $JWTdata->hotel_id;
           
            $hotelid = 0;
            $brandid = 0;
            $menuid = $JWTdata->menu_id;

            $devicelst = $this->commonRepository->getAllSubMenus($auditBy, $menuid, $hotelid, $brandid);
            $objLogger->info("======= END Common Action (getAllInternetLists) ================");
            return $this->jsonResponse($response, 'Success', $devicelst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllInternetLists) ================");
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
    public function getAllInternetLists(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllInternetLists) ================");  
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
            //$hotelid = $JWTdata->hotel_id;
            if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
				$objLogger->info("Hotel Id : ".$args['id']);
			}
			else {
				throw new CommonException('Hotel Id Empty.', 201);
			}  
		  
			if(!is_numeric($args['id'])){
				throw new CommonException('Invalid Parameter.', 201);
			}

            $hotelid = $args['id'];

            $brandid = 0;
            $devicelst = $this->commonRepository->getAllInternetLists($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllInternetLists) ================");
            return $this->jsonResponse($response, 'Success', $devicelst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllInternetLists) ================");
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException(' Token invalid or Expired', 401);
            }
        }
    }

    public function getAllDeviceLocations(Request $request, Response $response, array $args) : Response {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllDeviceLocations) ================");  
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

            $brandlst = $this->commonRepository->getAllDeviceLocations($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllDeviceLocations) ================");
            return $this->jsonResponse($response, 'Success', $brandlst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllDeviceLocations) ================");
            if(!empty($ex->getMessage())){
                throw new CommonException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new CommonException(' Token invalid or Expired', 401);
            }
        }
    }
    public function getAllDeviceTypes(Request $request, Response $response, array $args) : Response {
        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllDeviceTypes) ================");  
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

            $brandlst = $this->commonRepository->getAllDeviceTypes($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllDeviceTypes) ================");
            return $this->jsonResponse($response, 'Success', $brandlst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllDeviceTypes) ================");
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
    public function getAllTimeZone(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllTimeZone) ================");  
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
            $objLogger->info("======= END Common Action (getAllTimeZone) ================");
            return $this->jsonResponse($response, 'Success', $brandlst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllTimeZone) ================");
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
    public function getAllIcmpPolicys(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllIcmpPolicys) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'POST'){
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
            if(!isset($JWTdata->hotel_id) || empty($JWTdata->hotel_id)){
                throw new CommonException('Hotel Id is Invalid or Missing.', 201);
            }
            $hotelid = $JWTdata->hotel_id;
            $brandid = 0;

            $devicelst = $this->commonRepository->getAllIcmpPolicys($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllIcmpPolicys) ================");
            return $this->jsonResponse($response, 'Success', $devicelst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllIcmpPolicys) ================");
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
    public function getAllDeviceStatus(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllDeviceStatus) ================");  
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

            $devicelst = $this->commonRepository->getAllDeviceStatus($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllDeviceStatus) ================");
            return $this->jsonResponse($response, 'Success', $devicelst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllDeviceStatus) ================");
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
    public function getAllDevices(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllDevices) ================");  
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
           
            

            if(!empty($args) && array_key_exists('id', $args) && !empty($args['id'])){
                $objLogger->info("Hotel id : ".$args['id']);
            }
            else {
                throw new CommonException('Hotel Id Empty.', 201);
            }

            $hotelid = $args['id'];
            $brandid = 0;
            
            $devicelst = $this->commonRepository->getAllDevices($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllDevices) ================");
            return $this->jsonResponse($response, 'Success', $devicelst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllDevices) ================");
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
    public function getAllGroup(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllGroup) ================");  
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

            $brandlst = $this->commonRepository->getAllGroup($auditBy, $brandid, $hotelid);
            $objLogger->info("======= END Common Action (getAllGroup) ================");
            return $this->jsonResponse($response, 'Success', $brandlst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllGroup) ================");
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
    public function getAllAvailableMenus(Request $request, Response $response, array $args) : Response
    {

        $objLogger = $this->loggerFactory->getFileObject('CommonAction', 'CommonAction');
        $objLogger->info("======= Start Common Action (getAllAvailableMenus) ================");  
        try {
            
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'POST'){
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
            

            $menulst = $this->commonRepository->getAllAvailableMenus($JWTdata, $auditBy);
            $objLogger->info("======= END Common Action (getAllAvailableMenus) ================");
            return $this->jsonResponse($response, 'Success', $menulst, 200);   

        } catch (CommonException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END Common Action (getAllAvailableMenus) ================");
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
            if(strtoupper($method) != 'POST'){
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
            

            $menulst = $this->commonRepository->getAllAssignMenus($JWTdata, $auditBy);
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
                throw new CommonException('Invalid Parameter Missing.', 201);
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
                throw new CommonException('Invalid Parameter Missing.', 201);
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

    /**
     * @throws CommonException
     */
    public function getAllOttSideMenu(Request $request, Response $response, array $args) : Response
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
            $sidebarlist = $this->commonRepository->getAllottSideBarMenu($auditBy, $brandid, $hotelid);           
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
