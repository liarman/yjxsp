<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class RoomController extends AdminBaseController{
	public function ajaxRoomList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$name=I("post.name");
		$district_id=I("post.district_id");
		$building_id=I("post.building_id");
		$unit_id=I("post.unit_id");
		$countsql="select count(r.id) AS total from qfant_room r,qfant_unit u ,qfant_building b,qfant_district d where 1=1 AND r.unit_id=u.id AND u.building_id=b.id AND b.district_id=d.id ";
		$sql="select r.*,u.name AS uname, b.name AS bname,u.building_id,b.district_id,d.name AS dname from qfant_room r,qfant_unit u ,qfant_building b,qfant_district d where 1=1 AND r.unit_id=u.id AND u.building_id=b.id AND b.district_id=d.id ";
		$param=array();
		if(!empty($name)){
			$countsql.=" and r.name like '%s'";
			$sql.=" and r.name like '%s'";
			array_push($param,'%'.$name.'%');
		}
		if(!empty($district_id)){
			if(!empty($building_id)){
				if(!empty($unit_id)){
					$countsql.=" and r.unit_id =%d";
					$sql.=" and r.unit_id =%d";
					array_push($param,$unit_id);
				}
				$countsql.=" and u.building_id =%d";
				$sql.=" and u.building_id =%d";
				array_push($param,$building_id);
			}
			$countsql.=" and b.district_id =%d";
			$sql.=" and b.district_id =%d";
			array_push($param,$district_id);
		}
		$sql.=" limit %d,%d";
		array_push($param,$offset);
		array_push($param,$rows);
		$data=D('Room')->query($countsql,$param);
		$result['total']=$data[0]['total'];
		$data=D('Room')->query($sql,$param);
		$result["rows"] = $data;
		$this->ajaxReturn($result,'JSON');
	}
	/**
	 * 添加
	 */
	public function add(){
		if(IS_POST){
			$data['name']=I('post.name');
			$data['unit_id']=I('post.unit_id');
			$data['jzarea']=I('post.jzarea');
			$data['realarea']=I('post.realarea');
			$data['indate']=I('post.indate');
			$data['chargedate']=I('post.chargedate');
			$data['createtime']=date("Y-m-d H:i:s" ,time());
			unset($data['id']);
			$result=D('Room')->addData($data);
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
		$result=D('Room')->deleteData($map);
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
			$data['unit_id']=I('post.unit_id');
			$data['jzarea']=I('post.jzarea');
			$data['realarea']=I('post.realarea');
			$data['indate']=I('post.indate');
			$data['chargedate']=I('post.chargedate');
			$data['createtime']=date("Y-m-d H:i:s" ,time());
			$where['id']=$data['id'];
			$result=D('Room')->editData($where,$data);
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

	public function ajaxOwners(){
		$room_id=I("get.room_id");
		$data=D('RoomOwner')->where(array('room_id'=>$room_id))->select();
		$this->ajaxReturn($data,'JSON');
	}
	public function addowner(){
		if(IS_POST){
			$data['name']=I('post.name');
			$data['phone']=I('post.phone');
			$data['room_id']=I('post.room_id');
			unset($data['id']);
			if(D('RoomOwner')->where(array('phone'=>$data['phone']))->find()){
				$message['status']=3;
				$message['message']='此手机号已存在';
			}else {
				$result=D('RoomOwner')->addData($data);
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
	public function editowner(){
		if(IS_POST){
			$data['id']=I('post.id');
			$data['name']=I('post.name');
			$data['phone']=I('post.phone');
			$data['room_id']=I('post.room_id');
			$where['id']=$data['id'];
			if(D('RoomOwner')->where("phone='".$data['phone']."' and id <> ".$data['id'])->find()){
				$message['status']=3;
				$message['message']='此手机号已存在';
			}else {
				$result=D('RoomOwner')->editData($where,$data);
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
	public function deleteowner(){
		$id=I('get.id');
		$map=array(
			'id'=>$id
		);
		$result=D('RoomOwner')->deleteData($map);
		if($result){
			$message['status']=1;
			$message['message']='删除成功';
		}else {
			$message['status']=0;
			$message['message']='删除失败';
		}
		$this->ajaxReturn($message,'JSON');
	}
}
