<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class EquipmentController extends AdminBaseController{
    public function ajaxEquipmentList(){
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $result["total"]=D('Equipment')->count();
        $data=D('Equipment')->limit($offset.','.$rows)->select();
        foreach ($data as $k=>$value){//App/Index/openinfo/id/2
            $url = "http://www.iermu.com/svideo/".$value['shareid']."/".$value['uk'];
            $data[$k]['openInfoUrl']=$url;
        }
        $result["rows"] = $data;
        $this->ajaxReturn($result,'JSON');
        /*  $Equipment=D('Equipment')->select();
          $this->assign("Equipment",$Equipment);
          $this->display();*/
    }
    /**
     * 添加
     */
    public function addEquipment(){
        if(IS_POST){
            $data['name']=I('post.name');
            $data['shareid']=I('post.shareid');
            $data['uk']=I('post.uk');
            $data['deviceid']=I('post.deviceid');
            $data['areaid']=I('post.areaid');
            unset($data['id']);
            $result=D('Equipment')->addData($data);
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
    public function editEquipment(){
        if(IS_POST){
            $data['id']=I('post.id');
            $data['name']=I('post.name');
            $data['shareid']=I('post.shareid');
            $data['uk']=I('post.uk');
            $data['deviceid']=I('post.deviceid');
            $data['areaid']=I('post.areaid');
            $where['id']=$data['id'];
            $result=D('Equipment')->editData($where,$data);
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
    public function deleteEquipment(){
        $id=I('get.id');
        $map=array(
            'id'=>$id
        );
        $result=D('Equipment')->deleteData($map);
        if($result){
            $message['status']=1;
            $message['message']='删除成功';
        }else {
            $message['status']=0;
            $message['message']='删除失败';
        }
        $this->ajaxReturn($message,'JSON');
    }
    public function cameras(){
      /*  $id=I("get.id",0);
        if($id>0){*//*->where(array('type'=>$id))*/
            $cameras=D('Equipment')->select();
            $this->assign("cameras",$cameras);
            $this->display();
        //}
    }
    public function camera(){
        $id=I("get.id",0);
        if($id>0){
            $camera=D('Equipment')->where(array('id'=>$id))->find();
            $this->assign("camera",$camera);
            $this->display();
        }
    }


}
