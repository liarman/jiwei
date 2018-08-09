<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 管理控制器
 */
class PunishController extends AdminBaseController{
    public function index(){
        $this->display();
    }

    public function ajaxPunishList(){
        $document_id=I('get.document_id');//发车id
        $rows=D('FamilyPunish')->where(array('document_id'=>$document_id))->select();
        $data['rows']=$rows;
        $data['status']=1;
        $this->ajaxReturn($data,'JSON');

    }

    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $data['punish_date']=I('post.punish_date');
            $data['name']=I('post.name');
            $data['punish_reason']=I('post.punish_reason');
            $data['process_result']=I('post.process_result');
            $data['process_stage']=I('post.process_stage');
            $data['document_id']=I('post.document_id');
            $data['id']=I('post.id');
            $where['id']=$data['id'];
            if( $data['id']){
                $result=D('FamilyPunish')->editData($where,$data);
            }else {
                $result=D('FamilyPunish')->addData($data);
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
        $result=D('FamilyPunish')->deleteData($map);
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
