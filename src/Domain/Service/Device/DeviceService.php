<?php

namespace App\Domain\Service\Device;

interface DeviceService
{

    public function getUsrOne($assetid, $auditBy);
    public function create($input, $auditBy,$userName);
    public function update($input, $deviceid, $auditBy);
    public function getDeviceList($inputData,$auditBy,$userName, $hotelid, $startDate, $endDate);
    public function getMenuRightStatus($auditBy, $menuid);
    public function bulkUpload($input, $files, $auditBy);
}
