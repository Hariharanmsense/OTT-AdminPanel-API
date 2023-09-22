<?php
declare(strict_types=1);
namespace App\Model;
class BaseModel
{
    
	protected function allowedAsciiValueCheck($string){
		
		if(!mb_detect_encoding($string, 'ASCII', true)) {
			return false;
		}

		return true;
	}
    protected function dateFormatchange($date) {
		$dateChange='';
		//echo $date;die;
		if($date<>'' && $date<>'null'){
			$dateChange = date("d M Y", strtotime($date)); 
		}  
		return $dateChange; 
	} 
	
	//=== Date Format Change ===//
	protected function dateFormat($date, $changeFormat="Y-m-d H:i:s") {
		$dateChange='';
		//echo $date;die;
		/*
			$myDateTime = DateTime::createFromFormat('Y-m-d', $dateString);
			$newDateString = $myDateTime->format('m/d/Y');
		*/
		if(!empty(($date)) && !empty($changeFormat)){

			$dateChange = date($changeFormat, strtotime($date)); 
		}  
		return $dateChange; 
		

	} 

	protected function moveUploadedFile($directory, $orginalName, $filename)
    {
        // print_r(($orginalName->getClientFilename()));die();
        $ImageName = $orginalName->getClientFilename();


        $ext = pathinfo($ImageName, PATHINFO_EXTENSION);

        $newName = $filename . '.' . $ext;

        if ($orginalName->getError() === UPLOAD_ERR_OK) {

            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $orginalName->moveTo($directory . DIRECTORY_SEPARATOR . $newName);

        }
        //echo $newName;die();
        //$orginalName->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $newName;
    }

}
?>