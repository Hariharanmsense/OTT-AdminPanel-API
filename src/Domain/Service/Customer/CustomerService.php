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
    

}