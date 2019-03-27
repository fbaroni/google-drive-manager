<?php

namespace App\PathGenerator;

use Symfony\Component\HttpKernel\KernelInterface;

class CredentialsPathGenerator implements PathGeneratorInterface
{
    private $kernel;

    /**
     * CredentialsPathGenerator constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }


    public function getPath(): string
    {
        return $this->kernel->getProjectDir() . '/credentials/';
    }

    public function getAbsolutePathFile($fileName = null): string
    {
        return $this->getPath() . $fileName;
    }

    public function getAbsolutePathCredentilsFile(): string
    {
        $fileName = getenv('GOOGLE_CREDENTIALS_FILE');

        return $this->getAbsolutePathFile($fileName);
    }

    public function getAbsolutePathTokenFile(): string
    {
        $fileName = getenv('GOOGLE_TOKEN_FILE');

        return $this->getAbsolutePathFile($fileName);
    }
}