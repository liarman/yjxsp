<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class VillageController extends AdminBaseController{
	public function ajaxVillageList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result["total"]=D('Village')->count();

		$data=D('Village')->query("select v.*,t.name as tname from qfant_village v left JOIN  qfant_town t ON v.townid=t.id  limit %d,%d" ,array($offset,$rows));
		foreach ($data as $k=>$value){//App/Index/openinfo/id/2
			$data[$k]['openInfoUrl']=C('GLOBAL_DOMAIN_URL')."/index.php/App/Index/openinfo/id/".$value['id'];
		}
		foreach($data as $key=>$basevalue){
			$data[$key]['baseinfo']=htmlspecialchars_decode($basevalue['baseinfo']);
		}
		$result["rows"] = $data;
		//print_r($result);die;
        $this->ajaxReturn($result,'JSON');
	}
	/**
	 * 删除
	 */
	public function deleteVillage(){
		$id=I('get.id');
		$map=array(
			'id'=>$id
		);
		$result=D('Village')->deleteData($map);
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
	public function editVillage(){
		if(IS_POST){
			$data['id']=I('post.id');
			$data['name']=I('post.name');
			$data['longitude']=I('post.longitude');
			$data['latitude']=I('post.latitude');
			$data['contact']=I('post.contact');
			$data['tel']=I('post.tel');
			$data['intro']=I('post.intro');
			$data['baseinfo']=I('post.baseinfo');
			$data['townid']=I('post.townid');
			$where['id']=$data['id'];
			$result=D('Village')->editData($where,$data);
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
	 * 添加
	 */
	public function addVillage(){
		if(IS_POST){
			$data['name']=I('post.name');
			$data['longitude']=I('post.longitude');
			$data['latitude']=I('post.latitude');
			$data['contact']=I('post.contact');
			$data['tel']=I('post.tel');
			$data['intro']=I('post.intro');
			$data['baseinfo']=I('post.baseinfo');
			$data['townid']=I('post.townid');
			unset($data['id']);
			$result=D('Village')->addData($data);
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


	public function ajaxOpeninfos(){
		$vilage_id=I("get.village_id");
		$data=D('Openinfo')->where(array('villageid'=>$vilage_id))->select();
		$this->ajaxReturn($data,'JSON');
	}
	public function addopeninfo(){
		if(IS_POST){
			$data['name']=I('post.name');
			$data['idcard']=I('post.idcard');
			$data['sex']=I('post.sex');
			$data['nation']=I('post.nation');
			$data['birthday']=I('post.birthday');

			$data['educational']=I('post.educational');
			$data['personalkind']=I('post.personalkind');
			$data['partybranch']=I('post.partybranch');

			$data['jointime']=I('post.jointime');
			$data['turntime']=I('post.turntime');
			$data['position']=I('post.position');

			$data['address']=I('post.address');
			$data['phone']=I('post.phone');
			$data['tel']=I('post.tel');
			$data['outwardflow']=I('post.outwardflow');

			$data['villageid']=I('post.village_id');
			unset($data['id']);
			$result=D('Openinfo')->addData($data);
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
	public function editopeninfo(){
		if(IS_POST){
			$data['id']=I('post.id');
			$data['name']=I('post.name');
			$data['idcard']=I('post.idcard');
			$data['sex']=I('post.sex');
			$data['nation']=I('post.nation');
			$data['birthday']=I('post.birthday');

			$data['educational']=I('post.educational');
			$data['personalkind']=I('post.personalkind');
			$data['partybranch']=I('post.partybranch');

			$data['jointime']=I('post.jointime');
			$data['turntime']=I('post.turntime');
			$data['position']=I('post.position');

			$data['address']=I('post.address');
			$data['phone']=I('post.phone');
			$data['tel']=I('post.tel');
			$data['outwardflow']=I('post.outwardflow');
			$data['villageid']=I('post.village_id');
			$where['id']=$data['id'];
			$result=D('Openinfo')->editData($where,$data);
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
	public function deleteopeninfo(){
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
