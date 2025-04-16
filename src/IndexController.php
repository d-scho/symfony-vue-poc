<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class IndexController
{
    #[Route(path: '/', name: 'index')]
    public function index(): Response
    {
        return new Response('Hello World!');
    }
}