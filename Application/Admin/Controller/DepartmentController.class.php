<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 部门管理
 */
class DepartmentController extends AdminBaseController{
//******************权限***********************
    /**
     * 部门管理
     */
    public function index(){
        $this->display();
    }
    public function ajaxDepartmentList(){
        $tree=array();
        if($_SESSION['user']['department_id']){
            $data=D("Department")->query("SELECT * from qfant_department where FIND_IN_SET (id ,queryDepartments(".$_SESSION['user']['department_id']."))");
            $pid=$_SESSION['user']['department_id'];
        }else {
            $data=D("Department")->query("SELECT * from qfant_department where FIND_IN_SET (id ,queryDepartments(1))");
            $pid=1;
        }
        $data=D("Department")->getTreeData($data,$pid,'name','text',$pid);
        $root=D("Department")->where(array('id'=>$pid))->field('id,name,pid,sort')->find();
        $root['text']=$root['name'];
        $tree[0]=$root;
        $tree[0]['children']=$data;
        $this->ajaxReturn($tree,'JSON');
    }
    /**
     * 添加权限
     */
    public function add(){
        $data=I('post.');
        $map=array(
            'id'=>$data['id']
        );
        if($data['id']){
            $result=D('Department')->editData($map,$data);
        }else {
            $result=D('Department')->addData($data);
        }
        if($result){
            $message['status']=1;
            $message['message']='保存成功';
        }else {
            $message['status']=0;
            $message['message']='保存失败';
        }
        $this->ajaxReturn($message,'JSON');
    }
    /**
     * 获取权限
     */
    public function get(){
        $id=I('get.id');
        $department=D('Department')->where(array('id'=>$id))->find();
        $parent=D('Department')->where(array('id'=>$department['pid']))->find();
        $department['pname']=$parent['name'];
        $this->ajaxReturn($department,'JSON');
    }
    /**
     * 删除权限
     */
    public function delete(){
        $id=I('get.id');
        $map=array(
            'id'=>$id
        );
        $children=D('Department')->where(array('pid'=>$id))->select();
        if($children){
            $message['status']=0;
            $message['message']='删除失败，请先删除子部门';
        }else {
            $result=D('Department')->deleteData($map);
            if($result){
                $message['status']=1;
                $message['message']='删除成功';
            }else {
                $message['status']=0;
                $message['message']='删除失败';
            }
        }
        $this->ajaxReturn($message,'JSON');
    }
}