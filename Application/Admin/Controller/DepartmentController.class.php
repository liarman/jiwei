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
        $data=D("Department")->getTreeData();
        $result["rows"] = $data;
        $result["total"] = D("Department")->count();
        $result['status']=1;
        $result['message']='获取成功';
        $this->ajaxReturn($result,'JSON');
    }
    /**
     * 添加权限
     */
    public function add(){
        $data=I('post.');
        unset($data['id']);
        $result=D('AuthRule')->addData($data);
        if($result){
            $message['status']=1;
            $message['message']='添加成功';
        }else {
            $message['status']=0;
            $message['message']='添加失败';
        }
        $this->ajaxReturn($message,'JSON');
    }

    /**
     * 修改权限
     */
    public function edit(){
        $data=I('post.');
        $map=array(
            'id'=>$data['id']
            );
        $result=D('AuthRule')->editData($map,$data);
        if($result){
            $message['status']=1;
            $message['message']='修改成功';
        }else {
            $message['status']=0;
            $message['message']='修改失败';
        }
        $this->ajaxReturn($message,'JSON');
    }
    /**
     * 获取权限
     */
    public function get(){
        $id=I('get.id');
        $rule=D('AuthRule')->where(array('id'=>$id))->find();
        $this->ajaxReturn($rule,'JSON');
    }
    /**
     * 删除权限
     */
    public function delete(){
        $id=I('get.id');
        $map=array(
            'id'=>$id
            );
        $result=D('AuthRule')->deleteData($map);
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
