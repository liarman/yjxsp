<?php
namespace App\Controller;

use Common\Controller\AppBaseController;

/**
 * 认证控制器
 */
class CustomerController extends AppBaseController
{
    private $caesar;

    public function _initialize()
    {
        parent::_initialize();
        Vendor('Caesar.Caesar');
        $this->caesar = new \Caesar();
    }

    /**传参  phone  password  bluemac
     * 登录接口
     */

    public function Login()
    {
        $key = I("post.key");
        $b = I("post.b");
        if($key && $b){
            $b = $this->caesar->clientDecode($key, $b);
            $param = json_decode($b,true);
            $phone =$param['phone'];
            $pass = $param['password'];
            $bluemac = $param['bluemac'];
            $token = uniqid();
            $user = D("Account")->where(array('username' => $phone, 'password' => md5($pass), 'bluetooth' => $bluemac))->find();
            $res['customer_Id'] = $user['id'];
            $res['loginIden'] = $token;
            $res['loginTime'] = date("Y-m-d H:i:s", time());
            if ($user) {
                $login = D("LoginLogger")->where(array('customer_Id' => $user['id']))->find();
                if ($login) {
                    D("LoginLogger")->where(array('customer_Id' => $user['id']))->save($res);
                    $data['bstatus']['code'] = C('APP_STATUS.STATUS_CODE_SUCCESS');
                    $data['bstatus']['des'] = '登录成功';
                    $data['data']['token'] = $token;
                    $data['data']['userId'] = $user['id'];
                    $data['data']['phone'] = $user['phone'];
                }else{
                    D("LoginLogger")->add($res);
                    $data['bstatus']['code'] = C('APP_STATUS.STATUS_CODE_SUCCESS');
                    $data['bstatus']['des'] = '登录成功';
                    $data['data']['token'] = $token;
                    $data['data']['userId'] = $user['id'];
                    $data['data']['phone'] = $user['phone'];
                }
            }else {
                $data['bstatus']['code'] = C('APP_STATUS.STATUS_CODE_NOT_LOGIN');
                $data['bstatus']['des'] = '登录失败';
                $data['data'] = '';
            }
           echo $this->caesar->clientEncode($key, json_encode($data));
      }
    }

    /**
     * 退出登录接口
     */

    public function logout(){
            $key = I("post.key");
            $b = I("post.b");
        if($key && $b){
            $b = $this->caesar->clientDecode($key, $b);
            $param = json_decode($b,true);
            D("LoginLogger")->where(array('customer_Id' => $param['cparam']['userId']))->delete();
            $data['bstatus']['code'] = 0;
            $data['bstatus']['des'] = '退出成功！';
            $data['data'] = '';

            echo $this->caesar->clientEncode($key, json_encode($data));
        }

    }

    /**
     * 轮播图接口
     *
     */
    public function links(){
        if (IS_POST) {
            $key = I("post.key");
            $b = I("post.b");
            if ($key && $b) {
                    $sql = "select * from qfant_link  n  where 1=1";
                    $link = D('Link')->query($sql,"");
                    $data['bstatus']['code'] = 0;
                    $data['bstatus']['des'] = '获取成功';
                    $data['data']['linkResult'] = $link;
                    echo $this->caesar->clientEncode($key, json_encode($data));

            }
        }

    }

    /**
     * 商品分类接口
     * 传参：pageNo  pageSize
     */
    public function category(){
		if (IS_POST) {
            $key = I("post.key");
            $b = I("post.b");
            if ($key && $b) {
                $categorys = D('Category')->select();
				foreach($categorys as $k=>$val){
					$categorys[$k]['products']=D("Product")->field("id,name,pic1,price")->where(array('category_id'=>$val['id']))->select();
				}	
                $data['bstatus']['code'] = 0;
                $data['bstatus']['des'] = '获取成功';
                $data['data']['categoryResult'] = $categorys;

                echo $this->caesar->clientEncode($key, json_encode($data));

            }
        }
    }
    /**
     * 商品详情
     * 传参 id
     */
     public function  product(){
        if (IS_POST) {
             $key = I("post.key");
             $b = I("post.b");
             if ($key && $b) {
                 $b = $this->caesar->clientDecode($key, $b);
                 $param = json_decode($b, true);
                 $id=$param['id'];
                 $product = D('product')->where(array('id'=>$id))->find();
                 $data['bstatus']['code'] = 0;
                 $data['bstatus']['des'] = '获取成功';
                 $data['data']['productResult'] = $product;

                 echo $this->caesar->clientEncode($key, json_encode($data));
             }
         }

     }

    /**
     * 员工风采列表
     * 传参：pageNo  pageSize
     */
     public  function fengcai(){
         if (IS_POST) {
             $key = I("post.key");
             $b = I("post.b");
             if ($key && $b) {
                 $b = $this->caesar->clientDecode($key, $b);
                 $param = json_decode($b, true);
                 if($param['pageNo'] < 1){
					$param['pageNo'] = 1;
				}else{
					$pageNo = $param['pageNo'] ;
				}
                 $pageSize = $param['pageSize'];
                 $offset = ($pageNo - 1) * $pageSize;
                 $company_id=1;//$_SESSION['user']['company_id'];
                 $sql = "select * from qfant_fengcai  n  where 1=1 and  n.district_id='$company_id' ";
                 $param = array();
                 $sql .= " limit %d,%d";
                 array_push($param, $offset);
                 array_push($param, $pageSize);
                 $fengcai = D('Fengcai')->query($sql, $param);

                 $data['bstatus']['code'] = 0;
                 $data['bstatus']['des'] = '获取成功';
                 $data['data']['fengcaiResult'] = $fengcai;

                 echo $this->caesar->clientEncode($key, json_encode($data));

             }
         }

     }

    /**
     * 员工风采详情表
     * 传参 id
     */
    public  function fengcaiDetial(){
        if (IS_POST) {
            $key = I("post.key");
            $b = I("post.b");
            if ($key && $b) {
                $b = $this->caesar->clientDecode($key, $b);
                $param = json_decode($b, true);
                $fengcaidetial=D("Fengcai")->where(array('id'=> $param['id']))->select();
                $data['bstatus']['code'] = 0;
                $data['bstatus']['des'] = '获取成功';
                $data['data']['fengcaidetialResult'] = $fengcaidetial;

                echo $this->caesar->clientEncode($key, json_encode($data));

           }
        }
    }

    /**
     * 园区风采列表
     * 传参：pageNo  pageSize
     */
    public  function  news(){
      if (IS_POST) {
            $key = I("post.key");
            $b = I("post.b");
            if ($key && $b) {
                $b = $this->caesar->clientDecode($key, $b);
                $param = json_decode($b, true);
                if($param['pageNo'] < 1){
					$param['pageNo'] = 1;
				}else{
					$pageNo = $param['pageNo'] ;
				}
                $pageSize = $param['pageSize'];
                $offset = ($pageNo - 1) * $pageSize;
                $company_id=$_SESSION['user']['company_id'];
                $sql = "select * from qfant_news  n  where 1=1 and  n.district_id='$company_id' ";
                $param = array();
                $sql .= " limit %d,%d";
                array_push($param, $offset);
                array_push($param, $pageSize);
                $news = D('News')->query($sql, $param);

                $data['bstatus']['code'] = 0;
                $data['bstatus']['des'] = '获取成功';
                $data['data']['newsResult'] = $news;

                echo $this->caesar->clientEncode($key, json_encode($data));

            }
        }
    }

    /**
     * 园区风采详情
     * 传参 id
     */
    public function  newsDetial(){
       if (IS_POST) {
            $key = I("post.key");
            $b = I("post.b");
            if ($key && $b) {
                $b = $this->caesar->clientDecode($key, $b);
                $param = json_decode($b, true);
                $newsdetial=D("News")->where(array('id'=> $param['id']))->select();
                $data['bstatus']['code'] = 0;
                $data['bstatus']['des'] = '获取成功';
                $data['data']['newsdetialResult'] = $newsdetial;

               echo $this->caesar->clientEncode($key, json_encode($data));

            }
        }
    }
}
