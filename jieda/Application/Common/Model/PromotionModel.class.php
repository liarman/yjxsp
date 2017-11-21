<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 权限规则model
 */
class PromotionModel extends BaseModel{

    protected $_auto=array(
        array('register_time','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );

}
