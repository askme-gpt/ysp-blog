<?php

class ArticleModel extends BaseModel
{
    public $table = 'articles';

    /**
     * 规则验证
     * https://github.com/inhere/php-validate/wiki/config-rules-like-laravel
     * @return [type] [description]
     */
    public function rules(): array
    {
        return [
            ['user_id,title,type,content,category_id,tags', 'required'],
            ['type', 'in', [10, 20, 30]],
            ['content', 'string', 'min' => 2, 'max' => 16500],
            ['category_id', 'number'],
        ];
    }

    public function index($search = '', $page = 0, $limit = 15)
    {
        $conditions = [
            $this->table . '.status' => 10,
            "OR"                     => [
                $this->table . '.title[~]'   => $search,
                $this->table . '.content[~]' => $search,
            ],
        ];
        $limit = [
            'ORDER' => [$this->table . '.id' => 'DESC'],
            'LIMIT' => [($page - 1) * $limit, $limit],
        ];
        $list = $this->db->select($this->table, [
            "[>]users"      => ["user_id" => "id"],
            "[>]categories" => ["category_id" => "id"],
        ], [
            $this->table . '.id',
            $this->table . '.title',
            $this->table . '.tags',
            $this->table . '.content',
            $this->table . '.visits',
            $this->table . '.created_at',
            'users.name',
            'categories.name',
        ], array_merge($conditions, $limit));
        $count = $this->db->count($this->table, $conditions);
        return [
            'list'  => $list,
            'count' => $count,
        ];
    }

    public function allArticle()
    {
        return $this->db->select(
            $this->table,
            ['id', 'title', 'content', 'updated_at'],
            ['status' => 10]
        );
    }

    /**
     * 根据id查找文章相关信息
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findArticle($id)
    {
        $article = $this->db->get(
            $this->table,
            [
                "[>]users"      => ["user_id" => "id"],
                "[>]categories" => ["category_id" => "id"],
            ],
            [
                $this->table . '.id',
                $this->table . '.title',
                $this->table . '.tags',
                $this->table . '.content',
                $this->table . '.visits',
                $this->table . '.like',
                $this->table . '.created_at',
                $this->table . '.updated_at',
                'users.name',
                'categories.name',
            ],
            [
                $this->table . '.id'     => $id,
                $this->table . '.status' => 10,
            ]
        );
        return $article;
    }

    /**
     * 查找文章评论
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findArticleComments($id)
    {
        $comments = $this->db->select($this->table, [
            "[>]replies" => ["id" => "article_id"],
            "[>]users"   => ["replies.user_id" => "id"],
        ], [
            'users.name',
            'replies.id',
            'replies.like',
            'replies.created_at',
            'replies.content',
        ], [
            'replies.article_id'     => $id,
            'replies.status'         => 10,
            $this->table . '.status' => 10,
        ]);
        return $comments;
    }

    public function articleInfo($id)
    {
        $article = $this->findArticle($id);
        $data    = [
            'data'     => $article,
            'comments' => $this->findArticleComments($id),
            'tags'     => $this->articleTags($article['tags'] ?? ''),
        ];
        return $data;
    }

    public function articleTags($tag_ids)
    {
        $tag = new TagModel();
        return $tag->findTagsById($tag_ids);
    }

    /**
     * [articleByCategory description]
     * @param  [type] $category_id [description]
     * @return [type]              [description]
     */
    public function articleByCategory($category_id, $offset = 0, $limit = 15)
    {
        $list = $this->db->select($this->table, [
            "[>]users"      => ["user_id" => "id"],
            "[>]categories" => ["category_id" => "id"],
        ], [
            $this->table . '.id',
            $this->table . '.title',
            $this->table . '.tags',
            $this->table . '.content',
            $this->table . '.visits',
            $this->table . '.created_at',
            'users.name',
            'categories.name',
        ], [
            $this->table . '.status'      => 10,
            $this->table . '.category_id' => $category_id,
            'ORDER'                       => [$this->table . '.id' => 'DESC'],
            'LIMIT'                       => [$offset, $limit],
        ]);
        $count = $this->db->count($this->table, ['status' => 10, 'category_id' => $category_id]);
        return [
            'list'  => $list,
            'count' => $count,
        ];
    }

    public function paginate($value = '')
    {

    }
}
