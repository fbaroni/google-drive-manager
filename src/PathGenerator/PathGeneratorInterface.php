<?php

namespace App\PathGenerator;

interface PathGeneratorInterface
{
    public function getPath(): string;

    public function getAbsolutePathFile($fileName = null): string;
}