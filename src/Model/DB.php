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

    public function getMultipleimageobjects($sqlQuery){
        $multiData = array();
        $objLogger = $this->loggerFactory->getFileObject('DBError', 'DBModel');      
        try{
            $objtCon = $this->dBConFactory->getConnection();
            $result = mysqli_query($objtCon, $sqlQuery);
            $errorMsg = mysqli_error($objtCon);
            $this->dBConFactory->close($objtCon);

            if(!empty($errorMsg)){
                $objLogger->info("======= START DBError ================");
                $objLogger->error("Error Message : ".$errorMsg);
                $objLogger->info("======= END DBError ================");
                throw new DBException('Database Error', 201);
            }
    
            if($result){
                $flg = 0;
                While($row = mysqli_fetch_object($result)){
                    $flg = 1;
                    

                    foreach ($row as $key => $value) {  
                        //print_r($row->id);die();
						if($row->imgpath !=""){
							$i=1;
							$j = 0;
							$img_arry = array();
							while ($i <= $row->imgcnt){
								//$chkval = 0;
								$curryear = date('Y',strtotime($row->createdOn));		
								$currDate = date("Y-m-d",strtotime($row->createdOn));		
								$similar_name = $curryear."_".$row->id."_".$j;	
								//echo gettype($similar_name);
								$txtmsgimgfldr = "../public/uploads/msgimg/". $curryear."/".$currDate;
                                $actualpath = "public/uploads/msgimg/". $curryear."/".$currDate;
								
								$path = $txtmsgimgfldr.'/'.$similar_name;
                                

								
								if(file_exists($txtmsgimgfldr)){
									$img = scandir($txtmsgimgfldr);
                                    
										for($s = 0 ;$s < count($img);$s++):
											if($img[$s]!="." && $img[$s]!=".." && $img[$s]!=""):
                                            //    echo $img[$s]."    ".$similar_name."\r\n";
												if (strpos($img[$s], $similar_name) !== false) {
													array_push($img_arry,$actualpath.'/'.$img[$s]);
												}
											endif;
										endfor;
								}
								$i++;
								$j++;
							}
                           // print_R($img_arry);die();
							if(!empty($img_arry)){
								$row->filepath =$img_arry;
							}
                            //else{
                            //     $row->filepath = $img_arry;
                            // }
							
							
						}
			        }  
                    $multiData[] = $row;
                }
                if($flg == 0){

                    if(!empty($errorMsg))
                        throw new DBException($errorMsg, 201);
                    else 
                        throw new DBException("No Records Found", 201);
                }
            }
            else {
                if(!empty($errorMsg))
                    throw new DBException($errorMsg, 201);
                else 
                    throw new DBException("Empty Result Returned", 201);
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
                throw new DBException('Database Error', 201);
            }
       }
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
                throw new DBException('Database Error', 201);
            }
            else {
                throw new DBException('Database Error', 201);
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
                    throw new DBException($errorMsg, 201);
                else 
                    throw new DBException("Empty Result Returned", 201);
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
                throw new DBException('Database Error', 201);
            }
       }
    }

    public function getMultiDatasByObjects($sqlQuery,$RecCond="NO"){
        $multiData = array();
        $objLogger = $this->loggerFactory->getFileObject('DBError', 'DBModel');      
        try{
            $objtCon = $this->dBConFactory->getConnection();
            $result = mysqli_query($objtCon, $sqlQuery);
            $errorMsg = mysqli_error($objtCon);
            $this->dBConFactory->close($objtCon);

            if(!empty($errorMsg)){
                $objLogger->info("======= START DBError ================");
                $objLogger->error("Error Message : ".$errorMsg);
                $objLogger->info("======= END DBError ================");
                throw new DBException('Database Error', 201);
            }
    
            if($result){
                $flg = 0;
                While($row = mysqli_fetch_object($result)){
                    $flg = 1;
                    $multiData[] = $row;
                }
                if($RecCond == 'YES'){
                    return $multiData;
                }
                else {
                    if($flg == 0){

                        if(!empty($errorMsg))
                            throw new DBException($errorMsg, 201);
                        else 
                            throw new DBException("No Records Found", 201);
                    }
                }
            }
            else {
                if(!empty($errorMsg))
                    throw new DBException($errorMsg, 201);
                else 
                    throw new DBException("Empty Result Returned", 201);
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
                throw new DBException('Database Error', 201);
            }
       }
    }


    public function getSingleDatasByObjects($sqlQuery, $RecCond="NO"){
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

                if($RecCond == 'YES'){
                    return $singleData;
                }
                else {
                    if($flg == 0){

                        if(!empty($errorMsg))
                            throw new DBException($errorMsg, 201);
                        else 
                            throw new DBException("No Records Found", 201);
                    }
                }
                
            }
            else {
                if(!empty($errorMsg))
                    throw new DBException($errorMsg, 201);
                else 
                    throw new DBException("Empty Result Returned", 201);
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
                throw new DBException('Database Error', 201);
            }
       }
    }

    public function getSingleDatasByArray($sqlQuery, $RecCond="NO"){
        $singleData = array();
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
                    $singleData = $row;
                }

                if($RecCond == 'YES'){
                    return $singleData;
                }
                else {
                    if($flg == 0){

                        if(!empty($errorMsg))
                            throw new DBException($errorMsg, 201);
                        else 
                            throw new DBException("No Records Found", 201);
                    }
                }
                
            }
            else {
                if(!empty($errorMsg))
                    throw new DBException($errorMsg, 201);
                else 
                    throw new DBException("Empty Result Returned", 201);
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
                throw new DBException('Database Error', 201);
            }
       }
    }
}
?>