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

        $stringFromFile = file_exists($gitHeadFile) ? file($gitHeadFile, FILE_USE_INCLUDE_PATH) : "";

        if(isset($stringFromFile) && is_array($stringFromFile)) {
            //get the string from the array
            $firstLine = $stringFromFile[0];
            //seperate out by the "/" in the string
            $explodedString = explode("/", $firstLine, 3);

            $branchname = trim($explodedString[2]);
        }

        return $branchname;
    }

    public function getLastCommitMessage()
    {
        $gitCommitMessageFile = $this->projectDir.'/.git/COMMIT_EDITMSG';
        $commitMessage = file_exists($gitCommitMessageFile) ? file($gitCommitMessageFile, FILE_USE_INCLUDE_PATH) : "";

        return \is_array($commitMessage) ? trim($commitMessage[0]) : "";
    }

    public function getLastCommitDetail()
    {
        $logs = [];
        $gitLogFile = $this->projectDir.'/.git/logs/HEAD';
        $gitLogs = file_exists($gitLogFile) ? file($gitLogFile, FILE_USE_INCLUDE_PATH) : "";

        $logExploded = explode(' ', end($gitLogs));
        //$logExploded[2] contains author name
        $logs['author'] = $logExploded[2] ?? 'not defined';

        //$logExploded[4] contains the last commit timestamp
        $logs['date'] = isset($logExploded[4]) ? date('Y/m/d H:i', $logExploded[4]) : "not defined";

        return $logs;
    }
}
