<?php

namespace App\Domain\Service\WizardSetup;

interface WizardSetupService
{
    public function getAlltemplate($inputdata,$input1,$input2);
    public function AddFeatures($inputdata,$input1,$input2);
	public function createJsonFile($inputdata,$image1,$image2,$imagearray1,$input1,$input2);    
	//public function createJsonFile($inputdata,$image1,$image2,$input1,$input2);
    public function gettemplateDetails($hotelid,$tempid,$userid,$userName);
    public function updatefeed($JWTdata,$userid,$userName);
    public function bulkUploadrepository($JWTdata,$bulkuploadfile,$userId,$userName);
	
}
