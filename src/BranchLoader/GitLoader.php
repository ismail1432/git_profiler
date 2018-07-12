<?php


namespace App\BranchLoader;


class GitLoader
{
    const NO_BRANCH = 'no branch name';
    const HEAD = '/.git/HEAD';
    const COMMIT_EDIT_MESSAGE = '/.git/COMMIT_EDITMSG';
    const GIT_LOG_FILE = '/.git/logs/HEAD';

    private $projectDir;

    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function getBranchName()
    {
        $branchname = self::NO_BRANCH;

        $stringFromFile = file_exists(self::HEAD) ? file(self::HEAD, FILE_USE_INCLUDE_PATH) : "";

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
        $commitMessage = file_exists(self::COMMIT_EDIT_MESSAGE) ? file(self::COMMIT_EDIT_MESSAGE, FILE_USE_INCLUDE_PATH) : "";

        return \is_array($commitMessage) ? trim($commitMessage[0]) : "";
    }

    public function getLastCommitDetail()
    {
        $logs = $this->getGitLogs();

        return \is_array($logs) ? end($logs) : [];
    }

    public function getGitLogs()
    {
        $logs = [];
        $gitLogs = file_exists(self::GIT_LOG_FILE) ? file(self::GIT_LOG_FILE, FILE_USE_INCLUDE_PATH) : "";

        foreach ($gitLogs as $item => $value) {

            $logExploded = explode(' ', $value);
            $logs[$item]['sha'] = $logExploded[1] ?? 'not defined';
            $logs[$item]['author'] = $logExploded[2] ?? 'not defined';
            $logs[$item]['email'] = preg_replace('#<|>#','',$logExploded[3]) ?? 'not defined';
            $logs[$item]['date'] = isset($logExploded[4]) ? date('Y/m/d H:i', $logExploded[4]) : "not defined";
        }

        return $logs;
    }
}
