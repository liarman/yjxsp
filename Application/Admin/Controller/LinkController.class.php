<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class LinkController extends AdminBaseController{
	public function ajaxLinkList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result["total"]=D('Link')->count();

		$data=D('Link')->limit($offset.','.$rows)->select();
		$result["rows"] = $data;
		$this->ajaxReturn($result,'JSON');
	}
	/**
	 * 添加
	 */
	public function add(){
		if(IS_POST){
			$data['title']=I('post.title');
			$data['imgurl']=I('post.imgurl');
			$data['link']=I('post.link');
			$data['sort']=I('post.sort');
			$data['type']=I('post.type');
			unset($data['id']);
			$result=D('Link')->addData($data);
			if($result){
				$message['status']=1;
				$message['message']='保存成功';
			}else {
				$message['status']=0;
				$message['message']='保存失败';
			}
		}else {
			$message['status']=0;
			$message['message']='保存失败';
		}
		$this->ajaxReturn($message,'JSON');
	}
	/**
	 * 删除
	 */
	public function delete(){
		$id=I('get.id');
		$map=array(
			'id'=>$id
		);
		$result=D('Link')->deleteData($map);
		if($result){
			$message['status']=1;
			$message['message']='删除成功';
		}else {
			$message['status']=0;
			$message['message']='删除失败';
		}
		$this->ajaxReturn($message,'JSON');
	}
	/**
	 * 添加
	 */
	public function edit(){
		if(IS_POST){
			$data['id']=I('post.id');
			$data['title']=I('post.title');
			$data['imgurl']=I('post.imgurl');
			$data['link']=I('post.link');
			$data['sort']=I('post.sort');
			$data['type']=I('post.type');
			$where['id']=$data['id'];
			$result=D('Link')->editData($where,$data);
			if($result){
				$message['status']=1;
				$message['message']='保存成功';
			}else {
				$message['status']=0;
				$message['message']='保存失败';
			}
		}else {
			$message['status']=0;
			$message['message']='保存失败';
		}
		$this->ajaxReturn($message,'JSON');
	}
}
