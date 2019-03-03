<?php

namespace App\PathGenerator;

use Symfony\Component\HttpKernel\KernelInterface;

class UploadFilesPathGenerator implements PathGeneratorInterface
{
    private $kernel;

    /**
     * UploadFilesPathGenerator constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }


    public function getPath(): string
    {
        return $this->kernel->getProjectDir() . '/files/';
    }

    public function getAbsolutePathFile($fileName = null): string
    {
        if (!$fileName) {
            throw new \RuntimeException('Filename should not be null');
        }

        return $this->getPath() . $fileName;
    }
}