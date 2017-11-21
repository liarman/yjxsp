<?php
namespace App\Controller;
use Common\Controller\AppBaseController;
/**
 * 认证控制器
 */
class MeetController extends AppBaseController{
    private $caesar;
    public function _initialize(){
    }
   
    public function index(){
        $meetid=I('get.id',0);
        $this->assign("meetid",$meetid);
        $this->display();
    }
    public function login(){
        $meetid=I('post.meetid',0);
        $username=I('post.username');
        $password=I('post.password');
        $user=D("MeetAccount")->where(array('username'=>$username,'password'=>md5($password)))->find();
        if($user){
            $meetuser=D("MeetUser")->where(array('meet_id'=>$meetid,'account_id'=>$user['id']))->find();
            if($meetuser){
                session('meetuser',$user);
                $data['status']=1;
                $data['message']='登录成功';
            }else {
                $data['status']=0;
                $data['message']='您不在此会议室参与人员中';
            }
        }else {
            $data['status']=0;
            $data['message']='账号密码不正确';
        }
        $this->ajaxReturn($data,'JSON');
    }

    public function meeting(){
        $meetuser= session('meetuser');
      //  print_r($meetuser);die;
        $meetid=I('get.meetid',0);
        if($meetuser){
            $chats=D("MeetContent")->query("select mc.*,ma.username,ma.truename from qfant_meet_content mc LEFT  JOIN qfant_meet_account ma on mc.account_id=ma.id where mc.meet_id=".$meetid);
            $this->assign("meetid",$meetid);
            $this->assign("chats",$chats);
            $this->assign("meetuser",$meetuser);
            $this->display();
        }else {
            $this->redirect("/Meet/index/id/".$meetid);
        }

    }
    public function savecontent(){
        $data['meet_id']=I('post.meet_id',0);
        $data['account_id']=I('post.account_id');
        $data['content']=I('post.content');
        $data['createtime']=time();
        $result=D("MeetContent")->add($data);
        if($result){
            $data['status']=1;
            $data['message']='发言成功';
        }else {
            $data['status']=0;
            $data['message']='发言失败';
        }
        $this->ajaxReturn($data,'JSON');
    }
}
