<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class ActivityController extends AdminBaseController{


        public function ajaxActivityList(){

            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $offset = ($page-1)*$rows;
            $result["total"]=D('Fengcai')->count();
            $data=D('Fengcai')->limit($offset.','.$rows)->select();
            $result["rows"] = $data;
            $this->ajaxReturn($result,'JSON');
        }

    /**
     * 删除
     */
    public function deleteActivity(){
        $id=I('get.id');
        $map=array(
            'id'=>$id
        );
        $result=D('Fengcai')->deleteData($map);
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
     * 修改
     */
    public function editActivity(){
        if(IS_POST){
            $data['id']=I('post.id');
            $data['title']=I('post.title');
            $data['intro']=I('post.intro');
            $where['id']=$data['id'];
            //print_r($data);die;
            $result=D('Fengcai')->editData($where,$data);
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
     * 添加
     */
    public function addActivity(){
        if(IS_POST){
            $data['title']=I('post.title');
            $data['intro']=I('post.intro');
            unset($data['id']);
            $result=D('Fengcai')->addData($data);

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



    public function editopeninfo(){
        if(IS_POST){
            $data['id']=I('post.id');
            $data['title']=I('post.title');
            $data['intro']=I('post.intro');

            $where['id']=$data['id'];
            $result=D('Fengcai')->editData($where,$data);
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
     * 删除
     */
    public function deleteopeninfo(){
        $id=I('get.id');
        $map=array(
            'id'=>$id
        );
        $result=D('Openinfo')->deleteData($map);
        if($result){
            $message['status']=1;
            $message['message']='删除成功';
        }else {
            $message['status']=0;
            $message['message']='删除失败';
        }
        $this->ajaxReturn($message,'JSON');
    }
    public function ajaxActivityAll(){
        $townid=I("get.townid");
        $data=D('Activity')->where(array('townid'=>$townid))->select();
        $this->ajaxReturn($data,'JSON');
    }
    public function ajaxBoundary(){
        $vilage_id=I("get.village_id");
        $data=D('ActivityBoundary')->where(array('villageid'=>$vilage_id))->select();
        $this->ajaxReturn($data,'JSON');
    }
    public function addActivityBoundary(){
        if(IS_POST){
            $data['longitude']=I('post.longitude');
            $data['latitude']=I('post.latitude');
            $data['villageid']=I('post.villageid');
            unset($data['id']);
            $result=D('ActivityBoundary')->addData($data);
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
    public function editActivityBoundary(){
        if(IS_POST){
            $data['id']=I('post.id');
            $data['longitude']=I('post.longitude');
            $data['latitude']=I('post.latitude');
            $data['villageid']=I('post.villageid');
            $where['id']=$data['id'];
            $result=D('ActivityBoundary')->editData($where,$data);
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
     * 删除
     */
    public function deleteActivityBoundary(){
        $id=I('get.id');
        $map=array(
            'id'=>$id
        );
        $result=D('ActivityBoundary')->deleteData($map);
        if($result){
            $message['status']=1;
            $message['message']='删除成功';
        }else {
            $message['status']=0;
            $message['message']='删除失败';
        }
        $this->ajaxReturn($message,'JSON');
    }


    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle.date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function impUser(){
        $id = I('get.id');
        if (!empty($_FILES)) {
            $upload = new \Think\Upload();// 实例化上传类
            $filepath='./Upload/excel/';
            $upload->exts = array('xlsx','xls');// 设置附件上传类型
            $upload->rootPath  =  $filepath; // 设置附件上传根目录
            $upload->saveName  =     'time';
            $upload->autoSub   =     false;
            if (!$info=$upload->upload()) {
                $this->error($upload->getError());
            }
            foreach ($info as $key => $value) {
                unset($info);
                $info[0]=$value;
                $info[0]['savepath']=$filepath;
            }
            vendor("PHPExcel.PHPExcel");
            $file_name=$info[0]['savepath'].$info[0]['savename'];
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumn = $sheet->getHighestColumn(); // 取得总列数
            for($i=2;$i<=$highestRow;$i++)
            {
                $data['name']  = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
                $data['idcard']  = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
                $data['sex']  = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
                $data['nation']  = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
                $data['birthday']  = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
                $data['educational']  = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
                $data['partybranch']  = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
                $data['jointime']  = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
                $data['turntime']  = $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
                $data['address']  = $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
                $data['phone']  = $objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();
                $data['tel']  = $objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue();
                //print_r($data);die;
                $data['villageid']  = $id;
                M('openinfo')->where(array('villageid'=>$id))->add($data);
                $message['status']=1;
                $message['message']='导入完成，保存成功!';

            }
        }else
        {
            $message['status']=0;
            $message['message']='请选择上传文件！';
        }

        $this->ajaxReturn($message,'JSON');

    }

    private function _excelTime($date, $time = false) {
        if (function_exists('GregorianToJD')) {
            if (is_numeric($date)) {
                $jd = GregorianToJD(1, 1, 1970);
                $gregorian = JDToGregorian($jd + intval($date) - 25569);
                $date = explode('/', $gregorian);
                $date_str = str_pad($date[2], 4, '0', STR_PAD_LEFT) . "-" . str_pad($date[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date[1], 2, '0', STR_PAD_LEFT) . ($time ? " 00:00:00" : '');
                return $date_str;
            }
        } else {
            $date = $date > 25568 ? $date + 1 : 25569; /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
            $ofs = (70 * 365 + 17 + 2) * 86400;
            $date = date("Y-m-d", ($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
        }
        return $date;
    }

    function addProgress(){
        if(IS_POST) {
            $data['status1'] = I('post.status1');
            $data['status2'] = I('post.status2');
            $data['status3'] = I('post.status3');
            $data['status4'] = I('post.status4');
            $data['status5'] = I('post.status5');
            $data['status6'] = I('post.status6');
            $data['status7'] = I('post.status7');
            $data['status8'] = I('post.status8');
            $data['status9'] = I('post.status9');
            $data['status10'] = I('post.status10');

            $data['progresstitle1'] = I('post.progressTitle1');
            $data['progresstitle2'] = I('post.progressTitle2');
            $data['progresstitle3'] = I('post.progressTitle3');
            $data['progresstitle4'] = I('post.progressTitle4');
            $data['progresstitle5'] = I('post.progressTitle5');
            $data['progresstitle6'] = I('post.progressTitle6');
            $data['progresstitle7'] = I('post.progressTitle7');
            $data['progresstitle8'] = I('post.progressTitle8');
            $data['progresstitle9'] = I('post.progressTitle9');
            $data['progresstitle10'] = I('post.progressTitle10');

            $data['progresstime1'] = I('post.progressTime1');
            $data['progresstime2'] = I('post.progressTime2');
            $data['progresstime3'] = I('post.progressTime3');
            $data['progresstime4'] = I('post.progressTime4');
            $data['progresstime5'] = I('post.progressTime5');
            $data['progresstime6'] = I('post.progressTime6');
            $data['progresstime7'] = I('post.progressTime7');
            $data['progresstime8'] = I('post.progressTime8');
            $data['progresstime9'] = I('post.progressTime9');
            $data['progresstime10'] = I('post.progressTime10');
            $data['openinfoid'] = I('post.id');
            //print_r($data);die;

            $id = D('Progress')->field('id')->where(array('openinfoid' => $data['openinfoid']))->find();
            if ($id) {
                $where['openinfoid'] = $data['openinfoid'];
                $result = D('Progress')->editData($where, $data);
            }else {
                unset($data['id']);
                $result = D('Progress')->addData($data);
            }
            if($result){
                $message['status']=1;
                $message['message']='保存成功';
            }else {
                $message['status']=0;
                $message['message']='保存失败';
            }
        } else {
            $message['status']=0;
            $message['message']='保存失败';
        }
        $this->ajaxReturn($message,'JSON');
    }
    public function account(){
        $this->display();
    }
    /**
     * 党员账号
     */
    public function ajaxPartyAccountList(){
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $result["total"]=D('PartyAccount')->count();

        $data=D('PartyAccount')->query("select ma.*,t.name as tname,v.name as vname  from qfant_party_account ma ,qfant_town t,qfant_village v where ma.townid=t.id and ma.villageid = v.id  limit ".$offset.','.$rows);
        $result["rows"] = $data;
        $this->ajaxReturn($result,'JSON');
    }

    /**
     * 添加
     */
    public function addAccount(){
        if(IS_POST){
            $data['truename']=I('post.truename');
            $data['username']=I('post.username');
            $data['password']=md5(I('post.password'));
            $data['townid']=I('post.townid');
            $data['villageid']=I('post.villageid');
            unset($data['id']);
            $account=D("PartyAccount")->where(array('username'=>$data['username']))->find();
            if($account){
                $message['status']=0;
                $message['message']='此账号已存在';
            }else {
                $result=D('PartyAccount')->addData($data);
                if($result){
                    $message['status']=1;
                    $message['message']='保存成功';
                }else {
                    $message['status']=0;
                    $message['message']='保存失败';
                }
            }
        }else {
            $message['status']=0;
            $message['message']='保存失败';
        }
        $this->ajaxReturn($message,'JSON');
    }
    /**
     * 删除
     */
    public function deleteAccount(){
        $id=I('get.id');
        $map=array(
            'id'=>$id
        );
        $result=D('PartyAccount')->deleteData($map);
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
     * 添加
     */
    public function editAccount(){
        if(IS_POST){
            $data['id']=I('post.id');
            $data['truename']=I('post.truename');
            $data['username']=I('post.username');
            $data['password']=I('post.password');
            $data['townid']=I('post.townid');
            $data['villageid']=I('post.villageid');
            if($data['password']){
                $data['password']=md5($data['password']);
            }
            $where['id']=$data['id'];
            $result=D('PartyAccount')->editData($where,$data);
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
     * ajaxActivityAccountList
     */
    public function ajaxActivityAccountList(){
        $townid=I('get.townid');
        $meetid=I('get.meetid');
        $accounts=D('PartyAccount')->where(array('townid'=>$townid))->select();
        foreach ($accounts as $k=>$val){
            $mu=D('MeetUser')->where(array('meet_id'=>$meetid,'account_id'=>$val['id']))->find();
            $accounts[$k]['text']=$val['username'];
            if($mu){
                $accounts[$k]['checked'] = true;
            }
        }
        $this->ajaxReturn($accounts,'JSON');
    }

}
