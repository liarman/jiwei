<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class TownController extends AdminBaseController{
    public function ajaxTownList(){
        $rows=D('Town')->select();
        $data['rows']=$rows;
        $data['status']=1;
        $this->ajaxReturn($data,'JSON');
    }
    public function ajaxTownListAll(){
        $rows=D('Town')->select();
        $this->ajaxReturn($rows,'JSON');
    }
    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $data['name']=I('post.name');
            $data['sort']=I('post.sort');
            $data['id']=I('post.id');
            $where['id']=$data['id'];
            if( $data['id']){
                $result=D('Town')->editData($where,$data);
            }else {
                $result=D('Town')->addData($data);
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
        $result=D('Town')->deleteData($map);
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
