<?php


namespace App\Controller;


use App\BranchLoader\GitLoader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function profiler(GitLoader $gitLoader)
    {
        return $this->render('base.html.twig');
    }
}