<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台门店管理控制器
 */
class HouseController extends AdminBaseController{
	public function ajaxHouseList(){
        $document_id=I('get.document_id');//发车id
        $rows=D('FamilyHouse')->where(array('document_id'=>$document_id))->select();
        $data['rows']=$rows;
        $data['status']=1;
		$this->ajaxReturn($data,'JSON');
	}
    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $data['house_source']=I('post.house_source');
            $data['name']=I('post.name');
            $data['area']=I('post.area');
            $data['buydate']=I('post.buydate');
            $data['address']=I('post.address');
            $data['house_property']=I('post.house_property');
            $data['trade_price']=I('post.trade_price');
            $data['document_id']=I('post.document_id');
            $data['id']=I('post.id');
            $where['id']=$data['id'];
            if( $data['id']){
                $result=D('FamilyHouse')->editData($where,$data);
            }else {
                $result=D('FamilyHouse')->addData($data);
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
		$result=D('FamilyHouse')->deleteData($map);
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
