<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\RequestStack;

class PdfService
{
    private Pdf $pdf;
    private RequestStack $requestStack;
    private array $options;

    public function __construct(Pdf $pdf, RequestStack $requestStack, string $publicDir)
    {
        $this->pdf = $pdf;
        $this->requestStack = $requestStack;
        $this->createDirIfNotExists($publicDir);

        $this->options = [
            'margin-top' => '0',
            'margin-left' => '0',
            'margin-right' => '0',
            'dpi' => '300',
            'page-size' => 'A4'
        ];
    }

    public function generateFromUrl(string $url, string $fileNamePrefix, string $locale = null): string
    {
        $request = $this->requestStack->getMasterRequest();
        $fileName = $fileNamePrefix . uniqid((string) rand(), true);

        // DOCKER CONTAINER PORT WORKAROUND
        if ($request->getHost() === 'localhost') {
            $url = str_replace(':'.$_ENV['HOST_PORT'], ':'.$_ENV['CONTAINER_PORT'], $url);
        }

        $this->pdf->setOption(
            'custom-header',
            [
                'locale' => $locale ?? $request->headers->get('locale'),
            ]
        );
        $outputDir = sprintf('%s/%s.pdf', $_ENV['PDF_STORAGE_URL_PATH'],  $fileName);
        $this->pdf->generate($url, $outputDir);

        return $request->getSchemeAndHttpHost().'/'.$outputDir;
    }

    public function generateFromView(string $renderedView, string $fileNamePrefix): string
    {
        $request = $this->requestStack->getMasterRequest();
        $date = new \DateTime('now', new \DateTimeZone('Europe/Warsaw'));
        $date = $date->format("Y-m-d_h-m");
        $fileName = $fileNamePrefix . $date . '_' . uniqid((string) rand());

        $outputDir = sprintf('%s/%s.pdf', $_ENV['PDF_STORAGE_URL_PATH'],  $fileName);
        $this->pdf->generateFromHtml($renderedView, $outputDir);

        return $request->getSchemeAndHttpHost().'/'.$outputDir;
    }

    private function createDirIfNotExists(string $publicDir): void
    {
        $dir = $publicDir . DIRECTORY_SEPARATOR . $_ENV['PDF_STORAGE_URL_PATH'];
        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }
}
