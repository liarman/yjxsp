<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台首页控制器
 */
class UserController extends AdminBaseController{

	/**
	 * 用户列表
	 */
	public function index(){
		$word=I('get.word','');
		if (empty($word)) {
			$map=array();
		}else{
			$map=array(
				'username'=>$word
				);
		}
		$assign=D('Users')->getAdminPage($map,'register_time desc');
		$this->assign($assign);
		$this->display();
	}

	/**
	 * 修改面
	 */
	public function setPassword(){
		$map=I('post.');

		$logindata['password2']=$map['password2'];
		$logindata['password']=$map['password'];

		if($logindata['password2']!=$logindata['password']){
			$message['status']=0;
			$message['message']='两次密码输入不一样';
		}else {
			$where['id']=$map['id'];
			$loginda['password']=md5($logindata['password']);
			$Model = M(); // 实例化一个空对象
			$Model->startTrans(); // 开启事务
			$result= $Model-> table('qfant_users')->where(array('id'=>$where['id']))->save($loginda);
			$Model->commit();
			if($result){
				$message['status']=1;
				$message['message']='修改密码成功';
			}else {
				$message['status']=0;
				$message['message']='修改密码失败';
			}
			$this->ajaxReturn($message,'JSON');
		}
	}



}
