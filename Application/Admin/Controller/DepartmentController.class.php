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
        $data=D("Department")->getTreeData("tree",null,'name','text');
        $result["rows"] = $data;
        $result["total"] = D("Department")->count();
        $result['status']=1;
        $result['message']='获取成功';
        $this->ajaxReturn($data,'JSON');
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
