<?php
namespace App\Client;

interface FileManagerInterface
{
    public function listFirstFiles(): string;
    public function upload($fileName): string;
}