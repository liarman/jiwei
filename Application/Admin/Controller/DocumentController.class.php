<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
use PHPExcel;
use PHPExcel_IOFactory;
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
            //print_r($departs);die;
        }elseif($_SESSION['user']['datarange'] && empty($departs)){
            $departs=$_SESSION['user']['datarange'];
        }
        $goodsname=I("post.goodsname");
        $receivername=I("post.receivername");
        $shipper=I("post.shipper");
        $name=I("post.name");
        $tname=I("post.tname");
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        if($departs){
            $countsql = "SELECT	 count(o.id) AS total FROM	qfant_document o WHERE	1 = 1 and o.department_id in (".$departs.") ";
            $sql = " SELECT	 o.*,t.name as tname FROM	qfant_document o  left join qfant_department t on o.department_id=t.id where 1=1 and o.department_id in (".$departs.") ";
            $param=array();
            if(!empty($name)){
                $countsql.=" and o.name like '%s'";
                $sql.=" and o.name like '%s'";
                array_push($param,'%'.$name.'%');
            }
            if(!empty($tname)){
                $countsql.=" and o.department_id = %d ";
                $sql.=" and o.department_id = %d ";
                array_push($param,$tname);
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
     * 干部统计
     */
    public function ajaxDocumentCountList(){
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        $offset = ($page-1)*$rows;
        $tname=I("post.tname");
        if($tname){
            $countsql = "SELECT	 count(o.id) AS total FROM	qfant_document o  JOIN (SELECT d.id from qfant_department d where FIND_IN_SET (id ,queryDepartments(".$tname."))) AS  a ON o.department_id = a.id ";
            $sql = " SELECT	 o.*,t.name as tname FROM	qfant_document o  left join qfant_department t on o.department_id=t.id JOIN (Select d.id FROM qfant_department d where FIND_IN_SET (id ,queryDepartments(".$tname."))) as a on o.department_id = a.id ";
        }else{
            $countsql = "SELECT	 count(o.id) AS total FROM	qfant_document o  JOIN (SELECT d.id from qfant_department d where FIND_IN_SET (id ,queryDepartments(1))) AS  a ON o.department_id = a.id ";
            $sql = " SELECT	 o.*,t.name as tname FROM	qfant_document o  left join qfant_department t on o.department_id=t.id JOIN (Select d.id FROM qfant_department d where FIND_IN_SET (id ,queryDepartments(1))) as a on o.department_id = a.id  ";
        }
        $param=array();
        $sql.=" order by o.createtime desc limit %d,%d ";
        array_push($param,$offset);
        array_push($param,$rows);
        $data=D('Document')->query($countsql,$param);
        $result['total']=$data[0]['total'];
        $data=D('Document')->query($sql,$param);
        $result["rows"] = $data;
        $result["status"] = 1;

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
            if($data['ziliao']){
                $data['ziliao']=implode(",", $data['ziliao']);
            }
           // print_r($data['ziliao']);die;
            unset($data['id']);
            $res=D('Document')->addData($data);
            //print_r($res);die;
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
            /*if($data['ziliao']){
                $data['ziliao']=implode(",", $data['ziliao']);
            }
            if($data['report']){
                $data['report']=implode(",", $data['report']);
            }
            if($data['shenji']){
                $data['shenji']=implode(",", $data['shenji']);
            }*/
            //print_r($data['ziliao']);die;
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
     * 线索处置
     */
    public function editClueFile(){
        if(IS_POST){
            $data=I('post.');
            $where['id']=$data['id'];
            if($data['ziliao'] == null){
                $data['ziliao']=null;
            }else{
                $data['ziliao']=implode(",", $data['ziliao']);
            }
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
     * 述职述德
     */
    public function editReportFile(){
        if(IS_POST){
            $data=I('post.');
            $where['id']=$data['id'];
            if($data['report'] == null){
                $data['report']=null;
            }else{
                $data['report']=implode(",", $data['report']);
            }
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
     * 述职述德
     */
    public function editShenjiFile(){
        if(IS_POST){
            $data=I('post.');
            $where['id']=$data['id'];
            if($data['shenji'] == null){
                $data['shenji']=null;
            }else{
                $data['shenji']=implode(",", $data['shenji']);
            }
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


    public function editClue(){
        if(IS_Post){
           // $data['id']=I('post.id');
            $data['baseinfo']=I('post.baseinfo');
            $data['question_source']=I('post.question_source');
            $data['process_status']=I('post.process_status');
            $data['remark']=I('post.remark');
//            $where['id']=$data['id'];
           // print_r($data);die;
            $result=D('Document')->where(array('id'=>I('post.id')))->save($data);
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
    /**
     * 批量生成excle文件
     */
    public function batchBuild(){

        $ids=I("get.ids");
        $sql = " SELECT	 o.*,t.name as tname , t.id as did FROM	qfant_document o  left join qfant_department t on o.department_id=t.id where 1=1 and o.id in (".$ids.") ";

        $data=D('Document')->query($sql);
        $html ="";
        foreach ($data as $k=>$val){
            $html="";
            $id=$val['id'];
            $name=$val['name'];
            $dpt_id=$val['department_id'];
            $doc=D("Document")->where(array('id'=>$id))->find();
            $img = $doc['headimg'];
            $doc['headimg'] = $this->base64EncodeImage($img);


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
            $html.=$this->fetch("Document/detail");

            $res = $this->build_html($html,$dpt_id,$name);
            //print_r($res);die;
            D("Document")->where(array('id'=>$id))->save(array('dirname'=>$res));

        }
        $message['status']=1;
        $message['message']='批量生成成功';
        $this->ajaxReturn($message,'JSON');

}

    public function base64EncodeImage ($img) {
            if(strpos($img,'192.168.148.254')!==false){
                $img=str_replace('http://192.168.148.254','.',$img);
                $image_info = getimagesize($img);
                $image_data = fread(fopen($img, 'r'), filesize($img));
                $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
                return $base64_image;
            }else {
                return $img;
            }
    }


    public function build_html($html,$dpt_id,$name){

        $path = "./Upload/".$dpt_id."/";   //生成的档案所在目录
        if(!file_exists($path)){
            mkdir($path, 0700,true);
        }
        $Pinyin = new \Org\Util\ChinesePinyin();
        $name = $Pinyin->TransformWithoutTone($name);
        $time = $name.'.html';      //生成的二维码文件名
        $fileName = $path.$time;    //1.拼装生成的二维码文件路径
        //以读写方式打写指定文件，如果文件不存则创建
        if( ($TxtRes=fopen ($fileName,"w+")) === FALSE){
            echo("创建可写文件：".$fileName."失败");
            exit();
        }

        if(!fwrite ($TxtRes,$html)){ //将信息写入文件
            echo ("尝试向文件".$fileName."写入".$html."失败！");
            fclose($TxtRes);
            exit();
        }

        fclose ($TxtRes); //关闭指针

        return $fileName;


    }

    public function batchDownload(){

        $ids=I("get.ids","");
        $ids=explode(",",$ids);
        if($ids){
            $zip=new \ZipArchive();
            $zipName="./zip/download.zip";//定义打包后的包名
/*            if(!file_exists($zipName)){
                exit("无法找到文件");
           }*/

            if ($zip->open($zipName, \ZIPARCHIVE::OVERWRITE | \ZIPARCHIVE::CREATE)!==TRUE) {
                exit('无法打开文件，或者文件创建失败');
          }
          // print_r(file_exists($zipName));die;
            for ($i=0;$i<count($ids);$i++){
                $place=D("Document")->where(array("id"=>$ids[$i]))->find();
               if(file_exists($place['dirname'])){
                    $zip->addFile(iconv("utf-8","gbk//ignore",$place['dirname']), iconv("utf-8","gbk//ignore",basename($place['dirname']))); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
              }
           }
            $zip->close();
            //如果不要下载，下面这段删掉即可，如需返回压缩包下载链接，只需 return $zipName;
            //如果不要下载，下面这段删掉即可，如需返回压缩包下载链接，只需 return $zipName;
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header('Content-disposition: attachment; filename='.basename($zipName)); //文件名
            header("Content-Type: application/zip"); //zip格式的
            header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
            header('Content-Length: '. filesize($zipName)); //告诉浏览器，文件大小
            @readfile($zipName);
            //$this->delDirAndFile($dir,true);//删除目录和文件
            //unlink($zipName);////删除压缩包
            //print_r($zip);
        }else {
            $message['status']=0;
            $message['message']='下载失败';
            $this->ajaxReturn($message,'json');
        }
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


    public  function clueDetail(){
        $id=I('get.id');
        $doc=D("Document")->where(array('id'=>$id))->find();
        $this->assign("doc",$doc);
        $this->display();

    }

    public  function fileDetail(){
        $id=I('get.id');
        $doc=D("Document")->where(array('id'=>$id))->find();
        $this->assign("doc",$doc);

        $ziliao=$doc['ziliao'];
        if($ziliao){
            $condZiliao['id']=array('in',$ziliao);
            $ziliaos=D('Attachment')->where($condZiliao)->select();
            $this->assign("ziliaos",$ziliaos);
        }
        $report=$doc['report'];
        if($report){
            $condReport['id']=array('in',$report);
            $reports=D('Attachment')->where($condReport)->select();
            $this->assign("reports",$reports);
        }
        $shenji=$doc['shenji'];
        if($shenji){
            $condShenji['id']=array('in',$shenji);
            $shenjis=D('Attachment')->where($condShenji)->select();
            $this->assign("shenjis",$shenjis);
        }
        $this->display();
    }

    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8','gb2312','$expTitle');
        $fileName = date('干部档案');
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor('PHPExcel.PHPExcel');

        $objPHPExcel = new PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function daochu(){
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
        $tname=I("get.tname");
        $sql = "SELECT	 o.*,t.name as tname FROM	qfant_document o  left join qfant_department t on o.department_id=t.id where 1=1 and o.department_id in (".$departs.")";
        $param=array();
        if(!empty($tname)){
            $sql.=" and o.department_id = %d ";
            array_push($param,$tname);
        }
        $data=D('Document')->query($sql,$param);

        $xlsName = "Document";
        $xlsCell = array(
            array('id','ID'),
            array('name','用户名'),
            array('birthday','出生年月'),
            array('cardnumber','身份证号'),
            array('nation','民族'),
            array('join_date','入党时间'),
            array('native_place','籍贯'),
            array('work_date','工作时间'),
            array('work_depart','工作单位'),
            array('position','现任职务'),
            array('wedding','婚姻状况'),
            array('shenfen','人员身份'),
            array('address','家庭住址'),
            array('tname','所属部门'),
        );
        $xlsData = $data;
        foreach($xlsData as $k => $v){
            if($v['wedding']==1){
                $xlsData[$k]['wedding'] = '未婚';
            }
            if($v['wedding']==2){
                $xlsData[$k]['wedding'] = '已婚';
            }
            if($v['wedding']==3){
                $xlsData[$k]['wedding'] = '离异';
            }
            if($v['wedding']==4){
                $xlsData[$k]['wedding'] = '丧偶';
            }
            if($v['shenfen']==1){
                $xlsData[$k]['shenfen'] = '行政';
            }
            if($v['shenfen']==2){
                $xlsData[$k]['shenfen'] = '参公';
            }
            if($v['shenfen']==3){
                $xlsData[$k]['shenfen'] = '事业';
            }
            if($v['shenfen']==4){
                $xlsData[$k]['shenfen'] = '其他';
            }
        }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }
}
