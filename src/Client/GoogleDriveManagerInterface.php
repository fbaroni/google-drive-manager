<?php
namespace App\Client;

interface GoogleDriveManagerInterface
{
    public function getClient(): \Google_Client;
    public function getServiceDrive(): \Google_Service_Drive;
}