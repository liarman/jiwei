<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台权限管理
 */
class PassportController extends AdminBaseController{
    public function ajaxPassportList(){
        $document_id=I('get.document_id');//发车id
        $rows=D('Passport')->where(array('document_id'=>$document_id))->select();
        $data['rows']=$rows;
        $data['status']=1;
        $this->ajaxReturn($data,'JSON');

    }

    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $data['passportno']=I('post.passportno');
            $data['signdate']=I('post.signdate');
            $data['enddate']=I('post.enddate');
            $data['custodian']=I('post.custodian');
            $data['remark']=I('post.remark');
            $data['document_id']=I('post.document_id');
            $data['id']=I('post.id');
            $where['id']=$data['id'];
            if( $data['id']){
                $result=D('Passport')->editData($where,$data);
            }else {
                $result=D('Passport')->addData($data);
            }
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
    public function delete(){
        $id=I('get.id');
        $map=array(
            'id'=>$id
        );
        $result=D('Passport')->deleteData($map);
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
