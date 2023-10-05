<?php
declare(strict_types=1);
namespace App\Application\Actions\GuestMessage;


use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


use App\Application\Actions\Action;
use App\Exception\GuestMessage\GuestMessageException;
use App\Domain\Repository\GuestMessage\GuestMessageRepository;
use App\Application\Auth\JwtToken;



final class GuestMessageAction extends Action
{
    protected GuestMessageRepository $repository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;

    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, GuestMessageRepository $repository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->repository = $repository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }

    public function delete(Request $request, Response $response, array $args) : Response
    {
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageAction', 'delete');
        $objLogger->info("======= Start Guest Message Action (delete) ================");  
        try {
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'DELETE'){
              throw new GuestMessageException('Invalid Method', 500);
            }

         

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new GuestMessageException('JWT Token invalid or Expired.', 401);

            }

            $msg_id = isset($args['id'])? $args['id']:'';
            if(empty($msg_id)){
                throw new GuestMessageException("Message Id Required",201);
            }
            $userId = isset($JWTdata->decoded->id)? $JWTdata->decoded->id:'';
            $userName = isset($JWTdata->decoded->userName)? $JWTdata->decoded->userName:'';
            $guestMsginfo = $this->repository->guestmsgdelete($JWTdata,$msg_id,$userId,$userName);
            $objLogger->info("======= END Guest Message Action (delete) ================");
           return $this->jsonResponse($response, 'Success', $guestMsginfo, 200);

        }catch (GuestMessageException $ex) {
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->info("======= END Guest Message Action (delete) ================");
            if(!empty($ex->getMessage())){
                throw new GuestMessageException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new GuestMessageException(' Token invalid or Expired', 401);
            }
        }
   }
    public function getoneRoom(Request $request, Response $response, array $args) : Response
    {
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageAction', 'getoneRoom');
        $objLogger->info("======= Start Guest Message Action (getoneRoom) ================");  
        try {
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'GET'){
              throw new GuestMessageException('Invalid Method', 500);
            }

         

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new GuestMessageException('JWT Token invalid or Expired.', 401);

            }

            $msg_id = isset($args['id'])? $args['id']:'';
            if(empty($msg_id)){
                throw new GuestMessageException("Message Id Required",201);
            }
            $userId = isset($JWTdata->decoded->id)? $JWTdata->decoded->id:'';
            $userName = isset($JWTdata->decoded->userName)? $JWTdata->decoded->userName:'';
            $guestMsginfo = $this->repository->getoneRoomRepository($JWTdata,$msg_id,$userId,$userName);
            $objLogger->info("======= END Guest Message Action (getoneRoom) ================");
           return $this->jsonResponse($response, 'Success', $guestMsginfo, 200);

        }catch (GuestMessageException $ex) {
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->info("======= END Guest Message Action (getoneRoom) ================");
            if(!empty($ex->getMessage())){
                throw new GuestMessageException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new GuestMessageException(' Token invalid or Expired', 401);
            }
        }
   }

    /**
     * Summary of sendmessage
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @throws \App\Exception\GuestMessage\GuestMessageException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendmessage(Request $request, Response $response, array $args) : Response
    {
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageAction', 'sendmessage');
        $objLogger->info("======= Start Guest Message Action (sendmessage) ================");  
        try {
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'POST'){
              throw new GuestMessageException('Invalid Method', 500);
            }

         

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new GuestMessageException('JWT Token invalid or Expired.', 401);

            }

            $file = $request->getUploadedFiles();
            $msg_img = isset($file['msg_img']) ? $file['msg_img'] :'';
          
            $userId = isset($JWTdata->decoded->id)? $JWTdata->decoded->id:'';
            $userName = isset($JWTdata->decoded->userName)? $JWTdata->decoded->userName:'';
            $guestMsginfo = $this->repository->sendmessage($JWTdata,$msg_img,$userId,$userName);
            $objLogger->info("======= END Guest Message Action (sendmessage) ================");
           return $this->jsonResponse($response, 'Success', $guestMsginfo, 200);

        }catch (GuestMessageException $ex) {
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->info("======= END Guest Message Action (sendmessage) ================");
            if(!empty($ex->getMessage())){
                throw new GuestMessageException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new GuestMessageException(' Token invalid or Expired', 401);
            }
        }
   }


    /**
     * Summary of getguestmsgList
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @throws \App\Exception\GuestMessage\GuestMessageException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getguestmsgList(Request $request, Response $response, array $args) : Response
    {
        $objLogger = $this->loggerFactory->getFileObject('GuestMessageAction', 'getguestmsgList');
        $objLogger->info("======= Start Guest Message Action (getguestmsgList) ================");  
        try {
            $method = $request->getMethod();
            $objLogger->info("method : ".$method);
            if(strtoupper($method) != 'POST'){
              throw new GuestMessageException('Invalid Method', 500);
            }

         

            $jsndata = $this->getParsedBodyData($request);
            $objLogger->info("Input Data : ".json_encode($jsndata));

            $JWTdata = $this->getJsonFromParsedBodyData($request);
            if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
            {
                throw new GuestMessageException('JWT Token invalid or Expired.', 401);

            }

            /*$file = $request->getUploadedFiles();
            $logo = isset($file['msg_img']) ? $file['msg_img'] :'';*/
          
            $userId = isset($JWTdata->decoded->id)? $JWTdata->decoded->id:'';
            $userName = isset($JWTdata->decoded->userName)? $JWTdata->decoded->userName:'';
            $guestMsginfo = $this->repository->getguestmsglist($JWTdata,$userId,$userName);
            $objLogger->info("======= END Guest Message Action (getguestmsgList) ================");
           return $this->jsonResponse($response, 'Success', $guestMsginfo, 200);

        }catch (GuestMessageException $ex) {
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->info("======= END Guest Message Action (getguestmsgList) ================");
            if(!empty($ex->getMessage())){
                throw new GuestMessageException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new GuestMessageException(' Token invalid or Expired', 401);
            }
        }
   }


}