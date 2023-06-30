<?php

declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;

class ActionPayload implements JsonSerializable
{
    private int $statusCode;

    /**
     * @var array|object|null
     */
    private $data;

    private $errorMsg;

    public function __construct(int $statusCode = 200,$data = null, $errorMsg = '') {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->errorMsg = $errorMsg;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array|null|object
     */
    public function getData()
    {
        return $this->data;
    }

    public function getErrorMsg():string
    {
        return $this->errorMsg;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $payload = [
            'code' => $this->statusCode,
        ];

        if($this->statusCode == 200){
            $payload['status'] = true;
        }
        else {
            $payload['status'] = false;
        }

        if ($this->data !== null) {
            $payload['data'] = $this->data;
        } elseif ($this->errorMsg !== null) {
            $payload['message'] = $this->errorMsg;
        }

        return $payload;
    }
}
