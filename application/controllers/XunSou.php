<?php

use Yaf\Controller_Abstract as Controller;

/**
 * @name XunSouController
 * @desc 讯搜相关的一些方法
 * @see http://www.xunsearch.com/doc/php/guide/start.overview
 */
class XunSouController extends Controller
{
    private $_index;
    private $_doc;
    private $_search;

    public function init($value = '')
    {
        // 关闭view
        Yaf\Dispatcher::getInstance()->disableView();

        $xs = new \XS(APPLICATION_PATH . '/conf/xunsou.ini'); // 建立 XS 对象，项目名称为：demo
        // 获取索引对象
        $this->_index = $xs->index;

        // 获取搜索对象
        $this->_search = $xs->search;

        // 创建文档对象
        $this->_doc = new XSDocument;
    }

    /**
     * 增加文档
     */
    public function addAction(array $value)
    {
        // 添加到索引数据库中
        $this->_doc->setFields($value);
        $this->_index->add($this->_doc);
    }

    /**
     * 更新文档，需要有主键
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function updateAction(array $data = [])
    {
        // 如果索引数据库中已存在主键值相同的文档，那么相当于先删除原有的文档，再用当前文档替换它。 如果未存在主键值相同的文档，则效果和添加文档完全一致。
        // 有人可能会想，既然如此为什么还要有 XSIndex::add 呢，因为添加文档少了一个判断 过程，具有更高一些的效率。因此，如果您在使用 API 时能明确知道当前文档是新增的，那么 还是建议使用 add 这个 API。
        $this->_doc->setFields($data);
        $this->_index->update($this->_doc);
    }

    /**
     * 删除索引，根据主键删除
     * @param  string|array $ids [单个数字，或者数字数组]
     * @return [type]      [description]
     */
    public function deleteAction($ids = '')
    {
        return $this->_index->del($ids);
    }

    public function flushAction()
    {
        // 执行清空操作
        return $this->_index->clean();
    }

    /**
     * 前一章讲到有些情况不得不需要重建索引，可以用 XSIndex::clean 立即全部清空所有数据， 然后再把现有数据全部添加到索引数据库中。
     * @return [type] [description]
     */
    public function rebuildAction()
    {
        $this->flushAction();
        $this->_index->beginRebuild();

        $article = new ArticleModel();
        $data    = $article->allArticle();
        foreach ($data as &$value) {
            $value['updated_at'] = date('Ymd', strtotime($value['updated_at']));
            // 添加到索引数据库中
            $this->_doc->setFields($value);
            $this->_index->add($this->_doc);
        }
        $this->_index->endRebuild();
    }

    /**
     * 中止索引重建 丢弃重建临时库的所有数据, 恢复成当前搜索库, 主要用于偶尔重建意外中止的情况
     * @return [type] [description]
     */
    public function stopRebuildAction()
    {
        $this->_index->stopRebuild();
    }
    /**
     * [getHotAction description]
     * @param  [type] $limit 整数值，设置要返回的词数量上限，默认为 6，最大值为 50
     * @param  [type] $type  指定排序类型，默认为 total(总量)，可选值还有：lastnum(上周) 和 currnum(本周)
     * @return [type]        [description]
     */
    public function getHotAction($limit = 10, $type = 'total')
    {
        $words = $search->getHotQuery($limit, $type); // 获取前 10 个上周热门词
    }

    public function searchAction()
    {
        // 支持的 GET 参数列表
        // q: 查询语句
        // m: 开启模糊搜索，其值为 yes/no
        // f: 只搜索某个字段，其值为字段名称，要求该字段的索引方式为 self/both
        // s: 排序字段名称及方式，其值形式为：xxx_ASC 或 xxx_DESC
        // p: 显示第几页，每页数量为 XSSearch::PAGE_SIZE 即 10 条
        // ie: 查询语句编码，默认为 UTF-8
        // oe: 输出编码，默认为 UTF-8
        // xml: 是否将搜索结果以 XML 格式输出，其值为 yes/no
        $__ = array('q', 'm', 'f', 's', 'p', 'syn', 'xml');
        foreach ($__ as $_) {
            $$_ = $_GET[$_] ?? '';
        }
        // recheck request parameters
        $q = get_magic_quotes_gpc() ? stripslashes($q) : trim($q);
        if (empty($q)) {
            $q = '?';
        }

        $f = empty($f) ? '_all' : $f;

        // base url
        $bu = $this->getRequest()->uri . '?q=' . urlencode($q) . '&m=' . $m . '&f=' . $f;

        // dd($bu);
        // other variable maybe used in tpl
        $count       = $total       = $search_cost       = 0;
        $docs        = $related        = $corrected        = $hot        = array();
        $error       = $pager       = '';
        $total_begin = microtime(true);

        // perform the search
        try {
            $search = $this->_search;
            $search->setCharset('UTF-8');

            if (empty($q)) {
                // just show hot query
                $hot = $search->getHotQuery();
            } else {
                // fuzzy search
                $search->setFuzzy($m === 'checked');

                // synonym search
                $search->setAutoSynonyms($syn === 'checked');

                // set query
                if (!empty($f) && $f != '_all') {
                    $search->setQuery($f . ':(' . $q . ')');
                } else {
                    $search->setQuery($q);
                }

                // set sort
                if (($pos = strrpos($s, '_')) !== false) {
                    $sf = substr($s, 0, $pos);
                    $st = substr($s, $pos + 1);
                    $search->setSort($sf, $st === 'ASC');
                }

                // set offset, limit
                $p = max(1, intval($p));

                #搜索结果默认分页数量
                $n = 15;
                $search->setLimit($n, ($p - 1) * $n);

                // get the result
                $search_begin = microtime(true);
                $docs         = $search->search();
                $search_cost  = microtime(true) - $search_begin;

                // get other result
                $count = $search->getLastCount();
                $total = $search->getDbTotal();

                if ($count < 1 || $count < ceil(0.001 * $total)) {
                    $corrected = $search->getCorrectedQuery();
                }
                // get related query
                $related = $search->getRelatedQuery();

                // gen pager
                if ($count > $n) {
                    $pb    = max($p - 5, 1);
                    $pe    = min($pb + 10, ceil($count / $n) + 1);
                    $pager = '';
                    do {
                        $pager .= ($pb == $p) ? '<li class="disabled"><a>' . $p . '</a></li>' : '<li><a href="' . $bu . '&p=' . $pb . '">' . $pb . '</a></li>';
                    } while (++$pb < $pe);
                }
            }
        } catch (XSException $e) {
            $error = strval($e);
        }
        // calculate total time cost
        $total_cost = microtime(true) - $total_begin;
        return view('xunsou.search', compact('q', 's', 'f', 'm', 'syn', 'hot', 'docs', 'search', 'count', 'total', 'total_cost', 'error', 'related', 'corrected', 'search_cost', 'pager'));
    }

    public function searchNewAction()
    {
        $total_begin = microtime(true);
        $q           = $this->request->getQuery('q');
        $params      = [
            'q'   => $q,
            'f'   => "_all",
            'm'   => "checked",
            'syn' => "checked",
            's'   => "relevance",
        ];
        $bu = $this->getRequest()->uri . '?' . http_build_query($params);

        // other variable maybe used in tpl
        $count = $total = $search_cost = 0;
        $docs  = $related  = $corrected  = $hot  = array();
        $error = $pager = '';

        // perform the search
        try {
            $search = $this->_search;
            $search->setCharset('UTF-8');

            if (empty($q)) {
                // just show hot query
                $hot = $search->getHotQuery();
            } else {
                // fuzzy search
                $search->setFuzzy(true);

                // synonym search
                $search->setAutoSynonyms(true);

                // set query
                if (!empty($f) && $f != '_all') {
                    $search->setQuery($f . ':(' . $q . ')');
                } else {
                    $search->setQuery($q);
                }

                // set sort,默认顺序，相关性靠前
                $search->setSort('relevance');
                // set offset, limit
                $p = max(1, intval($p));

                #搜索结果默认分页数量
                $n = 10;
                $search->setLimit($n, ($p - 1) * $n);

                // get the result
                $search_begin = microtime(true);
                $docs         = $search->search();
                $search_cost  = microtime(true) - $search_begin;

                // get other result
                $count = $search->getLastCount();
                $total = $search->getDbTotal();

                if ($count < 1 || $count < ceil(0.001 * $total)) {
                    $corrected = $search->getCorrectedQuery();
                }
                // get related query
                $related = $search->getRelatedQuery();
            }
        } catch (XSException $e) {
            $error = strval($e);
        }
        // calculate total time cost
        $total_cost = microtime(true) - $total_begin;
        return compact('q', 'hot', 'docs', 'search', 'count', 'total', 'total_cost', 'error', 'related', 'corrected', 'search_cost');
    }

    /**
     * 边输入边搜索
     * @return [type] [description]
     */
    public function suggestAction()
    {
        $q     = $_GET['term'] ?? '';
        $q     = get_magic_quotes_gpc() ? stripslashes($q) : $q;
        $terms = array();
        if (!empty($q) && strpos($q, ':') === false) {
            try {
                $terms = $this->_search->setCharset('UTF-8')->getExpandedQuery($q);
            } catch (XSException $e) {

            }
        }
        echo response_json($terms);
    }

    public function __destruct()
    {
        $this->_index  = null;
        $this->_doc    = null;
        $this->_search = null;
    }
}
