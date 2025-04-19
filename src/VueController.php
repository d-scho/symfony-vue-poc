<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;

#[AsController]
final class VueController
{
    use GuessesMimeTypes;

    private const string BUILD_LOCATION = '/assets/vue-spa/build';
    private const string SERVED_FILES_PATH = self::BUILD_LOCATION . '/public';
    private const string MANIFEST_PATH = self::BUILD_LOCATION . '/internal-manifest.json';
    private const string ENTRY_FILE_NAME = 'app.ts';

    private string $projectDir;

    public function __construct(
        private readonly CacheInterface $vueAppAssetsCache,
        ParameterBagInterface $parameterBag,
    ) {
        $this->projectDir = $parameterBag->get('kernel.project_dir');
    }

    #[Route(path: '/vue-spa/{filename}', name: 'app_vue_spa', methods: 'GET')]
    public function vueSpa(string $filename): Response
    {
        return $this->vueAppAssetsCache->get('vue_app_asset.' . $filename, function () use ($filename) {
            $filePath = $this->projectDir . self::SERVED_FILES_PATH . "/$filename";

            if (!file_exists($filePath)) {
                return new Response('Vue SPA asset not found', Response::HTTP_NOT_FOUND);
            }

            return new Response(file_get_contents($filePath), headers: [
                'Content-Type' => $this->guessMimeType($filename),
            ]);
        });
    }

    /**
     * @throws ManifestError
     */
    #[Route(path: '/{path}', name: 'app_catch_all', requirements: ['path' => '.*'], methods: 'GET')]
    public function catchAll(): Response
    {
        $appFileName = $this->getAppFileName();

        return new Response(<<<HTML
            <div id="app"></div>
            <script type="module" src="vue-spa/$appFileName"></script>
            HTML
        );
    }

    /**
     * @throws ManifestError
     */
    private function getAppFileName(): string
    {
        return $this->vueAppAssetsCache->get('vue_app_entry_file', function () {
            $manifestPath = $this->projectDir . self::MANIFEST_PATH;

            if (!file_exists($manifestPath)) {
                throw ManifestError::fileMissing();
            }

            $manifest = file_get_contents($manifestPath);

            try {
                $content = json_decode($manifest, true, 512, JSON_THROW_ON_ERROR);

                $entryFileKey = 'src/' . self::ENTRY_FILE_NAME;

                assert(is_array($content), new \InvalidArgumentException('No array decoded from json file', 1745057660));
                assert(array_key_exists($entryFileKey, $content), new \InvalidArgumentException('No key for app file in decoded json', 1745057966));
                assert(is_array($content[$entryFileKey]), new \InvalidArgumentException('No array for app file in decoded json', 1745057999));
                assert(array_key_exists('file', $content[$entryFileKey]), new \InvalidArgumentException('No key for file path decoded json', 1745058033));

                $fileName = $content[$entryFileKey]['file'];

                assert(is_string($content[$entryFileKey]['file']), new \InvalidArgumentException('File path for app is not a string', 1745058079));
            } catch (\JsonException|\InvalidArgumentException $e) {
                throw ManifestError::unexpectedContent($e);
            }

            // the manifest.json entry will contain the subdirectory of the actually served files relative from the build folder
            // hence we take the substring to get only the file name by reverse engineering the string length of said directory from the class constants
            return substr(
                $fileName,
                strlen(substr(self::SERVED_FILES_PATH, strlen(self::BUILD_LOCATION))),
            );
        });
    }
}