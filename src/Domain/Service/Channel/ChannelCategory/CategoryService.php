<?php

declare(strict_types=1);

namespace App\Domain\Service\Channel\ChannelCategory;

interface CategoryService
{

    /**
     * Summary of avilablechannel
     * @param mixed $inputdata
     * @param mixed $userid
     * @param mixed $userName
     * @return void
     */
    public function avilablechannel($inputdata,$userid,$userName);
    /**
     * Summary of assignedchannellist
     * @param mixed $inputdata
     * @param mixed $userid
     * @param mixed $userName
     * @return void
     */
    public function assignedchannellist($inputdata,$userid,$userName);
     /*
    * View  repository function
    */
    public function ViewCategorylist($inputdata,$userName,$action);
    /*
    * Add brand repository function
    */
    public function create($inputdata,$userName);
    
    /*
    * Editable view repository function
    */

    public function getOneCategory($input1,$input2,$input3);

    /*
    * Update view repository function
    */
    public function update($inputdata,$input1,$input2);

    /* 
    * Delete view repository function
    */
    //public function delete($input1,$imput2,$input3);

}