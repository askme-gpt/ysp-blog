<?php
use Yaf\Controller_Abstract as Controller;

class MenuController extends Controller
{

    public function indexAction()
    {
        $data = MenuModel::index();
    }
}
