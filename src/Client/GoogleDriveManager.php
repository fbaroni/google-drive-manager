<?php

namespace App\Client;

use App\PathGenerator\CredentialsPathGenerator;

class GoogleDriveManager implements GoogleDriveManagerInterface
{
    private $credentialsPathGenerator;
    private $client;
    private $service;

    public function __construct(CredentialsPathGenerator $credentialsPathGenerator)
    {
        $this->credentialsPathGenerator = $credentialsPathGenerator;
        $this->client = $this->getClient();
        $this->service = $this->getServiceDrive();
    }

    public function getClient(): \Google_Client
    {
        try {
            $client = $this->createClient();

            return $this->setCredentialsToken($client);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getServiceDrive(): \Google_Service_Drive
    {
        $service = new \Google_Service_Drive($this->client);

        return $service;
    }

    public function listFirstFiles(): string
    {
        $optParams = array(
            'pageSize' => 10,
            'fields' => 'nextPageToken, files(id, name)'
        );
        $results = $this->service->files->listFiles($optParams);

        $files = '';
        if (count($results->getFiles()) == 0) {
            return "No files found.\n";
        } else {
            foreach ($results->getFiles() as $file) {
                $files .= sprintf("%s (%s)\n", $file->getName(), $file->getId());
            }
        }

        return $files;
    }

    protected function createClient(): \Google_Client
    {
        $client = new \Google_Client();
        $client->setApplicationName(getenv('GOOGLE_CLIENT_APP_NAME'));
        $client->setScopes(\Google_Service_Drive::DRIVE);
        $client->setAuthConfig($this->credentialsPathGenerator->getAbsolutePathCredentilsFile());
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        return $client;
    }

    /**
     * @param \Google_Client $client
     * @return \Google_Client
     * @throws \Exception
     */
    protected function setCredentialsToken(\Google_Client $client): \Google_Client
    {
        $tokenPath = $this->credentialsPathGenerator->getAbsolutePathTokenFile();

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new \Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }
}