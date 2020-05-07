<?php
use Yaf\Controller_Abstract as Controller;

/**
 *
 */
class ToolController extends Controller
{
    public function uploadImageAction()
    {
        $option = [
            'path' => APPLICATION_PATH . '/public/upload/',
        ];
        $upload = new Upload($option);
        if (!($file_name = $upload->uploadFile('upload_file'))) {
            echo $upload->errorInfo;
        }
        echo json_encode(['file_path' => '/public/upload/' . basename($file_name)]);
        // dd($file_name);

    }
}
