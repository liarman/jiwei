<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台权限管理
 */
class IdcardController extends AdminBaseController{
    public function ajaxIdcardList(){
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $countsql="select count(r.id) AS total from qfant_idcard r";
        $sql="SELECT r.* FROM qfant_idcard r";
        $param=array();
        array_push($param,$offset);
        array_push($param,$rows);
        $sql.=" limit %d,%d";
        $data=D('Idcard')->query($countsql,$param);
        $result['total']=$data[0]['total'];
        $data=D('Idcard')->query($sql,$param);
        $result["rows"] = $data;
        $this->ajaxReturn($result,'JSON');

}
    /**
     * 导入身份证
     */
    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle.date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function impIdcard(){
        if (!empty($_FILES)) {
            $upload = new \Think\Upload();// 实例化上传类
            $filepath='./Upload/excel/';
            $upload->exts = array('xlsx','xls');// 设置附件上传类型
            $upload->rootPath  =  $filepath; // 设置附件上传根目录
            $upload->saveName  =     'time';
            $upload->autoSub   =     false;
            if (!$info=$upload->upload()) {
                $this->error($upload->getError());
            }
            foreach ($info as $key => $value) {
                unset($info);
                $info[0]=$value;
                $info[0]['savepath']=$filepath;
            }
            vendor("PHPExcel.PHPExcel");
            $file_name=$info[0]['savepath'].$info[0]['savename'];
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumn = $sheet->getHighestColumn(); // 取得总列数
            for($i=2;$i<=$highestRow;$i++)
            {
                $cardid = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
                $id = M('Idcard')->where(array('cardid'=>$cardid))->field('id')->find();
                if(empty($id)){
                    $data['name']  = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
                    $data['cardid'] = $cardid;
                    M('Idcard')->add($data);

                    $message['status']=1;
                    $message['message']='导入完成，保存成功!';
                }else{
                    $message['status']=0;
                    $message['message']= '第'.$i.'行，错误，身份证号重复，保存失败!';
                    break;
                }
            }
        }else
        {
            $message['status']=0;
            $message['message']='请选择上传文件！';
        }

        $this->ajaxReturn($message,'JSON');

    }

}
