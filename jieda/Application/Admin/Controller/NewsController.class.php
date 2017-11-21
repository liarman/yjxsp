<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class NewsController extends AdminBaseController{
	public function ajaxCatsList(){
        $data=D('Newscat')->getTreeData();
        $this->ajaxReturn($data,'JSON');
	}
	public function ajaxCatsAll(){
		$data=D('Newscat')->getTreeData('tree','','text');
		$this->ajaxReturn($data,'JSON');
	}
	/**
	 * 获取权限
	 */
	public function getCat(){
		$id=I('get.id');
		$newscat=D('Newscat')->where(array('id'=>$id))->find();
		if($newscat['pid']){
			$pcat=D('Newscat')->where(array('id'=>$newscat['pid']))->find();
			$newscat['pname']=$pcat['name'];
		}
		$this->ajaxReturn($newscat,'JSON');
	}
	/**
	 * 删除
	 */
	public function deleteCat(){
		$id=I('get.id');
		$map=array(
			'id'=>$id
		);
		$result=D('Newscat')->deleteData($map);
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
	public function editCat(){
		if(IS_POST){
			$data['id']=I('post.id');
			$data['name']=I('post.name');
			$data['sort']=I('post.sort');
			$data['pid']=I('post.pid',0);
            $data['islink']=I('post.islink',0);
            $data['linkurl']=I('post.linkurl');
			$where['id']=$data['id'];
			$result=D('Newscat')->editData($where,$data);
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
	public function addCat(){
		if(IS_POST){
			$data['name']=I('post.name');
			$data['sort']=I('post.sort');
            $data['pid']=I('post.pid',0);
            $data['islink']=I('post.islink',0);
            $data['linkurl']=I('post.linkurl');
			unset($data['id']);
			$result=D('Newscat')->addData($data);
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


	public function ajaxNewsList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$result["total"]=D('News')->count();
		$data=D('News')->query("select n.*,c.name as cname from qfant_news n left JOIN  qfant_newscat c ON n.catid=c.id  limit %d,%d" ,array($offset,$rows));
		foreach($data as $key=>$basevalue){
			$data[$key]['content']=htmlspecialchars_decode($basevalue['content']);
		}
		$result["rows"] = $data;
		$this->ajaxReturn($result,'JSON');
	}
	/**
	 * 删除
	 */
	public function deleteNews(){
		$id=I('get.id');
		$map=array(
			'id'=>$id
		);
		$result=D('News')->deleteData($map);
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
	public function editNews(){
		if(IS_POST){
			$data['id']=I('post.id');
			$data['title']=I('post.title');
			$data['logo']=I('post.logo');
			$data['content']=I('post.content');
			$data['catid']=I('post.catid');
			$data['createtime']=time();
			$where['id']=$data['id'];
			$result=D('News')->editData($where,$data);
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
	public function addNews(){
		if(IS_POST){
			$data['title']=I('post.title');
			$data['logo']=I('post.logo');
			$data['content']=I('post.content');
			$data['catid']=I('post.catid');
			$data['createtime']=time();
			unset($data['id']);
			$result=D('News')->addData($data);
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
