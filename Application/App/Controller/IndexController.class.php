<?php
namespace App\Controller;
use Common\Controller\AppBaseController;
/**
 * 认证控制器
 */
class IndexController extends AppBaseController{
    private $caesar;
    public function _initialize(){
        parent::_initialize();
        Vendor('Caesar.Caesar');
        $this->caesar= new \Caesar();
    }
    /**
     * 个人中心首页
     */
    public function index(){

       // var_dump($caesar->clientEncode('F4C67DBE892A0351','{"cparam":{"imei":"864587029583557","versionCode":1,"versionName":"1.0.0"},"phone":"13212345678","verificationCode":"123456","room_id":1,"name":"测试"}'));
      //  var_dump( $this->caesar->clientDecode('F4C67DBE892A0351','0839FAD9B53AC1C925908F4FB7C0D9E99B9E660ACCE8EC021998BAE7CA01F524B1906688DA36CCF5A341085D91CCD3E49B9E7C88DA36CCF5A341085D90C6DAE49B9E6600D7E4B60F258A344F16C1DDED31397F84C5E5E904159CB2E4C504F2BB95391ECF1A52C8CC43478257BEC2E5E03C426606DAEFE90A1D9DBB4FD28E7CE03F483DCDB14AEA042B360D52BBCA4C219B23C2755E113C95AE'));
       // $this->display();
    }
    public function towns(){
       // if(IS_POST){
		   $key=I("post.key");
            $b=I("post.b");
          $towns=D("Town")->field('id,name,latitude,longitude,contact,mobile')->where(array('status'=>1))->select();
			foreach($towns as $k=>$val){
				$villages=D("Village")->field('id,name,tel,contact,latitude,longitude,type,intro')->where(array('townid'=>$val['id']))->select();
				 foreach($villages as $k2=>$val2){
					$villages[$k2]['principal']=$val['contact'];//负责人
					$villages[$k2]['mobile']=$val['mobile'];//负责人电话
					$villages[$k2]['boundary']=D("VillageBoundary")->where(array('villageid'=>$val2['id']))->select();
				}	
				$towns[$k]['children']=$villages;
                $towns[$k]['boundary']=D("TownBoundary")->where(array('townid'=>$val['id']))->select();
			}
            $data['bstatus']['code']=0;
            $data['bstatus']['des']='获取成功';
            $data['data']=$towns;
			//print_r(json_encode($data));die;
            echo  $this->caesar->clientEncode($key,json_encode($data));			
      //  }
    }
    public function villageInfo(){
        if(IS_POST){
            $key=I("post.key");
            $b=I("post.b");
            $b=$this->caesar->clientDecode($key,$b);
            $param=json_decode($b,true);
            $village=D("Village")->where(array('id'=>$param['id']))->find();
            $town=D("Town")->where(array('id'=>$village['townid']))->find();
			$village['manager']=  $town['name'];
            $data['bstatus']['code']=0;
            $data['bstatus']['des']='获取成功';
            $data['data']=$village;
            echo  $this->caesar->clientEncode($key,json_encode($data));
        }
    }
	 public function villages(){
        if(IS_POST){
            $key=I("post.key");
            $b=I("post.b");
            $b=$this->caesar->clientDecode($key,$b);
            $param=json_decode($b,true);
            $town=D("Town")->where(array('id'=>$param['id']))->find();
            $villages=D("Village")->where(array('townid'=>$param['id']))->select();
            foreach($villages as $k=>$val){
                $villages[$k]['principal']=$town['contact'];//负责人
                $villages[$k]['mobile']=$town['mobile'];//负责人电话
                $villages[$k]['boundary']=D("VillageBoundary")->where(array('villageid'=>$val['id']))->select();
            }
            $data['bstatus']['code']=0;
            $data['bstatus']['des']='获取成功';
            $data['data']=$villages;
            echo  $this->caesar->clientEncode($key,json_encode($data));
        }
    }
	 public function equipments(){
        if(IS_POST){
            $key=I("post.key");
            $b=I("post.b");
            $b=$this->caesar->clientDecode($key,$b);
            $param=json_decode($b,true);
            $equipments=D("Equipment")->where(array('type'=>$param['townid']))->select();
            $data['bstatus']['code']=0;
            $data['bstatus']['des']='获取成功';
            $data['data']=$equipments;
            echo  $this->caesar->clientEncode($key,json_encode($data));
        }
    }
	public function equipment(){
        if(IS_POST){
            $key=I("post.key");
            $b=I("post.b");
            $b=$this->caesar->clientDecode($key,$b);
            $param=json_decode($b,true);
            $equipment=D("Equipment")->where(array('id'=>$param['id']))->find();
			
			
			$param['method']='liveplay';
			$param['deviceid']=$equipment['deviceid'];
			$param['shareid']=$equipment['shareid'];
			$param['uk']=$equipment['uk'];
			$param['type']='rtmp';
			if($param['deviceid'] && $param['shareid'] && $param['uk']){
				$data=http("https://api.iermu.com/v2/pcs/device",$param);
				$data=json_decode($data,true);
				$equipment['rtmp']=$data['url'];
				$equipment['status']=$data['status'];
				if($equipment['status']==0){
					$data['bstatus']['code']=-2;
					$data['bstatus']['des']='设备已离线或取消分享';
				}else {
					$data['bstatus']['code']=0;
					$data['bstatus']['des']='获取成功';
					$data['data']=$equipment;	
				}				
			}else {
				$data['bstatus']['code']=-1;
				$data['bstatus']['des']='设备号为空,暂时无法播放';
			}
            echo  $this->caesar->clientEncode($key,json_encode($data));
        }
    }
    public function consult(){
        if(IS_POST){
            $key=I("post.key");
            $b=I("post.b");
            $b=$this->caesar->clientDecode($key,$b);
            $param=json_decode($b,true);
            $consult['villageid']=$param['villageid'];
            $consult['content']=$param['content'];
            $result=D("VillageConsult")->add($consult);
            if($result){
                $data['bstatus']['code']=0;
                $data['bstatus']['des']='添加成功';
            }else {
                $data['bstatus']['code']=-1;
                $data['bstatus']['des']='添加失败';
            }
            echo  $this->caesar->clientEncode($key,json_encode($data));
        }
    }
	public function weather(){
        if(IS_POST){
            $key=I("post.key");
            $b=I("post.b");
            $b=$this->caesar->clientDecode($key,$b);
            $param=json_decode($b);
			$w=http("https://api.seniverse.com/v3/weather/now.json?key=fguvd3ahk6eneabr&location=Bozhou&language=zh-Hans&unit=c");
			$w=json_decode($w);
            $data['bstatus']['code']=0;
            $data['bstatus']['des']='获取成功';
            $data['data']=$w->results;
			//print_r( json_encode($data));die;
            echo  $this->caesar->clientEncode($key,json_encode($data));
        }
    }
	public function news(){
		$catid=I("get.catid",0);
		if($catid>0){
			$cat=D('Newscat')->field('name')->where(array('id'=>$catid))->find();
            $catname=$cat['name'];
			$cats=D('Newscat')->where(array('pid'=>$catid))->order("sort desc")->select();
            $this->assign("catname",$catname);
            if(empty($cats)){
                $news=D('News')->where(array('catid'=>$catid))->select();
                $this->assign("news",$news);
                $this->display("newslist");
            }else {
				foreach($cats as $k=>$val){
					$cats[$k]['name']=htmlspecialchars_decode($val['name']);//负责人
				}
                $this->assign("cats",$cats);
                $this->display();
            }
		}else {
			$catname='新闻列表';
			$cats=D('Newscat')->where(array('pid'=>0))->order("sort desc")->select();
			foreach($cats as $k=>$val){
				$cats[$k]['name']=htmlspecialchars_decode($val['name']);//负责人
			}
            $this->assign("cats",$cats);
            $this->assign("catname",$catname);
            $this->display();
		}
	}
    public function baseinfo(){
        $id=I("get.id",0);
        if($id>0){
            $town=D('Town')->where(array('id'=>$id))->find();
            $town['baseinfo']=htmlspecialchars_decode($town['baseinfo']);
            $villages=D('Village')->where(array('townid'=>$id))->select();
            $this->assign("villages",$villages);
            $this->assign("town",$town);
            $this->display();
        }
    }
    public function village(){
        $id=I("get.id",0);
        $type=I("get.type",0);
        if($id>0){
            $village=D('Village')->where(array('id'=>$id))->find();
            $village['baseinfo']=htmlspecialchars_decode($village['baseinfo']);
            $village['appearance']=htmlspecialchars_decode($village['appearance']);
            $village['activity']=htmlspecialchars_decode($village['activity']);
            $village['dyactivity']=htmlspecialchars_decode($village['dyactivity']);
            $this->assign("village",$village);
            $this->assign("type",$type);
//            print_r($village);die;
            $this->display();
        }
    }
	public function newsdetail(){
        $id=I("get.id",0);
        if($id>0){
            $news=D('News')->where(array('id'=>$id))->find();
            $news['content']=htmlspecialchars_decode($news['content']);
            $this->assign("news",$news);
           // print_r($news);die;
            $this->display();
        }
    }
    public function cameras(){
        $id=I("get.id",0);
        if($id>0){
            $cameras=D('Equipment')->where(array('type'=>$id))->select();
            $this->assign("cameras",$cameras);
            $this->display();
        }
    }
    public function camera(){
        $id=I("get.id",0);
        if($id>0){
            $camera=D('Equipment')->where(array('id'=>$id))->find();
            $this->assign("camera",$camera);
            $this->display();
        }
    }

    public function process(){
        $id=I("get.id",0);
        if($id>0){
            $process=D('Progress')->where(array('openinfoid'=>$id))->select();
            $this->assign("process",$process);
            //print_r($process);die;
            $this->display();
        }
    }

    /**
     * openinfo
     * 党支部管理的公开信息
     */
  /*  public function openinfo1(){
        $vilage_id=I("get.id",0);
        if($vilage_id>0){
            $openinfo=D('Openinfo')->where(array('villageid'=>$vilage_id))->select();
            $this->assign("openinfo",$openinfo);
            $this->display();
        }
    }*/
    public function openinfo(){
        $id=I("get.id",0);
        if($id>0){
            $param = array();
            array_push($param,$id);
            $sql = " select v.name as vname,o.* from qfant_village v  LEFT JOIN qfant_openinfo o ON o.villageid=v.id where  o.villageid=%d";
            $openinfo=D('Openinfo')->query($sql,$param);
            $this->assign("openinfo",$openinfo);
            $this->display();
        }

    }

    /**
     * lookOpeninfo
     * 查看党支部个人的公开信息
     */
    public function lookOpeninfo(){
        $id=I("get.id",0);
        if($id>0){
            $lookOpenInfo=D('Openinfo')->where(array('id'=>$id))->find();
            $this->assign("lookOpenInfo",$lookOpenInfo);
            $this->display();
        }
    }
    public function groupindex(){
        $this->display();
    }
    public function standard(){
        $this->display();
    }
    public function grouplife(){
        $this->display();
    }
	public function companys(){
		$townid=I("get.townid",0);
        $villages=D("Village")->where(array('townid'=>$townid))->select();
		$this->assign("villages",$villages);
		$this->display();
    }
}
