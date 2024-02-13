<?php
namespace App\Listeners;

use Jatmy\Framework\Http\Events\ResponseEvent;

class ContentLenghtListener
{
    public function __invoke(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        if(is_null($response->getHeader('Content-Length'))) {
            $response->setHeader('Content-Length', strlen($response->getContent()));
        }
    }
}
