<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class StockController extends AdminBaseController{
    public function ajaxStockList(){
        $document_id=I('get.document_id');//发车id
        $rows=D('FamilyStock')->where(array('document_id'=>$document_id))->select();
        $data['rows']=$rows;
        $data['status']=1;
        $this->ajaxReturn($data,'JSON');
    }
    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $data['stock_code']=I('post.stock_code');
            $data['name']=I('post.name');
            $data['stock_count']=I('post.stock_count');
            $data['buydate']=I('post.buydate');
            $data['market_value']=I('post.market_value');
            $data['document_id']=I('post.document_id');
            $data['id']=I('post.id');
            $where['id']=$data['id'];
            if( $data['id']){
                $result=D('FamilyStock')->editData($where,$data);
            }else {
                $result=D('FamilyStock')->addData($data);
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
        $result=D('FamilyStock')->deleteData($map);
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
