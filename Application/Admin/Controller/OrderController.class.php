<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台权限管理
 */
class OrderController extends AdminBaseController{
    public function ajaxOrderList(){
        $goodsname=I("post.goodsname");
        $receivername=I("post.receivername");
        $shipper=I("post.shipper");
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $countsql = "SELECT	 count(o.id) AS total FROM	qfant_order o WHERE	1 = 1 ";
      //  $sql = "SELECT	o.* ,c.driver as driver ,r.name as endcityname FROM	qfant_order o left join qfant_cardrive c on o.cardriveid=c.id	LEFT JOIN qfant_route r on r.id=o.endcity WHERE 1=1";
        $sql = "SELECT	o.* ,c.driver as driver ,r.name as endcityname ,cd.number as number FROM	qfant_order o left join qfant_cardrive cd on o.cardriveid=cd.id	LEFT JOIN qfant_route r on r.id=o.endcity LEFT JOIN qfant_car  c on c.id=cd.carid where 1=1";
        $param=array();
        if(!empty($goodsname)){
            $countsql.=" and o.goodsname like '%s'";
            $sql.=" and o.goodsname like '%s'";
            array_push($param,'%'.$goodsname.'%');
        }
        if(!empty($receivername)){
            $countsql.=" and o.receivername like '%s'";
            $sql.=" and o.receivername like '%s'";
            array_push($param,'%'.$receivername.'%');
        }
        if(!empty($shipper)){
            $countsql.=" and o.shipper like '%s'";
            $sql.=" and o.shipper like '%s'";
            array_push($param,'%'.$shipper.'%');
        }
        $sql.=" limit %d,%d ";
        array_push($param,$offset);
        array_push($param,$rows);
        $data=D('Order')->query($countsql,$param);
        $result['total']=$data[0]['total'];
        $data=D('Order')->query($sql,$param);
        $result["rows"] = $data;
        $this->ajaxReturn($result,'JSON');

    }
    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $data=I('post.');
            $data['createdate']=time();
            unset($data['id']);
            $res=D('Order')->addData($data);
            $id=$res;
            $where['id']=$res;
            $data['orderno']=$this->OrdernoMethod($id,"J");
            $result=D('Order')->editData($where,$data);
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
         //  $data['orderno']=$this->OrdernoMethod($data['id'],"J");
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
        $sql ="SELECT o.id AS oid,	o.orderno,	o.shipper,	o.shippertel,	o.receivername,	o.receiveraddress,	o.receivertel,	cd.number AS number FROM	qfant_order AS o,	qfant_cardrive AS cd WHERE	o.cardriveid = cd.id AND o. STATUS = 1 AND cd.id = '$cardriveid' ";
        $data=D('Order')->query($sql,"");
        $this->ajaxReturn($data,'JSON');

    }
    public  function printorderList(){
        $ids=I('get.id');
        $arr1 = explode("@@",$ids);
        for($index=0;$index<count($arr1);$index++) {
//            $sql="select * from qfant_order where id='$arr1[$index]'";
            $data=D('Order')->where(array('id'=>$arr1[$index]))->find();
            $datap[$index]=$data;

        }
        $this->ajaxReturn($datap,'JSON');

    }
}
