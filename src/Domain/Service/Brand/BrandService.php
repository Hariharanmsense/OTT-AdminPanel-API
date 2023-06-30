<?php

declare(strict_types=1);

namespace App\Domain\Service\Brand;

interface BrandService
{

     /*
    * View brand repository function
    */
    public function ViewbrandListRepository($inputdata);
    /*
    * Add brand repository function
    */
    public function AddnewBrand($inputdata);
    
    /*
    * Editable view repository function
    */

    public function EditViewRepository($input,$inputdata);

    /*
    * Update view repository function
    */
    public function UpdateRepository($inputdata);

    /* 
    * Delete view repository function
    */
    public function DeleteBrandRepository($input,$inputdata);

    /*
    * Active or deactive repository function
    */
    public function actordeact($inputData,$brandid);
}