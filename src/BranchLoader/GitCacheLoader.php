<?php


namespace App\BranchLoader;


use Psr\SimpleCache\CacheInterface;


class GitCacheLoader
{
    const HEAD = '/.git/HEAD';
    const COMMIT_EDIT_MESSAGE = '/.git/COMMIT_EDITMSG';
    const GIT_LOG_FILE = '/.git/logs/HEAD';


    private $cache;
    private $headFile;
    private $commitEditMessage;
    private $gitLogFile;

    public function __construct(CacheInterface $cache, $projectDir)
    {
        $this->cache = $cache;
        $this->headFile = $projectDir.self::HEAD;
        $this->commitEditMessage = $projectDir.self::COMMIT_EDIT_MESSAGE;
        $this->gitLogFile = $projectDir.self::GIT_LOG_FILE;
    }

    public function getBranchName()
    {
        return $this->cache->get('git.branch_name');
    }

    public function branchCacheIsValid()
    {
        return $this->cache->has('git.time_store_branch_name') ? $this->cache->get('git.time_store_branch_name') === filemtime($this->headFile) : false;
    }

    public function setBranchNameInCache($branchName)
    {
        $this->cache->set('git.time_store_branch_name', filemtime($this->headFile));
        $this->cache->set('git.branch_name', $branchName);
    }

    public function lastCommitMessageCacheIsValid()
    {
        return $this->cache->has('git.time_last_commit_message') ? $this->cache->get('git.time_last_commit_message') === filemtime($this->commitEditMessage) : false;
    }

    public function getLastCommitMessage()
    {
        return $this->cache->get('git.last_commit_message');

    }

    public function setLastCommitMessageInCache($message)
    {
        $this->cache->set('git.time_last_commit_message', filemtime($this->commitEditMessage));
        $this->cache->set('git.last_commit_message', $message);
    }

    public function getLastCommitDetail()
    {
        return $this->cache->get('git.last_commit_detail');

    }

    public function setLastCommitDetailInCache($details)
    {
        $this->cache->set('git.time_last_commit_detail', filemtime($this->gitLogFile));
        $this->cache->set('git.last_commit_detail', $details);
    }

    public function setLogsInCache($logs)
    {
        $this->cache->set('git.time_logs', filemtime($this->gitLogFile));
        $this->cache->set('git.logs', $logs);
    }

    public function getLogsFromCache()
    {
        $this->cache->get('git.logs');
    }

    public function gitLogsCacheIsValid()
    {
        return $this->cache->has('git.time_logs') ? $this->cache->get('git.time_logs') === filemtime($this->gitLogFile) : false;
    }
}
