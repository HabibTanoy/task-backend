<?php

namespace App\Traits;

trait ResponseTrait
{
    private $statusCode = 200;
    private $message = "Operation Successful";
    private $resourceName = "data";

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setResourceName($name)
    {
        $this->resourceName = $name;
        return $this;
    }

    private function getSuccessResponse()
    {
        return [
            'success'       => true,
            'errors'        => $this->message,
            ''              => '',
        ];
    }

    public function responseWithSuccess()
    {
        return response()
            ->json($this->getSuccessResponse(), $this->statusCode);
    }

    public function responseWithItem($item)
    {
        $success = $this->getSuccessResponse();
        $success[$this->resourceName] = $item;

        return response()
            ->json($success, $this->statusCode);

    }

    public function responseWithError()
    {
        return response()->json([
            'status_code'   => $this->statusCode,
            'error'         => $this->message,
        ], $this->statusCode);
    }

    public function responseWithCollection($collection)
    {
        $response = $collection->toArray();
        $data = [
            'status_code'       => $this->statusCode,
            'message'           => $this->message,
            $this->resourceName => isset($response['data']) ? $response['data'] : $response,
        ];

        if (isset($response['per_page'])) {
            $data['pagination'] = [
                'per_page'      => (int) $response['per_page'],
                'current_page'  => (int) $response['current_page'],
                'total'         => (int) $response['total'],
            ];
        }
        return response()->json($data, $this->statusCode);
    }

    public function responseWithNotAllowed($message = "User don't have permission")
    {
        return $this->setStatusCode(403)
            ->setMessage($message)
            ->responseWithError();
    }
}
