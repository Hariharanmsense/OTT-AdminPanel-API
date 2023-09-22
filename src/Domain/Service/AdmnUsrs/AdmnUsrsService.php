<?php

namespace App\Domain\Service\AdmnUsrs;

interface AdmnUsrsService
{

    public function getUsrOne($userid, $auditBy, $hotelid, $brandid);
    public function create($input, $auditBy, $hotelid, $brandid);
    public function update($input, $userid, $auditBy, $hotelid, $brandid);
    public function getUsrsList($input, $auditBy, $hotelid, $brandid);
    public function getMenuRightStatus1($auditBy, $menuid);
    public function forgotPassword($input);
    public function resetPassword($input);
    public function activeOrDeactiveUser($userid, $auditBy);
    public function excel($response, $input, $auditBy, $hotelid, $brandid);
    public function delete($userid, $auditBy);
    public function resetPasswordGet($resetCode);
    public function PasswordUpdate($input, $auditBy);
    public function oldPassword($input, $auditBy);
    public function getLastLogin($input, $auditBy);
    public function ownPasswordUpdate($input);
    public function profileUpdate($JWTdata, $auditBy);
    
}
