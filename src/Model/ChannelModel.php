<?php

namespace App\Model;
use App\Factory\DBConFactory;
use App\Factory\LoggerFactory;
use App\Exception\Channel\ChannelException;
use App\Model\DB;
class ChannelModel extends BaseModel
{
    
    protected DBConFactory $dBConFactory;
    protected LoggerFactory $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory, DBConFactory $dBConFactory)
    {
        $this->loggerFactory = $loggerFactory;
        $this->dBConFactory = $dBConFactory;
    }
    
    public function viewchannellist($userid,$username){
        $objLogger = $this->loggerFactory->addFileHandler($username.'_ChannelModel.log')->createInstance('ChannelModel');
        try 
        {

            $action = "VIEW";
            $sqlQuery = "call SP_AddandEditChannelInfo('$action','','', 0, 0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $ListChannel = $dbObjt->getMultiDatasByObjects($sqlQuery);
               
                if(!empty($ListChannel)){
                    return $ListChannel;
                }
                else{
                    if (empty($ListChannel)) {
                        throw new ChannelException('Channel credentials invalid. ', 200);
                    }
                }

        }catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }

    }

    public function createchannel($channelname,  $channelimg,$userid,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('ChannelModel_'.$userName.'.log')->createInstance('ChannelModel');
        try 
        {
            $action = "ADD";
			$baseurl='';
			
			if(!empty($channelname)){
				
				//$filename = $channelimg[0]->getClientOriginalName();
				
				//print_r($channelimg);die();
				$j = 0;
				foreach($channelname as $chnlnme){
					$ext = pathinfo($channelimg[$j]->getClientFilename(), PATHINFO_EXTENSION);
                    $imagename = $chnlnme.'.'.$ext;
					
					//$filepath = $parentUrl.'/'.$imagename;
                    $parentUrl = "../public/uploads/Channels";
					
					$sqlQuery = "call SP_AddandEditChannelInfo('$action','$chnlnme','' ,0 ,$userid)";
					$objLogger->info('Query : '.$sqlQuery);
					$dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
					$user = $dbObjt->getSingleDatasByObjects($sqlQuery);

                    //print_R($user);die()
                    if(strtoupper($user->status) != 'FAILURE'){
                        $lastinsertid = $user->lastinsertId;

                        $imagename = $lastinsertid.'.'.$ext;
					
					
					    $baseurl = "public/uploads/Channels/".$imagename;
                        $updateStatus = $this->updateImagepath($lastinsertid,$baseurl,$userName);
                        if($updateStatus){
                            $objLogger->info("Updated image Path : ".$updateStatus);
                        }

                       

                        

                        if ($channelimg[$j]->getError() === UPLOAD_ERR_OK) {

                            //print_R($channelimg[$j]->getError());die();
                            //echo $parentUrl . DIRECTORY_SEPARATOR . $imagename;
                            if(!file_exists($parentUrl)){
                                mkdir($parentUrl,0777,true);
                            }
                            $channelimg[$j]->moveTo($parentUrl . DIRECTORY_SEPARATOR . $imagename);
                        }

                    }               
		
				
					$j++;
				}

            }
            /*if(!empty($channelimg)){
                $decoded_string = base64_decode($channelimg,true);
                if ($decoded_string === false)
                { 
                    throw new ChannelException('Invalid Image Format. ', 200);
                }
                $f = finfo_open();

                /* ------ Start get File Extention -----*/
               /* $mime_type = finfo_buffer($f, $decoded_string, FILEINFO_MIME_TYPE);
                $expext = explode('/',$mime_type);
                $ext = $expext[1];
                /* ------ END get File Extention -----*/

               /* $imagename = $channelname.'.'.$ext;
                //$parentUrl = "../uploads/".$channelname."/";
                $parentUrl = "../uploads/Channels";
                $filepath = $parentUrl.'/'.$imagename;

                if(!file_exists($parentUrl)){
                    mkdir($parentUrl,0777,true);
                }
                file_put_contents($filepath, $decoded_string);
            }*/



				/*$sqlQuery = "call SP_AddandEditChannelInfo('$action','$channelname','$baseurl' ,0 ,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);*/
                
               
                if(!empty($user->msg)){
                
                    return $user;
                }
                else{
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 200);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }
    }

    public function getoneModel($channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('ChannelModel_'.$userName.'.log')->createInstance('ChannelModel');
        try 
        {
            $action = "GETONE";
                        
           
            $sqlQuery = "call SP_AddandEditChannelInfo('$action','', '', $channelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 200);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." 1Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }
    }


    public function update($channelname, $channelimg,$channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('ChannelModel_'.$userName.'.log')->createInstance('ChannelModel');
        try 
        {
            $action = "UPDATE";
            $filepath ='';
			$baseurl = '0';
			if(!empty($channelimg)){
				
				//if(!empty($channelimg)){
					
					$ext = pathinfo($channelimg->getClientFilename(), PATHINFO_EXTENSION);
				//print_R($ext);die();
				$imagename = $channelid.'.'.$ext;
				$parentUrl = "../public/uploads/Channels";
				
				$baseurl = "public/uploads/Channels/".$imagename;
                //$filepath = $parentUrl.'/'.$imagename;
	
				if ($channelimg->getError() === UPLOAD_ERR_OK) {
					//echo $parentUrl . DIRECTORY_SEPARATOR . $imagename;
					if(!file_exists($parentUrl)){
						mkdir($parentUrl,0777,true);
					}
					$channelimg->moveTo($parentUrl . DIRECTORY_SEPARATOR . $imagename);
				}
				//}
				

            }
            /*if(!empty($channelimg)){
                $decoded_string = base64_decode($channelimg,true);
                if ($decoded_string === false)
                {
                    throw new ChannelException('Invalid Image Format. ', 200);
                }
                $f = finfo_open();

                /* ------ Start get File Extention -----*/
                //$mime_type = finfo_buffer($f, $decoded_string, FILEINFO_MIME_TYPE);
                //$expext = explode('/',$mime_type);
                //$ext = $expext[1];
                /* ------ END get File Extention -----*/

                //$imagename = $channelname.'.'.$ext;
                //$parentUrl = "../uploads/".$channelname."/";
                //$parentUrl = "../uploads/Channels";
                //$filepath = $parentUrl.'/'.$imagename;

                //if(!file_exists($filepath)){
                  //  mkdir($parentUrl,0777,true);
                //}
                //file_put_contents($filepath, $decoded_string);
            //}*/
            $sqlQuery = "call SP_AddandEditChannelInfo('$action','$channelname', '$baseurl', $channelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new ChannelException('Channel  credentials invalid. ', 200);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." 1Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }
    }

    public function deleteModel($channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('ChannelModel_'.$userName.'.log')->createInstance('ChannelModel');
        try 
        {
            $action = "DELETE";
                        
           
            $sqlQuery = "call SP_AddandEditChannelInfo('$action','', '', $channelid,$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
               
                if(!empty($user)){
                    $imgpath = isset($user->imgpath)?$user->imgpath :'';
                        if(file_exists($imgpath)):
                            unlink($imgpath);         
                        endif;
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 200);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->error("Error File : ".$ex->getFile()." 1Error Line : ".$ex->getLine());
            $objLogger->error("Error Trace String : ".$ex->getTraceAsString());
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }
    }

    public function  updateImagepath($channelid,$path,$userName){
        $objLogger = $this->loggerFactory->addFileHandler('ChannelModel_'.$userName.'.log')->createInstance('ChannelModel');
        $sqlQuery = "update tvchannelslist set channellogo = '".$path."' where id=".$channelid;
        $objLogger->info('Query : '.$sqlQuery);
        $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
        $pathlist = $dbObjt->insOrUpdteOrDetQuery($sqlQuery);

        if(!empty($pathlist)){
            return $path;
        }
    }   


    // private function rrmdir($dir) {
	// 	//print_r("hai");die();
	// 	if (is_dir($dir)) {
	// 		//print_r("hai");die();
	// 	  $objects = scandir($dir);
	// 	  foreach ($objects as $object) {
	// 		if ($object != "." && $object != "..") {
	// 		  if (filetype($dir."/".$object) == "dir") 
	// 			 	$this->rrmdir($dir."/".$object); 
	// 		  else unlink   ($dir."/".$object);
	// 		}
	// 	  }
	// 	  reset($objects);
	// 	  rmdir($dir);
	// 	}
	// }
}




// if (is_dir($file)) {
//     self::deleteDir($file);
// } else {
//     unlink($file);
// }