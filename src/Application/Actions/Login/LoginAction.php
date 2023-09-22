<?php
declare(strict_types=1);
namespace App\Application\Actions\Login;

use App\Factory\LoggerFactory; 
use App\Factory\DBConFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Action;
use App\Exception\Login\LoginException;
use App\Domain\Repository\Login\LoginRepository;
use App\Application\Auth\JwtToken;
use App\Application\Auth\Crypto;


final class LoginAction extends Action
{
    protected LoginRepository $loginRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;
    protected Crypto $crypto;

    public function __construct(Crypto $crypto, JwtToken $jwtToken, LoggerFactory $loggerFactory, LoginRepository $loginRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->loginRepository = $loginRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
        $this->crypto = $crypto;
    }

     /**
     * @throws LoginException
     */
    public function logOut(Request $request, Response $response, array $args): Response{
        try {    

          $objLogger = $this->loggerFactory->getFileObject('LoginAction', 'LoginAction');
          $objLogger->info("======= Start logOut Action ================");
          $method = $request->getMethod();
          if(strtoupper($method) != 'POST'){
            throw new LoginException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('Content-Type');
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new LoginException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("Input Data : ".json_encode($jsndata));

          $JWTdata = $this->getJsonFromParsedBodyData($request);
          if(!isset($JWTdata->decoded) || !isset($JWTdata->decoded->id))
          {
              throw new InternetException('JWT Token invalid or Expired.', 401);
          }
          $auditBy = $JWTdata->decoded->id;

          $logstatus = $this->loginRepository->logOut($JWTdata, $auditBy);
          if( $logstatus == 'SUCCESS'){
            return $this->jsonResponse($response, 'Logout time updated successfully','', 200);
          }
          else {
            return $this->jsonResponse($response, 'Logout time not updated','', 201);
          }

        } catch (LoginException $ex) {

          $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
          $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
          //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
          if(!empty($ex->getMessage())){
              throw new LoginException($ex->getMessage(), $ex->getCode());
          }
          else {
              throw new LoginException('Login User Id Invalid', 201);
          }
      }
    }
     /**
     * @throws LoginException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {           
          $objLogger = $this->loggerFactory->getFileObject('LoginAction', 'LoginAction');
          $method = $request->getMethod();
          if(strtoupper($method) != 'POST'){
            throw new LoginException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('Content-Type');
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new LoginException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $objLogger->info("======= Start Login Action ================");
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $usrData = $this->loginRepository->doAuth($jsndata);
          

          $objLogger->info("======= END Login Action ================");
          return $this->jsonResponse($response, 'Login successfully', $usrData, 200);
        } catch (LoginException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new LoginException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new LoginException('Login credentials invalid', 201);
            }
        }
    }

}
?>