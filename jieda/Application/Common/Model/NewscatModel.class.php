<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 权限规则model
 */
class NewscatModel extends BaseModel{

    protected $_auto=array(
        array('createtime','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );
    /**
     * 获取全部
     * @param  string $type tree获取树形结构 level获取层级结构
     * @return array       	结构数据
     */
    public function getTreeData($type='tree',$order='',$alias=''){
        // 判断是否需要排序
        if(empty($order)){
            $data=$this->select();
        }else{
            $data=$this->order($order)->select();
        }
        if(!empty($alias)){
            foreach($data as $k=>$val){
                $data[$k][$alias]=$val['name'];
            }
        }
        // 获取树形或者结构数据
        $data=\Org\Nx\Data::tree2($data,0);
        return $data;
    }
}
