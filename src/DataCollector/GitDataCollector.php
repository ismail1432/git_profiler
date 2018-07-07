<?php


namespace App\DataCollector;


use App\BranchLoader\GitLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class GitDataCollector extends DataCollector
{
    private $gitLoader;

    public function __construct(GitLoader $gitLoader)
    {
        $this->gitLoader = $gitLoader;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        // We add tht git informations in the data
        $this->data = [
            'git_branch' => $this->gitLoader->getBranchName(),
            'last_commit_message' => $this->gitLoader->getLastCommitMessage(),
            'logs' => $this->gitLoader->getLastCommitDetail(),
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
        return $this->data['logs']['author'];
    }

    public function getLastCommitDate()
    {
        return $this->data['logs']['date'];
    }
}