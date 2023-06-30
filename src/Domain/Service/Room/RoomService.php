<?php

declare(strict_types=1);

namespace App\Domain\Service\Room;

interface RoomService
{

     /*
    * View brand repository function
    */
    public function viewroomlist($inputdata);
    /*
    * Add brand repository function
    */
    public function create($inputdata);
    
    /*
    * Editable view repository function
    */

    public function getOne($input,$inputdata,$input2);

    /*
    * Update view repository function
    */
    public function update($inputdata,$input1);

    /* 
    * Delete view repository function
    */
    public function delete($input,$inputdata);

}