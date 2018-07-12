<?php


namespace App\BranchLoader;


class GitLoader
{
    const COMMIT_EDIT_MESSAGE = '/.git/COMMIT_EDITMSG';
    const GIT_LOG_FILE = '/.git/logs/HEAD';
    const HEAD = '/.git/HEAD';
    const NO_BRANCH = 'no branch name';

    private $commitEditMsg;
    private $gitLogFile;
    private $headFile;

    public function __construct($projectDir)
    {
        $this->commitEditMsg = $projectDir.self::COMMIT_EDIT_MESSAGE;
        $this->gitLogFile = $projectDir.self::GIT_LOG_FILE;
        $this->headFile = $projectDir.self::HEAD;
    }

    public function getBranchName()
    {
        $branchname = self::NO_BRANCH;

        $stringFromFile = file_exists($this->headFile) ? file($this->headFile, FILE_USE_INCLUDE_PATH) : "";
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
        $commitMessage = file_exists($this->commitEditMsg) ? file($this->commitEditMsg, FILE_USE_INCLUDE_PATH) : "";

        return \is_array($commitMessage) ? trim($commitMessage[0]) : "";
    }

    public function getLastCommitDetail()
    {
        $logs = $this->getLogs();

        return \is_array($logs) ? end($logs) : [];
    }

    public function getLogs()
    {
        $logs = [];
        $gitLogs = file_exists($this->gitLogFile) ? file($this->gitLogFile, FILE_USE_INCLUDE_PATH) : "";

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
