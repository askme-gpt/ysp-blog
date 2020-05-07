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
        $data   = $this->article->index();
        return view('article.index', compact('data'));
    }

    public function readAction($id)
    {
        $data = $this->article->articleInfo($id);
        return view('article.read', $data);
    }

    public function addAction()
    {
        return view('article.add');
    }

    public function createAction()
    {
        dd($_POST);
    }
}
