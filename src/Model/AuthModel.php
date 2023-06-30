<?php
namespace App\Model;

use App\Factory\DBConFactory;


use App\Model\DB;
use App\Exception\Auth\AuthException;

class AuthModel extends BaseModel

{
    protected DBConFactory $dBConFactory;

    public function __construct(DBConFactory $dBConFactory)
    {

        $this->dBConFactory = $dBConFactory;
        
    }

    public function validateEmailandUserId($email, $userId){
        //$objLogger = $this->loggerFactory->getFileObject('AuthMiddleware', 'AuthModel');
        try{

            $sqlQuery = "SELECT COUNT(*) AS CNT FROM adminusers WHERE adminStatus = 1 AND id = '".$userId."' AND  email = '".$email."' ";
           
            $objtCon = $this->dBConFactory->getConnection();
            $result = mysqli_query($objtCon, $sqlQuery);
            $errorMsg = mysqli_error($objtCon);
            $this->dBConFactory->close($objtCon);

            if($result){
                $flg = 0;
                While($row = mysqli_fetch_object($result)){
                    $flg = 1;
                    return $row->CNT;
                }

                if($flg == 0){

                    if(!empty($errorMsg))                        
                        throw new DBException("Invalid Access", 403);
                }
            }
            else {
                if(!empty($errorMsg))                  
                    throw new AuthException("Invalid Access", 403);
            }


        }
        catch(AuthException $ex){
            if(!empty($ex->getMessage())){
                throw new AuthException($ex->getMessage(), $ex->getCode());
            }
            else {
                throw new AuthException('Invalid Access', 403);
            }
        }
    }
}
?>