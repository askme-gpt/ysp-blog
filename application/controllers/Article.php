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

    /**
     * layui分页
     * @return [type] [description]
     */
    public function indexAction()
    {
        $params = $this->getRequest()->getQuery();
        $search = $params['q'] ?? null;
        $page   = $params['page'] ?? 1;
        $limit  = $params['limit'] ?? 10;
        $data   = $this->article->index($search, $page, $limit);
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
        array_walk($tags, function (&$x) {
            $x['value'] = $x['id'];
            unset($x['id']);
            return $x;
        });
        $tags = json_encode($tags, 320);
        return view('article.add', compact('categories', 'tags'));
    }

    /**
     * 创建文章，使用模型验证，好处是可以指定场景，避免到处写验证逻辑
     * @return [type] [description]
     */
    public function createAction()
    {
        $_POST['user_id'] = 1;
        $_POST['content'] = json_encode($_POST['content'], 384);
        $_POST['tags']    = $_POST['select'];
        unset($_POST['select']);
        $this->article->load($_POST)->atScene('create');
        if (!$insert_id = $this->article->create()) {
            exit($this->article->firstError());
        }
        return redirect("/article/read", ['id' => $insert_id]);
    }

    /**
     * 根据文章分类id获取类目文章
     * @return [type] [description]
     */
    public function categoryAction()
    {
        $category_id = $this->getRequest()->getQuery('cat');
        $data        = $this->article->articleByCategory($category_id);
        return view('article.index', compact('data'));
    }
}
