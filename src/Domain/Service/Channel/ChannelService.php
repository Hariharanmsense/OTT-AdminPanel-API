<?php

declare(strict_types=1);

namespace App\Domain\Service\Channel;

interface ChannelService
{

     /*
    * View Channel repository function
    */
    public function ViewChannellist($inputdata,$input1,$input2);
    /*
    * Add Hotel repository function
    */
   
    public function create($inputdata,$input1,$input2,$input3);
    /*
    * Editable repository function
    */
    public function getOneChannel($channelid, $userid,$userName);
   

    /*
    * Update  repository function
    */
   public function update($inputdata,$channelid,$userid,$input1,$input2);

    /* 
    * Delete  repository function
    */
    public function delete($channelid,$userid,$userName);
    
    public function excel($response,$input, $userid,$userName);
}