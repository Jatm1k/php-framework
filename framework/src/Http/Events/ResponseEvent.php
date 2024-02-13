<?php
namespace Jatmy\Framework\Http\Events;

use Jatmy\Framework\Event\Event;
use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;

class ResponseEvent extends Event
{
    public function __construct(
        private Request $request,
        private Response $response
    ) {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
