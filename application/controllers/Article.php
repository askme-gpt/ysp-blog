<?php
use Inhere\Validate\FieldValidation;
use Inhere\Validate\Validation;
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

    public function readAction()
    {
        $id   = $this->getRequest()->getQuery('id');
        $data = $this->article->articleInfo($id);
        return view('article.read', $data);
    }

    /**
     * 文章添加页面
     */
    public function addAction()
    {
        $tag        = new TagModel();
        $category   = new CategoryModel();
        $tags       = $tag->index();
        $categories = $category->index();
        return view('article.add', compact('categories', 'tags'));
    }

    public function createAction()
    {
        $_POST['user_id'] = 1;
        $_POST['content'] = json_encode($_POST['content'], 384);
        $_POST['tags']    = join($_POST['tags'], ',');
        dd($_POST);
        $this->article->load($_POST)->atScene('create');
        if (!$insert_id = $this->article->create()) {
            exit($this->article->firstError());
        }
        return redirect("/article/read", ['id' => $insert_id]);
    }

    public function categoryAction()
    {
        $category_id = $this->getRequest()->getQuery('cat');
        $data        = $this->article->articleByCategory($category_id);
        return view('article.index', compact('data'));

    }
}
