<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class CarController extends AdminBaseController{
    public function index(){
        $this->display();
    }
    public function ajaxCarList(){
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $result["total"]=D('Car')->count();
        $data=D('Car')->limit($offset.','.$rows)->select();
        $result["rows"] = $data;
        $result["status"] = 1;
        $this->ajaxReturn($result,'JSON');
    }
    /**
     * 添加
     */
    public function addCar(){
        if(IS_POST){
            $data['driver']=I('post.driver');
            $data['carnumber']=I('post.carnumber');
            $data['status']=I('post.status');
            unset($data['id']);
            $result=D('Car')->addData($data);

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
     * 编辑
     */
    public function editCar(){
        if(IS_POST){
            $data['id']=I('post.id');
            $data['driver']=I('post.driver');
            $data['carnumber']=I('post.carnumber');
            $data['status']=I('post.status');
            //print_r($data);die;
            $where['id']=$data['id'];
            $result=D('Car')->editData($where,$data);
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

    /**
     * 删除
     */
    public function deleteCar(){
        $id=I('get.id');
        $map=array(
            'id'=>$id
        );
        $result=D('Car')->deleteData($map);
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
     * 启用
     */
    public function usingCar(){
        $data['id']=I('get.id');
        $status= I('get.status');
        if($status == 1){
            $message['status']=0;
            $message['message']='已经启用，请勿再次启用！';
        }else{
            $data['status'] = 1;
            $where['id']=$data['id'];
            $result=D('Car')->editData($where,$data);
            if($result){
                $message['status']=1;
                $message['message']='启用成功';
            }else {
                $message['status']=0;
                $message['message']='启用失败';
            }
        }

        $this->ajaxReturn($message,'JSON');
    }

    /**
     * 禁用
     */
    public function forbiddenCar(){
        $data['id']=I('get.id');
        $status= I('get.status');
        if($status == 0){
            $message['status']=0;
            $message['message']='已经停用，请勿再次停用！';
        }else{
            $data['status'] = 0;
            $where['id']=$data['id'];
            $result=D('Car')->editData($where,$data);
            if($result){
                $message['status']=1;
                $message['message']='停用成功';
            }else {
                $message['status']=0;
                $message['message']='停用失败';
            }
        }

        $this->ajaxReturn($message,'JSON');
    }


}
