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
        $fileName = getenv('CREDENTIALS_FILE');

        return $this->getPath() . $fileName;
    }
}