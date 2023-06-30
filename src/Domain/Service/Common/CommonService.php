<?php

namespace App\Domain\Service\Common;

interface CommonService
{
    public function getAllBrand($auditBy, $brandid, $hotelid);
    public function getAllHotel($auditBy, $brandid, $hotelid);
    public function getAllAssignMenus($auditBy, $brandid, $hotelid);
    public function getAllReadWriteMenus($jsndata, $auditBy);
    public function getAllTimeZone($auditBy, $brandid, $hotelid);
	public function getAllSideBarMenu($auditBy, $brandid, $hotelid);
    public function getMenuRightAccess($menuid, $auditBy, $brandid, $hotelid);
}
