<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class DistrictController extends AdminBaseController{
	public function ajaxDistrictList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result["total"]=D('District')->count();

		$data=D('District')->limit($offset.','.$rows)->select();
		foreach ($data as $key=>$basevalue){
			$data[$key]['contact']=htmlspecialchars_decode($basevalue['contact']);
		}
		$result["rows"] = $data;
		$this->ajaxReturn($result,'JSON');
	}
	public function ajaxDistrictAll(){
		$data=D('District')->select();
		$this->ajaxReturn($data,'JSON');
	}
	/**
	 * 添加
	 */
	public function add(){
		if(IS_POST){
			$data['name']=I('post.name');
			$data['contact']=I('post.contact');
			/*$data['wuyefee']=I('post.wuyefee');*/
			unset($data['id']);
			$result=D('District')->addData($data);
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
		$result=D('District')->deleteData($map);
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
			$data['name']=I('post.name');
            $data['contact']=I('post.contact');
			/*$data['wuyefee']=I('post.wuyefee');*/
			$where['id']=$data['id'];
			$result=D('District')->editData($where,$data);
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
