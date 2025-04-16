<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class VueController extends AbstractController
{
    #[Route(path: '/vue-spa/{filename}', name: 'app_vue_spa', methods: 'GET')]
    public function vueSpa(
        string $filename,
        ParameterBagInterface $parameterBag
    ): Response {
        $projectDir = $parameterBag->get('kernel.project_dir');

        $filePath = $projectDir . '/assets/vue-spa/build/' . $filename;

        try {
            $content = file_get_contents($filePath);
        } catch (\Throwable) {
            return new Response('Vue SPA asset not found', Response::HTTP_NOT_FOUND);
        }

        return new Response($content, headers: [
            'Content-Type' => 'text/javascript',
        ]);
    }

    #[Route(path: '/{path}', name: 'app_survey', requirements: ['path' => '.*'], methods: 'GET')]
    public function survey(): Response
    {
        return new Response(<<<HTML
            <div id="app"></div>
            <script type="module" src="vue-spa/app-B2LplHYB.js"></script>    
            HTML
        );
    }
}