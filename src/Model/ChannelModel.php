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
	

    public function viewchannellist($search_value,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('ChannelModel_'.$userName, 'viewchannellist');  
        try 
        {
            $objLogger->info("======= START Channel  Model (viewchannellist) ================");
            $action = "VIEW";
            $sqlQuery = "call SP_AddandEditChannelInfo('$action','','', 0, 0,'".$search_value."',0)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $ListChannel = $dbObjt->getMultiDatasByObjects($sqlQuery);
                $objLogger->info("======= END Channel  Model (viewchannellist) ================");
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
            $objLogger->info("======= END Channel  Model (viewchannellist) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 401);
            }
            else {
                throw new ChannelException('Database Error', 401);
            }
        }

    }

    public function createchannel($channelname,  $channelimg,$userid,$userName){
        $objLogger = $this->loggerFactory->getFileObject('ChannelModel_'.$userName, 'createchannel'); 
        try 
        {
            $objLogger->info("======= START Channel Model (createchannel) ================");
            $action = "ADD";
			$baseurl='';
			
			if(!empty($channelname)){
				$replacenme = str_replace("_"," ",$channelname);
				//$j = 0;
				//foreach($channelname as $chnlnme){
                    //$ext = pathinfo($channelimg[$j]->getClientFilename(), PATHINFO_EXTENSION);
					$ext = pathinfo($channelimg->getClientFilename(), PATHINFO_EXTENSION);
                    //$imagename = $replacenme.'.'.$ext;
					
					//$filepath = $parentUrl.'/'.$imagename;
                    $parentUrl = "../public/uploads/channels";
					
					$sqlQuery = "call SP_AddandEditChannelInfo('$action','$channelname','' ,0,0,'' ,$userid)";
					$objLogger->info('Query : '.$sqlQuery);
					$dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
					$user = $dbObjt->getSingleDatasByObjects($sqlQuery);

                    //print_R($user);die()
                    if(strtoupper($user->status) != 'FAILURE'){
                        $lastinsertid = $user->lastinsertId;
                        $extention = strtolower($ext);
                        $imagename = $lastinsertid.'.'.$extention;
					
					
					    $baseurl = "public/uploads/channels/".$imagename;
                        $updateStatus = $this->updateImagepath($lastinsertid,$baseurl,$userName);
                        if($updateStatus){
                            $objLogger->info("Updated image Path : ".$updateStatus);
                        }

                       

                        

                        if ($channelimg->getError() === UPLOAD_ERR_OK) {

                            //print_R($channelimg[$j]->getError());die();
                            //echo $parentUrl . DIRECTORY_SEPARATOR . $imagename;
                            if(!file_exists($parentUrl)){
                                mkdir($parentUrl,0777,true);
                            }
                           // $channelimg[$j]->moveTo($parentUrl . DIRECTORY_SEPARATOR . $imagename);
                            $channelimg->moveTo($parentUrl . DIRECTORY_SEPARATOR . $imagename);

                        }

                    }               
		
				
					//$j++;
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
                
                $objLogger->info("======= START Channel Model (createchannel) ================");
                if(!empty($user->msg)){
                    if($user->status == "FAILURE" ){
                        throw new ChannelException($user->msg, 201);
                    }else{
                        return $user;
                    }
                    
                }
                else{
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 201);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." Error Message : ".$ex->getMessage());
            $objLogger->info("======= START Channel Model (createchannel) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }

    public function getoneModel($channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->getFileObject('ChannelModel_'.$userName, 'getoneModel'); 
        try 
        {
            $objLogger->info("======= START Channel  Model (getoneModel) ================");
            $action = "GETONE";
                        
           
            $sqlQuery = "call SP_AddandEditChannelInfo('$action','', '', $channelid,0,'',$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                $objLogger->info("======= START Channel  Model (getoneModel) ================");
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new ChannelException('Channel credentials invalid. ', 201);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Channel  Model (getoneModel) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }


    public function update($channelname, $channelimg,$channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->getFileObject('ChannelModel_'.$userName, 'update'); 
        try 
        {
            $objLogger->info("======= START Channel  Model (update) ================");
            $action = "UPDATE";
            $filepath ='';
			$baseurl = '0';
			if(!empty($channelimg)){
				
				//if(!empty($channelimg)){
					
					$ext = pathinfo($channelimg->getClientFilename(), PATHINFO_EXTENSION);
                    $extcasechage = strtolower($ext);
                    $min = 1;
                    $max = 100;
                    $randomNumber = random_int($min, $max); // Generate a cryptographically secure random integer between $min and $max

				//print_R($ext);die();
				$imagename = $channelid."_".$randomNumber.'.'.$extcasechage;
				$parentUrl = "../public/uploads/channels";
				
				$baseurl = "public/uploads/channels/".$imagename;
                //$filepath = $parentUrl.'/'.$imagename;
	
				if ($channelimg->getError() === UPLOAD_ERR_OK) {
					//echo $parentUrl . DIRECTORY_SEPARATOR . $imagename;
					if(!file_exists($parentUrl)){
						mkdir($parentUrl,0777,true);
					}

                    if(is_dir($parentUrl)){
                        $scandirimg = scandir($parentUrl);
                        for($sndr = 0 ;$sndr<count($scandirimg);$sndr++){
                            if(str_contains($scandirimg[$sndr], $channelid)) {
                                $unlinkimg = $parentUrl.'/'.$scandirimg[$sndr];
                                unlink($unlinkimg);
                                break;
                            }
                        }
                        
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
            $sqlQuery = "call SP_AddandEditChannelInfo('$action','$channelname', '$baseurl', $channelid,0,'',$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                $objLogger->info("======= END Channel  Model (update) ================");
                if(!empty($user)){
                    return $user;
                }
                else{
                   // $objLogger->info('User Data : '.json_encode($user));
                    if (empty($user)) {
                        throw new ChannelException('Channel  credentials invalid. ', 201);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->info("======= START Channel  Model (update) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
            }
        }
    }

    public function deleteModel($channelid,$userid,$userName){

        $objLogger = $this->loggerFactory->addFileHandler('ChannelModel_'.$userName.'.log')->createInstance('ChannelModel');
        try 
        {
            $objLogger->info("======= START Channel  Model (deleteModel) ================");
            $action = "DELETE";                       
           
            $sqlQuery = "call SP_AddandEditChannelInfo('$action','', '', $channelid,0,'',$userid)";
                $objLogger->info('Query : '.$sqlQuery);
                $dbObjt = new DB($this->loggerFactory, $this->dBConFactory);
                $user = $dbObjt->getSingleDatasByObjects($sqlQuery);
                $objLogger->info("======= END Channel  Model (deleteModel) ================");
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
                        throw new ChannelException('Channel credentials invalid. ', 201);
                    }
                }
                
        } catch (ChannelException $ex) {

            $objLogger->error("Error Code : ".$ex->getCode()." 12Error Message : ".$ex->getMessage());
            $objLogger->info("======= END Channel  Model (deleteModel) ================");
            if(!empty($ex->getMessage())){
                throw new ChannelException($ex->getMessage(), 201);
            }
            else {
                throw new ChannelException('Database Error', 201);
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