<?php

namespace App\Controllers;

use Models;
use Core;
use Core\View;
use Core\Controller;

class ProductsController extends Controller {

    var $layout = 'admin';

    function __construct() {
        if (empty($_SESSION['users'])) {
            //header("Location: " . INSTALL_URL . "?controller=Admin&action=login", true, 301);
        }
    }

    public function index() {
        $productModel = new \App\Models\Product();

        // Извличане на всички записи от таблицата gallery
        $products = $productModel->getAll();

        // Прехвърляне на данни към изгледа
        $this->view($this->layout, ['products' => $products]);
    }

    public function edit() {
        $productModel = new \App\Models\Product();
        $imageModel = new \App\Models\Image();

        if (!empty($_POST['send_frm'])) {
            
            $data = array();
            $data['id'] = $_POST['id'];
            $data['product_name'] = $_POST['product_name'];
            $data['product_description'] = $_POST['product_description'];
            
            $productModel->update($data);

            header("Location: " . INSTALL_URL . "?controller=Products&action=index", true, 301);
        }

        $arr = array();
        $arr['product'] = $productModel->get($_REQUEST['id']);

        $opts = array();
        $opts['gallery_id'] = $_REQUEST['id'];
        $arr['images'] = $imageModel->getAll($opts);

        // Прехвърляне на данни към изгледа
        $this->view($this->layout, $arr);
    }

    public function create() {
        $productModel = new \App\Models\Product();

        if (!empty($_POST['send_frm'])) {

            $data = array();
            $data['product_name'] = $_POST['product_name'];
            $data['product_description'] = $_POST['product_description'];
            $productModel->save($data);
            
            header("Location: " . INSTALL_URL . "?controller=Products&action=index", true, 301);
        }

        $this->view($this->layout, array());
    }

    public function upload() {
        $imageModel = new \App\Models\Image();

        require 'App\Helpers\uploader\src\class.upload.php';

        $handle = new \Verot\Upload\Upload($_FILES['file']);

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $img_name = time();

            if ($handle->uploaded) {

                $thumb_dest = 'web/upload';

                $handle->file_new_name_body = $img_name;
                $handle->image_resize = true;
                $handle->image_x = 200;
                $handle->image_ratio_y = true;

                $handle->process($thumb_dest);

                if ($handle->processed) {
                    $handle->clean();
                } else {
                    echo 'error : ' . $handle->error;
                }

                $data = array();
                $data['image_name'] = $handle->file_dst_name;
                $data['gallery_id'] = $_POST['id'];
                $imageModel->save($data);
            }
        } else {
            echo "Error: " . $_FILES['file']['error'];
        }

        $arr = array();

        $opts = array();
        $opts['gallery_id'] = $_POST['id'];
        $arr['images'] = $imageModel->getAll($opts);

        $this->view('ajax', $arr);
    }

    function deleteImage() {

        $imageModel = new \App\Models\Image();

        $arr = array();

        $img_arr = $imageModel->get($_POST['id']);

        $imageModel->delete($_POST['id']);

        $path = ROOT_PATH . "web/upload/" . $img_arr['image_name'];

        if (file_exists($path)) {
            unlink($path);
        }

        $this->view('ajax', $arr);
    }
    
    public function viewProduct(){
        $productModel = new \App\Models\Product();
        $imageModel = new \App\Models\Image();
        
        $arr = array();
        $arr['product'] = $productModel->get($_REQUEST['id']);

        $opts = array();
        $opts['gallery_id'] = $_REQUEST['id'];
        $arr['images'] = $imageModel->getAll($opts);
        
        $this->view($this->layout, $arr);
    }
}
