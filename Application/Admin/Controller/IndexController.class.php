<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
use PHPExcel;
use PHPExcel_IOFactory;
/**
 * 后台首页控制器
 */
class IndexController extends AdminBaseController{
	/**
	 * 首页
	 */
	public function index(){
		// 分配菜单数据
		$nav_data=D('AdminNav')->getTreeData('level','order_number desc');
		$assign=array(
			'data'=>$nav_data
		);
		$this->assign($assign);
		$this->display();
	}
	/**
	 * elements
	 */
	public function elements(){

		$this->display();
	}
	
	/**
	 * welcome
	 */
	public function welcome(){
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
		$name=I("post.name");
		$tname=I("post.tname");
		$sql = "SELECT	 o.*,t.name as tname FROM	qfant_document o  left join qfant_department t on o.department_id=t.id where 1=1";
		$param=array();
		if(!empty($name)){
			$sql.=" and o.name like '%s'";
			array_push($param,'%'.$name.'%');
		}
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
