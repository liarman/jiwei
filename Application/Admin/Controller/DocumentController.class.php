<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 干部档案管理
 */
class DocumentController extends AdminBaseController{
    public function ajaxDocumentList(){
        $userid=$_SESSION['user']['id'];
        $data['userid']=$userid;
        $departs='';
        if($_SESSION['user']['department_id']){
            $departs=D('Department')->query("select queryDepartments(".$_SESSION['user']['department_id'].') as departs');
        }
        if($_SESSION['user']['datarange'] && $departs){
            $departs=$_SESSION['user']['datarange'].','.$departs[0]['departs'];
        }elseif($_SESSION['user']['datarange'] && empty($departs)){
            $departs=$_SESSION['user']['datarange'];
        }

        //print_r(session('user.data'));
//        print_r($_SESSION['user']);die;
        $goodsname=I("post.goodsname");
        $receivername=I("post.receivername");
        $shipper=I("post.shipper");
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        if($departs){
            $countsql = "SELECT	 count(o.id) AS total FROM	qfant_document o WHERE	1 = 1 and o.department_id in (".$departs.") ";
            $sql = " SELECT	 o.*,t.name as tname FROM	qfant_document o  left join qfant_department t on o.department_id=t.id where 1=1 and o.department_id in (".$departs.") ";
            $param=array();
            if(!empty($goodsname)){
                $countsql.=" and o.goodsname like '%s'";
                $sql.=" and o.goodsname like '%s'";
                array_push($param,'%'.$goodsname.'%');
            }
            if(!empty($receivername)){
                $countsql.=" and o.receivername like '%s'";
                $sql.=" and o.receivername like '%s'";
                array_push($param,'%'.$receivername.'%');
            }
            if(!empty($shipper)){
                $countsql.=" and o.shipper like '%s'";
                $sql.=" and o.shipper like '%s'";
                array_push($param,'%'.$shipper.'%');
            }
            $sql.=" order by o.createtime desc limit %d,%d ";
            array_push($param,$offset);
            array_push($param,$rows);
            $data=D('Document')->query($countsql,$param);
            $result['total']=$data[0]['total'];
            $data=D('Document')->query($sql,$param);
            $result["rows"] = $data;
            $result["status"] = 1;
        }else{
            $result['total']=0;
            $result["rows"] = array();
            $result["status"] = 1;
            $this->ajaxReturn($result,'JSON');
        }
        $this->ajaxReturn($result,'JSON');

    }
    /**
     * 添加
     */
    public function add(){
        if(IS_POST){
            $data=I('post.');
            $data['createtime']=date("Y-m-d h:i:s", time());
            $userid=$_SESSION['user']['id'];
            $data['userid']=$userid;
            unset($data['id']);
            $res=D('Document')->addData($data);
            if($res){
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
     * 编辑
     */
    public function edit(){
        if(IS_POST){
            $data=I('post.');
            $where['id']=$data['id'];
            $data['createtime']=date("Y-m-d h:i:s", time());
            $userid=$_SESSION['user']['id'];
            $data['userid']=$userid;
            $result=D('Document')->editData($where,$data);
            if($result){
                $message['status']=1;
                $message['message']='保存成功';
            }else {
                $message['status']=0;
                $message['message']='保存失败';
            }
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
        $result=D('Document')->deleteData($map);
        if($result){
            $message['status']=1;
            $message['message']='删除成功';
        }else {
            $message['status']=0;
            $message['message']='删除失败';
        }
        $this->ajaxReturn($message,'JSON');
    }

    public function addCarOrder(){
        $data['cardriveid']=I('get.id');//发车id
        $ids=I('get.orderid');//订单id
        $arr1 = explode("@@",$ids);
        $data['status']='1';//已装车
        $result="";
        for($index=0;$index<count($arr1);$index++) {
            $where['id']=$arr1[$index];
            $result=D('Order')->editData($where,$data);
        }
        if($result){
            $message['status']=1;
            $message['message']='装车成功';
        }else {
            $message['status']=0;
            $message['message']='装车失败';
        }
        $this->ajaxReturn($message,'JSON');
    }


    public  function printorderList(){
        $ids=I('get.id');
        $arr1 = explode("@@",$ids);
        for($index=0;$index<count($arr1);$index++) {
            $data=D('Order')->where(array('id'=>$arr1[$index]))->find();
            $datap[$index]=$data;

        }
        $this->ajaxReturn($datap,'JSON');

    }

    public  function detail(){
        $id=I('get.id');
        $doc=D("Document")->where(array('id'=>$id))->find();
        $this->assign("doc",$doc);

        $familys=D("Family")->where(array('document_id'=>$id))->select();
        $this->assign("familys",$familys);

        $punishes=D("FamilyPunish")->where(array('document_id'=>$id))->select();
        $this->assign("punishes",$punishes);

        $houses=D("FamilyHouse")->where(array('document_id'=>$id))->select();
        $this->assign("houses",$houses);

        $stocks=D("FamilyStock")->where(array('document_id'=>$id))->select();
        $this->assign("stocks",$stocks);

        $passports=D("Passport")->where(array('document_id'=>$id))->select();
        $this->assign("passports",$passports);

        $abroads=D("Abroad")->where(array('document_id'=>$id))->select();
        $this->assign("abroads",$abroads);

        $companys=D("FamilyCompany")->where(array('document_id'=>$id))->select();
        $this->assign("companys",$companys);

        $weddings=D("ChildWedding")->where(array('document_id'=>$id))->select();
        $this->assign("weddings",$weddings);

        $incomes=D("FamilyIncome")->where(array('document_id'=>$id))->select();
        $this->assign("incomes",$incomes);

        $totalIncome=D("FamilyIncome")->where(array('document_id'=>$id))->sum('year_income');
        $this->assign("totalIncome",$totalIncome);
        $this->display();

    }
}
