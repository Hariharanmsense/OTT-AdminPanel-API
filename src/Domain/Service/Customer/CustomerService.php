<?php

declare(strict_types=1);

namespace App\Domain\Service\Customer;

interface CustomerService
{

     /*
    * View Hotel repository function
    */
    public function Viewhotellist($inputdata);
    /*
    * Add Hotel repository function
    */
   
    public function create($inputdata);
    /*
    * Editable repository function
    */
    public function getsinglehotel($groupid, $userid,$userName);
   

    /*
    * Update  repository function
    */
    public function update($inputdata,$hotelid);

    /* 
    * Delete  repository function
    */
     public function delete($inputdata,$hotelid,$input2);
	
	
	 /* 
    * Activate / Deactivate repository function
    */
     public function activeOrDeactive($hotelId, $auditBy);

    /* 
    * ICMP Activate / Deactivate repository function
    */
    public function icmpStatus($hotelId, $auditBy);

    /* 
    * ALERT EMAIL Activate / Deactivate repository function
    */
    public function alertEmailStatus($hotelId, $auditBy);

    /* 
    * BW Activate / Deactivate repository function
    */
    public function bwStatus($hotelId, $auditBy);

    /* 
    * Export to Excel repository function
    */
    public function excel($response, $input, $auditBy);

     /* 
    * Generate Hotel Code Suggestion
    */
    public function gnrteHtlCde($input, $brandid, $hotelname, $auditBy);
    

}