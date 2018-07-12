<?php


namespace App\DataCollector;


use App\BranchLoader\GitLoader;
use App\BranchLoader\GitManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class GitDataCollector extends DataCollector
{
    private $gitLoader;
    private $gitManager;

    public function __construct(GitLoader $gitLoader, GitManager $gitManager)
    {
        $this->gitLoader = $gitLoader;
        $this->gitManager = $gitManager;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        // We add tht git informations in the data
        $this->data = [
            'git_branch' => $this->gitManager->findCurrentBranch(),
            'last_commit_message' => $this->gitManager->findLastCommitMessage(),
            'last_commit_details' => $this->gitManager->findLastCommitDetail(),
            'logs' => $this->gitManager->findLogs(),
        ];
    }

    public function getName()
    {
        return 'app.git_data_collector';
    }

    public function reset()
    {
        $this->data = array();
    }

    //Some helpers to access more easily in the template
    public function getGitBranch()
    {
        return $this->data['git_branch'];
    }

    public function getLastCommitMessage()
    {
        return $this->data['last_commit_message'];
    }

    public function getLastCommitAuthor()
    {
        return $this->data['last_commit_details']['author'];
    }

    public function getLastCommitDate()
    {
        return $this->data['last_commit_details']['date'];
    }

    public function getLogs()
    {
        return $this->data['logs'];
    }
}