<?php

namespace Jatmy\Framework\Http;

class Kernel
{
    public function handle(Request $request): Response
    {
        $content = 'test';
        return new Response($content);
    }
}
