<?php
namespace App\Controller;
use Common\Controller\WapController;
/**
 * 认证控制器
 */
class BindController extends WapController{
    public function _initialize() {
		
		//parent::_initialize();

        $this->assign('staticFilePath',str_replace('./','/',StaticFilePath.'Static'));
    }
    public  function bind(){
        $bindingModel = D("Binding");
        $bindkeyModel = D("Bindkey");
		
        if(IS_POST){
            $key=I('post.key');
            $wecha_id=I('post.wecha_id');
            $bindk=$bindkeyModel->where(array('key'=>$key,'status'=>1))->find();
			if($wecha_id){
				 if(empty($bindk)){
                $data['status'] = 2;
                $data['message'] = "您输入的绑定秘钥不存在";
                $this->ajaxReturn($data,'JSON');
				}else {
					$binduser=$bindingModel->where(array('wecha_id'=> $wecha_id))->find();
					if($binduser){
						$data['status'] = 5;
						$data['message'] = "您已经绑定，不需要再次绑定";
						$this->ajaxReturn($data,'JSON');
					}else {
						$bindkeyModel->where(array('key'=>$key))->setField(array('status'=>0));
						$user['wecha_id']=$wecha_id;
						$user['createtime']=time();
						$user['bkey_id']=$bindk['id'];
						$bindingModel->add($user);
						$data['status'] = 1;
						$data['message'] = "绑定成功";
						$this->ajaxReturn($data,'JSON');

					}
				}
			}else {
				$data['status'] = 3;
                $data['message'] = "绑定信息错误";
                $this->ajaxReturn($data,'JSON');
			}
           
        }else {
            $wecha_id=$this->wecha_id;
            $this->assign('wecha_id',$wecha_id);
            $this->display();
        }
    }

	public function register(){
		if(IS_POST) {
			$user['wecha_id'] = I("post.wecha_id", '');
			$user['phone'] = I("post.phone", '');
			$user['username'] = I("post.username", '');
			if ($user['wecha_id'] && $user['phone'] && $user['username']) {
				$user['regtime'] = time();
				$result = D("Customer")->add($user);
				if ($result) {
					$data['status'] = 1;
					$data['message'] = '注册成功';
				} else {
					$data['status'] = 0;
					$data['message'] = '注册失败';
				}
			} else {
				$data['status'] = 0;
				$data['message'] = '注册失败';
			}
		}else {
			$data['status'] = 0;
			$data['message'] = '注册失败';
		}
		$this->ajaxReturn($data,'JSON');
	}

	public function sendAdd(){
		if(IS_POST){
			$data['wecha_id']=I("post.wecha_id");
			$data['receivername']=I("post.receivername");
			$data['receivertel']=I("post.receivertel");
			$data['receiveraddress']=I("post.receiveraddress");
			$data['createdate']=time();
			unset($data['id']);
			D("Order")->add($data);
			$res= M('Order')->field('id')->where($data)->find();
			$id=$res['id'];
			$where['id']=$res['id'];
			$data['orderno']=$this->OrdernoMethod($id,"J");
			$result=D('Order')->editData($where,$data);
				if ($result) {
					$data['status'] = 1;
					$data['message'] = '成功';
				} else {
					$data['status'] = 0;
					$data['message'] = '失败';
				}
			}else{
				$data['status'] = 0;
				$data['message'] = '失败';
			}
		$this->ajaxReturn($data,'JSON');

	}

	public function myInfoEdit(){
		if(IS_POST){
			$data=I('post.');
			$where['id']=$data['id'];
			$result=D('Customer')->editData($where,$data);
			if($result){
				$message['status']=1;
				$message['message']='保存成功';
			}else {
				$message['status']=0;
				$message['message']='保存失败';
			}
		}
		$this->ajaxReturn($message,'JSON');
	}
	public  function myInfo(){
	$d['wecha_id']=I("get.wecha_id");
	if($d) {
		$customer = M('Customer')->where($d)->find();
		$this->assign("customer",$customer);
		$this->display('myInfo');
	}
}

	public function myOrder(){
		$wecha_id=I("get.wecha_id",'');
		$this->assign("wecha_id",$wecha_id);
		$this->display();
	}
	public function ajaxMyOrder(){
		$d=I("get.wecha_id");
		$pageNo = I("get.pageNo");
		if($pageNo==0){
			$pageNo =1;
		}
		$rows = 10;
		$offset = ($pageNo-1)*$rows;
		$data=D('Order')->where(array('wecha_id'=>$d))->limit($offset.','.$rows)->select();
		foreach ($data as $key=>$basevalue){
			if($basevalue['status']=='0'){
				$data[$key]['status']='已提交订单';
			}else if($basevalue['status']=='1'){
				$data[$key]['status']='已装车';
			}else{
				$data[$key]['status']='已到站';
			}
		}
		$this->ajaxReturn($data,'JSON');
	}
	public  function mySend(){
		$this->display('mySend');
	}
	public function OrdernoMethod($id,$type){
		$time=date('YmdHis');
		$str=strval($id);
		for($i = 0; $i <strlen($str); $i++)
		{
			$time=$time."0";
		}
		$code=$type.$time.$id;
		return $code;

	}
}
