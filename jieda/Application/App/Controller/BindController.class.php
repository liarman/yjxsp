<?php
namespace App\Controller;
use Common\Controller\WapController;
/**
 * 认证控制器
 */
class BindController extends WapController{
    public function _initialize() {
		
		parent::_initialize();

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
}
