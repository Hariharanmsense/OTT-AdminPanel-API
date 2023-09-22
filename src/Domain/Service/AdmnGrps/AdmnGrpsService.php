<?php

namespace App\Domain\Service\AdmnGrps;

interface AdmnGrpsService
{

    public function getGrpOne($groupid, $auditBy, $hotelid, $brandid);
    public function create($input, $auditBy, $hotelid, $brandid);
    public function update($input, $groupid, $auditBy, $hotelid, $brandid);
    public function getGrpList($input, $auditBy, $hotelid);
    public function getMenuRightStatus1($auditBy, $menuid);
    public function excel($response, $input, $auditBy, $hotelid, $brandid);
    public function createGroupAssginMenu($input, $auditBy);
    public function editGroupAssginMenu($input, $auditBy);
    public function delete($groupid, $auditBy);
}
