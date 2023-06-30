<?php

namespace App\Domain\Service\AdmnUsrs;

interface AdmnUsrsService
{

    public function getUsrOne($userid, $auditBy, $hotelid, $brandid);
    public function create($input, $auditBy, $hotelid, $brandid);
    public function update($input, $userid, $auditBy, $hotelid, $brandid);
    public function getUsrsList($input, $auditBy, $hotelid, $brandid);
    public function getMenuRightStatus($auditBy, $menuid);
    public function forgotPassword($input);
    public function resetPassword($input);
    public function activeOrDeactiveUser($userid, $auditBy);
}
