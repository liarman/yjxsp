<?php
namespace Home\Controller;

use Common\Controller\HomeBaseController;
use Think\Verify;
/**
 * 注册首页Controller
 */
class RegController extends HomeBaseController
{
    /**
     * 首页
     */
    public function selecttype()
    {
        if (IS_POST) {
            $param = I('post.');
//            $this->redirect('Home/Reg/register', ['type' => $param['type']]);
            $this->assign('type', $param['type']);
            $this->display('Reg/register');
        } else {
            $this->display();
        }
    }

    public function reg()
    {
        if (IS_POST) {
            $param = I('post.');
            $this->redirect('Reg/register', ['type' => $param['type']]);
        } else {
            $this->display();
        }
    }

    public function agreement()
    {
        $this->display();
    }
    /* 生成验证码 */
    public function verify()
    {
        $config = [
            'fontSize' => 14, // 验证码字体大小
            'length' => 4, // 验证码位数
            'imageH' => 30,
            'imageW' => 100,
            'useNoise'    =>    false
        ];
        $Verify = new Verify($config);
        $Verify->entry();
    }

    /* 验证码校验 */
    public function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        $res = $verify->check($code, $id);
        $this->ajaxReturn($res, 'json');
    }
}

