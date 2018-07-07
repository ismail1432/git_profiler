<?php


namespace App\BranchLoader;


class GitLoader
{
    private $projectDir;

    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function getBranchName()
    {
        $gitHeadFile = $this->projectDir.'/.git/HEAD';

        $branchname = 'no branch name';

        $stringFromFile = file_exists($gitHeadFile)
            ? file($gitHeadFile, FILE_USE_INCLUDE_PATH) : "";

        if(isset($stringFromFile) && is_array($stringFromFile)) {
            //get the string from the array
            $firstLine = $stringFromFile[0];
            //seperate out by the "/" in the string
            $explodedString = explode("/", $firstLine, 3);

            $branchname = $explodedString[2];
        }

        return $branchname;
    }

    public function getLastCommitMessages()
    {
        $gitCommitMessages = $this->projectDir.'/.git/COMMIT_EDITMSG';
        $stringFromFile = file_exists($gitCommitMessages)
            ? file($gitCommitMessages, FILE_USE_INCLUDE_PATH) : "";

        dump($stringFromFile);
    }
}