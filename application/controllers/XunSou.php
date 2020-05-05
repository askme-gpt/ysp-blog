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

    public function init($value = '')
    {
        // 关闭view
        Yaf\Dispatcher::getInstance()->disableView();

        $xs = new \XS(APPLICATION_PATH . '/conf/xunsou.ini'); // 建立 XS 对象，项目名称为：demo
        // 获取索引对象
        $this->_index = $xs->index;
        // 创建文档对象
        $this->_doc = new XSDocument;
    }
    /**
     * 增加文档
     */
    public function addAction()
    {
        $article = new ArticleModel();
        $data    = $article->allArticle();
        foreach ($data as $value) {
            // 添加到索引数据库中
            $this->_doc->setFields($value);
            $this->_index->add($this->_doc);
        }
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

    public function flushAction($value = '')
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
        // 宣布开始重建索引
        $this->_index->beginRebuild();

        // 然后在此开始添加数据
        $this->_index->add($this->_doc);

        // 告诉服务器重建完比
        $this->_index->endRebuild();
    }

    /**
     * 中止索引重建 丢弃重建临时库的所有数据, 恢复成当前搜索库, 主要用于偶尔重建意外中止的情况
     * @return [type] [description]
     */
    public function stopRebuild()
    {
        $this->_index->stopRebuild();
    }
}
