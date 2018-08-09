<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class ChildWeddingController extends AdminBaseController{
    public function ajaxChildWeddingList(){
        $document_id=I('get.document_id');//发车id
        $rows=D('ChildWedding')->where(array('document_id'=>$document_id))->select();
        $data['rows']=$rows;
        $data['status']=1;
        $this->ajaxReturn($data,'JSON');

    }

    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $data['name']=I('post.name');
            $data['mate_name']=I('post.mate_name');
            $data['country']=I('post.country');
            $data['work_dept']=I('post.work_dept');
            $data['position']=I('post.position');
            $data['submit_date']=I('post.submit_date');
            $data['document_id']=I('post.document_id');
            $data['id']=I('post.id');
            $where['id']=$data['id'];
            if( $data['id']){
                $result=D('ChildWedding')->editData($where,$data);
            }else {
                $result=D('ChildWedding')->addData($data);
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
        $result=D('ChildWedding')->deleteData($map);
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
