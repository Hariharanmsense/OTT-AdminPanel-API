<?php

declare(strict_types=1);

namespace App\Domain\Service\Login;

interface LoginService
{
    public function doAuth($inputdata);

    public function logUser($userId);

}
