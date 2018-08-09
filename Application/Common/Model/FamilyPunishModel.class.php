<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 权限规则model
 */
class FamilyPunishModel extends BaseModel{

    // 自动验证
    protected $_validate=array(
//        array('username','require','用户名必须',0,'',3), // 验证字段必填
    );

    // 自动完成
    protected $_auto=array(
//        array('password','md5',1,'function') , // 对password字段在新增的时候使md5函数处理
//        array('register_time','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );



}
