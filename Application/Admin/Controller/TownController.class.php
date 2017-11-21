<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class TownController extends AdminBaseController{
	public function ajaxTownList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result["total"]=D('Town')->count();

		$data=D('Town')->limit($offset.','.$rows)->select();
		foreach($data as $k=>$value){
			$data[$k]['baseinfo']=htmlspecialchars_decode($value['baseinfo']);
		}
		$result["rows"] = $data;
		$this->ajaxReturn($result,'JSON');
	}
	public function ajaxTownAll(){
		$data=D('Town')->select();
		$this->ajaxReturn($data,'JSON');
	}
	/**
	 * 添加
	 */
	public function addTown(){
		if(IS_POST){
			$data['name']=I('post.name');
			$data['longitude']=I('post.longitude');
			$data['latitude']=I('post.latitude');
			$data['baseinfo']=I('post.baseinfo');
			unset($data['id']);
			$result=D('Town')->addData($data);
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
	public function deleteTown(){
		$id=I('get.id');
		$map=array(
			'id'=>$id
		);
		$result=D('Town')->deleteData($map);
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
	public function editTown(){
		if(IS_POST){
			$data['id']=I('post.id');
			$data['name']=I('post.name');
			$data['longitude']=I('post.longitude');
			$data['latitude']=I('post.latitude');
			$data['baseinfo']=I('post.baseinfo');
			$where['id']=$data['id'];
			$result=D('Town')->editData($where,$data);
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
