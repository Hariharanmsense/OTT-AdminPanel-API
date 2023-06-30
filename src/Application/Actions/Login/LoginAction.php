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


final class LoginAction extends Action
{
    protected LoginRepository $loginRepository;
    protected LoggerFactory $loggerFactory;
    protected DBConFactory $dBConFactory;
    protected JwtToken $jwtToken;

    public function __construct(JwtToken $jwtToken, LoggerFactory $loggerFactory, LoginRepository $loginRepository, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->loginRepository = $loginRepository;
        $this->dBConFactory = $dBConFactory;
        $this->jwtToken = $jwtToken;
    }

     /**
     * @throws LoginException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {  
          $objLogger = $this->loggerFactory->addFileHandler('LoginModel.log')->createInstance('LoginAction');
		  $method = $request->getMethod();
          if(strtoupper($method) != 'POST'){
            throw new LoginException('Invalid Method', 500);
          }

          $contentType = $request->getHeaderLine('Content-Type');
          if(strtoupper($contentType) != 'APPLICATION/JSON'){
            throw new LoginException('Invalid ContentType', 500);
          }

          $jsndata = $this->getParsedBodyData($request);
          $jsndata = $this->getParsedBodyData($request);
          //print_r($jsndata->userid);die();
          $objLogger->info("======= Start Login Action ================");
          $objLogger->info("Input Data : ".json_encode($jsndata));
          $usrData = $this->loginRepository->doAuth($jsndata);
          

          $objLogger->info("======= END Login Action ================");
          return $this->jsonResponse($response, 'Login successfully', $usrData, 200);
        } catch (LoginException $e) {
            throw new LoginException($e->getMessage(), 401);
        }
    }

}
?>