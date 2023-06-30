<?php

namespace App\Model;
use App\Exception\DB\DBException;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;

class DB {

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }

    public function insOrUpdteOrDetQuery($sqlQuery){
        $objLogger = $this->loggerFactory->getFileObject('DBError', 'DBModel');
        try{
            $objtCon = $this->dBConFactory->getConnection();
            $result = mysqli_query($objtCon, $sqlQuery);
            $errorMsg = mysqli_error($objtCon);
            $this->dBConFactory->close($objtCon);
            $objLogger->info("Mysql Error Message : ".$errorMsg);
            return $result;
        }
        catch(DBException $ex){
            $objLogger->info("======= START DBError ================");
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END DBError ================");
            if(!empty($ex->getMessage())){
                //throw new DBException($ex->getMessage(), $ex->getCode());
                throw new DBException('Database Error', 401);
            }
            else {
                throw new DBException('Database Error', 401);
            }
       }
    }


    public function getMultiDatasByArray($sqlQuery){
        $multiData = array();
        $objLogger = $this->loggerFactory->getFileObject('DBError', 'DBModel');      
        try{
            $objtCon = $this->dBConFactory->getConnection();
            $result = mysqli_query($objtCon, $sqlQuery);
            $errorMsg = mysqli_error($objtCon);
            $this->dBConFactory->close($objtCon);
    
            if($result){
                $flg = 0;
                While($row = mysqli_fetch_assoc($result)){
                    $flg = 1;
                    $multiData[] = $row;
                }
                if($flg == 0){
                        $objLogger->info(" Result : No Records Found");
                        $multiData = array();
                }
            }
            else {
                if(!empty($errorMsg))
                    throw new DBException($errorMsg, 401);
                else 
                    throw new DBException("Empty Result Returned", 401);
            }
            
            return $multiData;
        }
       catch(DBException $ex){
            $objLogger->info("======= START DBError ================");
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END DBError ================");
            if(!empty($ex->getMessage())){
                throw new DBException($ex->getMessage(), $ex->getCode());
                
            }
            else {
                throw new DBException('Database Error', 401);
            }
       }
    }

    public function getMultiDatasByObjects($sqlQuery){
        $multiData = array();
        $objLogger = $this->loggerFactory->getFileObject('DBError', 'DBModel');      
        try{
            $objtCon = $this->dBConFactory->getConnection();
            $result = mysqli_query($objtCon, $sqlQuery);
            $errorMsg = mysqli_error($objtCon);
            $this->dBConFactory->close($objtCon);
    
            if($result){
                $flg = 0;
                While($row = mysqli_fetch_object($result)){
                    $flg = 1;
                    $multiData[] = $row;
                }
                if($flg == 0){

                    if(!empty($errorMsg))
                        throw new DBException($errorMsg, 401);
                    else
                        throw new DBException("No Records Found", 201);
                }
            }
            else {
                if(!empty($errorMsg))
                    throw new DBException($errorMsg, 401);
                else 
                    throw new DBException("Empty Result Returned", 401);
            }
            
            return $multiData;
        }
       catch(DBException $ex){
            $objLogger->info("======= START DBError ================");
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END DBError ================");
            if(!empty($ex->getMessage())){
                throw new DBException($ex->getMessage(), $ex->getCode());
                
            }
            else {
                throw new DBException('Database Error', 401);
            }
       }
    }
    
    public function getSingleDatasByObjects($sqlQuery){
        $singleData = NULL;
        $objLogger = $this->loggerFactory->getFileObject('DBError', 'DBModel');
        try{
            $objtCon = $this->dBConFactory->getConnection();
            $result = mysqli_query($objtCon, $sqlQuery);
            $errorMsg = mysqli_error($objtCon);
            $this->dBConFactory->close($objtCon);
    
            if($result){
                $flg = 0;
                While($row = mysqli_fetch_object($result)){
                    $flg = 1;
                    $singleData = $row;
                }
                if($flg == 0){

                    if(!empty($errorMsg))
                        throw new DBException($errorMsg, 401);
                    else 
                        throw new DBException("No Records Found", 201);
                }
            }
            else {
                if(!empty($errorMsg))
                    throw new DBException($errorMsg, 401);
                else 
                    throw new DBException("Empty Result Returned", 401);
            }
            return $singleData;
        }
       catch(DBException $ex){

            $objLogger->info("======= START DBError ================");
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END DBError ================");
            if(!empty($ex->getMessage())){
                throw new DBException($ex->getMessage(), $ex->getCode());
                
            }
            else {
                throw new DBException('Database Error', 401);
            }
       }
    }

    public function getMultiDatasByObjectsList($sqlQuery){
        //$multiData = array();
        $objLogger = $this->loggerFactory->getFileObject('DBError', 'DBModel');      
        try{
            $objtCon = $this->dBConFactory->getConnection();
            $result = mysqli_query($objtCon, $sqlQuery);
            $errorMsg = mysqli_error($objtCon);
            $this->dBConFactory->close($objtCon);
    
            if($result){
                $row = '';
                $flg = 0;
                if($row = mysqli_fetch_object($result)){
                    $flg = 1;
                   
                }
                if($flg == 0){

                    if(!empty($errorMsg)):
                        throw new DBException($errorMsg, 401);
                    else:
                        throw new DBException("No Records Found", 201);
                    endif;
                }
            }
            else {
                if(!empty($errorMsg))
                    throw new DBException($errorMsg, 401);
                else 
                    throw new DBException("Empty Result Returned", 401);
            }
            
            return $row;
        }
       catch(DBException $ex){
            $objLogger->info("======= START DBError ================");
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END DBError ================");
            if(!empty($ex->getMessage())){
                throw new DBException($ex->getMessage(), $ex->getCode());
                
            }
            else {
                throw new DBException('Database Error', 401);
            }
       }
    }

    public function getMultiDatasByObjectsNullReturns($sqlQuery){
        $multiData = array();
        $objLogger = $this->loggerFactory->getFileObject('DBError', 'DBModel');      
        try{
            $objtCon = $this->dBConFactory->getConnection();
            $result = mysqli_query($objtCon, $sqlQuery);
            $errorMsg = mysqli_error($objtCon);
            $this->dBConFactory->close($objtCon);
    
            if($result){
                $flg = 0;
                While($row = mysqli_fetch_object($result)){
                    $flg = 1;
                    $multiData[] = $row;
                }
                if($flg == 0){

                    if(!empty($errorMsg)):
                        throw new DBException($errorMsg, 401);
                    else:
                        //throw new DBException("No Records Found", 201);
                    endif;
                }
            }
            else {
                if(!empty($errorMsg))
                    throw new DBException($errorMsg, 401);
                else 
                    throw new DBException("Empty Result Returned", 401);
            }
            
            return $multiData;
        }
       catch(DBException $ex){
            $objLogger->info("======= START DBError ================");
            $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            $objLogger->info("======= END DBError ================");
            if(!empty($ex->getMessage())){
                throw new DBException($ex->getMessage(), $ex->getCode());
                
            }
            else {
                throw new DBException('Database Error', 401);
            }
       }
    }

}
?>