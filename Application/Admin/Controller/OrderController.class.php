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
        $countsql = "SELECT	count(o.id) AS total FROM	qfant_order o WHERE	1 = 1 ";
        $sql = "SELECT	o.*  FROM	qfant_order o	WHERE 1=1";
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
        $sql.=" limit %d,%d";
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
}
