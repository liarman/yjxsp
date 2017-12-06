<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台权限管理
 */
class ReceiveController extends AdminBaseController{
    public function receiveList(){
        $shipperid=I('get.shipperid');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        if($shipperid) {
            $countsql = "select count(id) AS total from qfant_receive where shipperid='$shipperid'";
            $sql = "select * from qfant_receive where shipperid='$shipperid'";
        }
        $param=array();
        array_push($param,$offset);
        array_push($param,$rows);
        $sql.=" limit %d,%d";
        $data=D('Receive')->query($countsql,$param);
        $result['total']=$data[0]['total'];
        $data=D('Receive')->query($sql,$param);
        $result["rows"] = $data;
        $this->ajaxReturn($result,'JSON');

    }
    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $data=I('post.');
            unset($data['id']);
            $result=D('Order')->addData($data);
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
    public function edit(){
        if(IS_POST){
            $data=I('post.');
            $where['id']=$data['id'];
            $result=D('Order')->editData($where,$data);
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
     *
     * 查看
     */
    public  function look(){
        $data=I('get.');
        $id=$data['id'];
        $sql="select * from qfant_order where id='$id'";
        $data=D('Order')->query($sql,"");
        $data['time']=time();

        $this->ajaxReturn($data,'JSON');
    }

    /**
     * 删除
     */
    public function delete(){
        $id=I('get.id');
        $map=array(
            'id'=>$id
        );
        $result=D('Order')->deleteData($map);
        if($result){
            $message['status']=1;
            $message['message']='删除成功';
        }else {
            $message['status']=0;
            $message['message']='删除失败';
        }
        $this->ajaxReturn($message,'JSON');
    }

    public function addCarOrder(){
        $data['cardriveid']=I('get.id');//发车id
        $data['id']=I("get.orderid");//订单id
        $data['assembledate']=time();
        $data['status']='1';//已装车
        $where['id']=$data['id'];
        $result=D('Order')->editData($where,$data);
        if($result){
            $message['status']=1;
            $message['message']='装车成功';
        }else {
            $message['status']=0;
            $message['message']='装车失败';
        }
        $this->ajaxReturn($message,'JSON');
    }

    public function orderList(){
        $cardriveid=I('get.id');//发车id
        $sql ="select o.id as oid,o.orderno ,o.shipper,o.shippertel,o.receivername,o.receiveraddress,o.receivertel,r.name as rname from qfant_order as o ,qfant_cardrive as cd ,qfant_route as r where o.cardriveid=cd.id and o.status=1 and r.id=o.endcity and cd.id='$cardriveid'";
        $data=D('Order')->query($sql,"");
        $this->ajaxReturn($data,'JSON');

    }
}
