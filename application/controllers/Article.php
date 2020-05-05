<?php
use Yaf\Controller_Abstract as Controller;

class ArticleController extends Controller
{
    private $article;

    public function init()
    {
        $this->article = new ArticleModel();
    }

    public function indexAction()
    {
        $search = $this->getRequest()->getQuery('q');
        $this->article->index();

    }
}
