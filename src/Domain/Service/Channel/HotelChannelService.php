<?php

declare(strict_types=1);

namespace App\Domain\Service\Channel;

interface HotelChannelService
{

     /*
    * View  repository function
    */
    public function ViewChannellist($inputdata);
    /*
    * Add brand repository function
    */
    public function create($inputdata);
    
    /*
    * Editable view repository function
    */

    public function getOneHotelChannel($input1,$input2,$input3);

    /*
    * Update view repository function
    */
    public function update($inputdata,$input1,$input2);

    /* 
    * Delete view repository function
    */
    //public function delete($input1,$imput2,$input3);

    /*
    * Assign Channel repository function
    */
    public function assginMenu($input, $username,$auditBy);
	
	
	/*
	*Get Over all list channels
	*/
	public function getOverallchannellist($hotelid,$userid,$userName);
}	