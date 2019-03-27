<?php

namespace App\Command;

use App\Client\GoogleDriveManager;
use App\PathGenerator\UploadFilesPathGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UploadFileCommand extends Command
{
    protected static $defaultName = 'app:upload-file';
    private $uploaderPathGetter;
    private $googleDriveManager;

    /**
     * UploadFileCommand constructor.
     * @param UploadFilesPathGenerator $uploaderPathGetter
     * @param GoogleDriveManager $googleDriveManager
     */
    public function __construct(UploadFilesPathGenerator $uploaderPathGetter, GoogleDriveManager $googleDriveManager)
    {
        $this->uploaderPathGetter = $uploaderPathGetter;
        $this->googleDriveManager = $googleDriveManager;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('filename', InputArgument::REQUIRED, 'Filename to upload')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $io->writeln('Succesfully uploaded file with id: ' .
                $this->googleDriveManager->upload($input->getArgument('filename')));
        }
        catch(\Exception $exception){
            $io->writeln('Uploading the file we found the problem:' . $exception->getMessage());
        }
    }
}
