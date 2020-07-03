<?php

use Medoo\Medoo;

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
            ['id', 'required', 'int', 'on' => 'update'],
            ['content', 'string', 'min' => 2, 'max' => 16500],
            ['category_id', 'number'],
        ];
    }

    public function index(string $search = '', int $page = 0, int $limit = 15, int $category_id = 0, int $tag_id = 0, int $user_id = 0)
    {
        $conditions = [
            $this->table . '.status' => 10,
        ];

        if ($search) {
            $search_con = [
                "OR" => [
                    $this->table . '.title[~]'   => $search,
                    $this->table . '.content[~]' => $search,
                ],
            ];
            $conditions = array_merge($conditions, $search_con);
        }

        if ($category_id && is_int($category_id)) {
            $category_con = [
                $this->table . '.category_id' => $category_id,
            ];
            $conditions = array_merge($conditions, $category_con);
        }

        if ($tag_id && is_int($tag_id)) {
            $tag_con    = [Medoo::raw(sprintf('FIND_IN_SET(%s,<tags>)', $tag_id))];
            $conditions = array_merge($conditions, $tag_con);
        }

        if ($user_id && is_int($user_id)) {
            $user_con = [
                $this->table . '.user_id' => $user_id,
            ];
            $conditions = array_merge($conditions, $user_con);
        }

        $limit = [
            'ORDER' => [$this->table . '.id' => 'DESC'],
            'LIMIT' => [($page - 1) * $limit, $limit],
        ];

        // $list = $this->db->debug()->select($this->table, [
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
            'users.id(uid)',
            'users.name(user_name)',
            'categories.id(cid)',
            'categories.name(category_name)',
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
                'users.name(username)',
                'categories.name(category_name)',
                'categories.id(cid)',
            ],
            [
                $this->table . '.id'     => $id,
                $this->table . '.status' => 10,
            ]
        );
        // dd($article);
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
        if (!$article) {
            return [];
        }
        $data = [
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
}
