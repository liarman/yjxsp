<?php
namespace Common\Controller;
use Common\Controller\BaseController;
/**
 * admin 基类控制器
 */
class AdminBaseController extends BaseController{
	/**
	 * 初始化方法
	 */
	public function _initialize(){
		//print_r(session('user'));die;
		parent::_initialize();
		if(empty(session('user'))){
			$this->redirect('/Admin/Login/index');
		}
		$auth=new \Think\Auth();
		$rule_name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		$result=$auth->check($rule_name,$_SESSION['user']['id']);
		//print_r($rule_name);die;
		if(!$result){
			$this->error('您没有权限访问',U('Admin/Login/index'));
		}
	}




}

