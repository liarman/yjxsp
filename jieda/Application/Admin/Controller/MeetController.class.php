<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class MeetController extends AdminBaseController{
	public function ajaxMeetList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result["total"]=D('Meet')->count();

		$data=D('Meet')->query("select m.*,t.name as tname from qfant_meet m ,qfant_town t where m.townid=t.id limit ".$offset.','.$rows);
		foreach ($data as $k=>$value){
			$data[$k]['url']=C('GLOBAL_DOMAIN_URL')."/index.php/App/Meet/index/id/".$value['id'];
		}
		$result["rows"] = $data;
		$this->ajaxReturn($result,'JSON');
	}
	/**
	 * 添加
	 */
	public function addMeet(){
		if(IS_POST){
			$data['name']=I('post.name');
			$data['intro']=I('post.intro');
			$data['townid']=I('post.townid');
			unset($data['id']);
			$result=D('Meet')->addData($data);
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
	public function deleteMeet(){
		$id=I('get.id');
		$map=array(
			'id'=>$id
		);
		$result=D('Meet')->deleteData($map);
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
	public function editMeet(){
		if(IS_POST){
			$data['id']=I('post.id');
			$data['name']=I('post.name');
			$data['intro']=I('post.intro');
			$data['townid']=I('post.townid');
			$where['id']=$data['id'];
			$result=D('Meet')->editData($where,$data);
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
	public function account(){
		$this->display();
	}
	public function ajaxAccountList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result["total"]=D('MeetAccount')->count();

		$data=D('MeetAccount')->query("select ma.*,t.name as tname from qfant_meet_account ma ,qfant_town t where ma.townid=t.id limit ".$offset.','.$rows);
		$result["rows"] = $data;
		$this->ajaxReturn($result,'JSON');
	}
	public function ajaxVillageAccountList(){
		$townid=I('get.townid');
		$meetid=I('get.meetid');
		$accounts=D('MeetAccount')->where(array('townid'=>$townid))->select();
		foreach ($accounts as $k=>$val){
			$mu=D('MeetUser')->where(array('meet_id'=>$meetid,'account_id'=>$val['id']))->find();
			$accounts[$k]['text']=$val['username'];
			if($mu){
				$accounts[$k]['checked'] = true;
			}
		}
		$this->ajaxReturn($accounts,'JSON');
	}
	/**
	 * 添加
	 */
	public function addAccount(){
		if(IS_POST){
			$data['truename']=I('post.truename');
			$data['username']=I('post.username');
			$data['password']=md5(I('post.password'));
			$data['townid']=I('post.townid');
			unset($data['id']);
			$account=D("MeetAccount")->where(array('username'=>$data['username']))->find();
			if($account){
				$message['status']=0;
				$message['message']='此账号已存在';
			}else {
				$result=D('MeetAccount')->addData($data);
				if($result){
					$message['status']=1;
					$message['message']='保存成功';
				}else {
					$message['status']=0;
					$message['message']='保存失败';
				}
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
	public function deleteAccount(){
		$id=I('get.id');
		$map=array(
			'id'=>$id
		);
		$result=D('MeetAccount')->deleteData($map);
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
	public function editAccount(){
		if(IS_POST){
			$data['id']=I('post.id');
			$data['truename']=I('post.truename');
			$data['username']=I('post.username');
			$data['password']=I('post.password');
			$data['townid']=I('post.townid');
			if($data['password']){
				$data['password']=md5($data['password']);
			}
			$where['id']=$data['id'];
			$result=D('MeetAccount')->editData($where,$data);
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
	 * 保存用户
	 */
	public function saveuser(){
		$users=I('post.users');
		$meetid=I('post.meetid');
		$users=explode(",",$users);
		D('MeetUser')->where(array('meet_id'=>$meetid))->delete();
		foreach ($users as $user){
			$data['meet_id']=$meetid;
			$data['account_id']=$user;
			D('MeetUser')->add($data);
			$message['status']=1;
			$message['message']='保存成功';
		}
		$this->ajaxReturn($message,'JSON');

	}
}
