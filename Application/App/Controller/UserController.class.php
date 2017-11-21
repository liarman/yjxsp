<?php
namespace App\Controller;
use Common\Controller\AppBaseController;
/**
 * 认证控制器
 */
class UserController extends AppBaseController{
    private $caesar;
    public function _initialize(){
        parent::_initialize();
        Vendor('Caesar.Caesar');
        $this->caesar= new \Caesar();
    }
   
    public function userinfo(){
        $this->display();
    }
}
