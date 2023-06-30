<?php

namespace App\Domain\Service\Tvsolution;

interface TvtempService
{
    public function getAlltemplate($inputdata,$auditBy);
	public function getallchannelfeed($inputdata,$auditBy);
	public function getallfeatures($inputdata,$auditBy);
	public function getJsonfile($templateid,$auditBy,$userName);
}
