<?php

declare(strict_types=1);

namespace App\Domain\Service\Channel\ChannelCategory;

interface CategoryService
{

     /*
    * View  repository function
    */
    public function ViewCategorylist($inputdata);
    /*
    * Add brand repository function
    */
    public function create($inputdata);
    
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