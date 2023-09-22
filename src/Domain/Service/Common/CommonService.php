<?php

namespace App\Domain\Service\Common;

interface CommonService
{
   public function getAllGroup($auditBy, $brandid, $hotelid);
	public function getAllBrand($auditBy, $brandid, $hotelid);
    public function getAllHotel($auditBy, $brandid, $hotelid);
    public function getAllAssignMenus($input, $auditBy);
    public function getAllAvailableMenus($input, $auditBy);
    public function getAllReadWriteMenus($jsndata, $auditBy);
    public function getAllTimeZone($auditBy, $brandid, $hotelid);
    public function getAllSideBarMenu($auditBy, $brandid, $hotelid);
    public function getMenuRightAccess($menuid, $auditBy, $brandid, $hotelid);
    public function getAllDevices($auditBy, $brandid, $hotelid);
    public function getAllDeviceStatus($auditBy, $brandid, $hotelid);
    public function getAllIcmpPolicys($auditBy, $brandid, $hotelid);
    public function getAllDeviceLocations($auditBy, $brandid, $hotelid);
    public function getAllDeviceTypes($auditBy, $brandid, $hotelid);
    public function getAllInternetLists($auditBy, $brandid, $hotelid);
    public function getAllSubMenus($auditBy, $menuid, $hotelid, $brandid);
    public function getAllallowedBrands($auditBy);
    public function getAllallowedHotels($auditBy, $brandId);
    public function getMenuRightsByHotelMenu($auditBy, $menuid, $hotelid);
    public function getAllallowedHotelsByGroup($auditBy, $groupId);
    public function getAllAvaliableHotelByGroup($auditBy, $groupId);
    public function getAllIneterfaceType($auditBy);
    public function getAlllatestHotels($auditBy);
	public function getAllottSideBarMenu($auditBy, $brandid, $hotelid);
}
