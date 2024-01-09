<?php

namespace Jatmy\Framework\Controller;

use Jatmy\Framework\Http\Response;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function render(string $view, array $data = [], Response $response = null): Response
    {
        $view .= '.html.twig';
        /** @var \Twig\Environment $twig */
        $twig = $this->container->get('twig');
        $content = $twig->render($view, $data);

        $response ??= new Response();

        $response->setContent($content);

        return $response;
    }
}
