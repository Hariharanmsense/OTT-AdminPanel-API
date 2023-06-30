<?php

namespace App\Domain\Service\AdmnGrps;

interface AdmnGrpsService
{

    public function getGrpOne($groupid, $userid, $hotelid, $brandid);
    public function create($input, $userid, $hotelid, $brandid);
    public function update($input, $groupid, $userid, $hotelid, $brandid);
    public function getGrpList($input, $userid, $hotelid, $brandid);
    public function getMenuRightStatus($userid, $menuid);
}
