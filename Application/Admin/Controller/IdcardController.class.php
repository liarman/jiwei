<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台权限管理
 */
class IdcardController extends AdminBaseController
{
    public function ajaxIdcardList()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $countsql = "select count(r.id) AS total from qfant_idcard r";
       // $sql = "SELECT r.* FROM qfant_idcard r";
        $sql = "SELECT r.responsevalue,i.name,i.cardid ,i.id as id FROM qfant_idcard i left  join qfant_idcard_response r  ON i.id=r.idcardid";
        $param = array();
        array_push($param, $offset);
        array_push($param, $rows);
        $sql .= " limit %d,%d";
        $data = D('Idcard')->query($countsql, $param);
        $result['total'] = $data[0]['total'];
        $data = D('Idcard')->query($sql, $param);
        $result["rows"] = $data;
        $this->ajaxReturn($result, 'JSON');

    }

    /**
     * 导入身份证
     */
    public function exportExcel($expTitle, $expCellName, $expTableData)
    {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle . date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle . '  Export time:' . date('Y-m-d H:i:s'));
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function impIdcard()
    {
        if (!empty($_FILES)) {
            $upload = new \Think\Upload();// 实例化上传类
            $filepath = './Upload/excel/';
            $upload->exts = array('xlsx', 'xls');// 设置附件上传类型
            $upload->rootPath = $filepath; // 设置附件上传根目录
            $upload->saveName = 'time';
            $upload->autoSub = false;
            if (!$info = $upload->upload()) {
                $this->error($upload->getError());
            }
            foreach ($info as $key => $value) {
                unset($info);
                $info[0] = $value;
                $info[0]['savepath'] = $filepath;
            }
            vendor("PHPExcel.PHPExcel");
            $file_name = $info[0]['savepath'] . $info[0]['savename'];
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load($file_name, $encode = 'utf-8');
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumn = $sheet->getHighestColumn(); // 取得总列数
            for ($i = 2; $i <= $highestRow; $i++) {
                $cardid = $objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();
                $id = M('Idcard')->where(array('cardid' => $cardid))->field('id')->find();
                if (empty($id)) {
                    $data['name'] = $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
                    $data['cardid'] = $cardid;
                    M('Idcard')->add($data);

                    $message['status'] = 1;
                    $message['message'] = '导入完成，保存成功!';
                } else {
                    $message['status'] = 0;
                    $message['message'] = '第' . $i . '行身份证号重复，保存失败!';
                    break;
                }
            }
        } else {
            $message['status'] = 0;
            $message['message'] = '请选择上传文件！';
        }

        $this->ajaxReturn($message, 'JSON');

    }

    function  look()
    {
        $d=I('get.');
        $url = 'http://59.203.96.98:8000/';
        $da='{
                                "header": {
                                    "authCode": "b94b0c31dcbb6b4e92b35f02cdf564c2",
                                    "senderID": "3416-0049"
                                },
                                "request": {
                                    "data": [
                                        {
                                            "name": "username",
                                            "type": "GsbString",
                                            "value": "bzsg"
                                        },
                                        {
                                            "name": "password",
                                            "type": "GsbString",
                                            "value": "bab155382877539583b96a6501929fd3"
                                        },
                                        {
                                            "name": "serviceCode",
                                            "type": "GsbString",
                                            "value": "340100000000_01_0000004054"
                                        },
                                        {
                                            "name": "condition",
                                            "type": "GsbString",
                                            "value": "<condition><item><GMSFHM>341281198811175854</GMSFHM></item></condition>"
                                        },
                                        {
                                            "name":"requiredItems",
                                            "type":"GsbString",
                                            "value":"<requiredItems><item><GMSFHM>公民身份号码</GMSFHM><XM>姓名</XM><QYMC>企业名称</QYMC><YQYGX>与企业关系</YQYGX><JYDZ>经营地址</JYDZ><SHTYXYDM>社会统一信用代码</SHTYXYDM></item></requiredItems>"
                                        },
                                        {
                                            "name": "clientInfo",
                                            "type": "GsbString",
                                            "value": "<clientInfo><loginName>jwgly</loginName></clientInfo>"
                                        }
                                    ],
                                    "method": "requestQuery",
                                    "serviceCode": "3416-0049-1-00000001",
                                    "version": "1"
                                }
                            }';
        $res = $this->curl_post($url,$da);
        $data['responsevalue']=$res;

        $data['idcardid']=$d['id'];
        $Resdata=D('IdcardResponse')->where(array('idcardid'=>$data['idcardid']))->find();
        if($Resdata['id']){//如果存在该员工的数据删除，重新插入新的数据
            $id=$Resdata['id'];
            $map=array('id'=>$id);
           $R=D('IdcardResponse')->deleteData($map);
            var_dump($R);
        }
        unset($data['id']);
        $result=D('IdcardResponse')->addData($data);
        if($result){
            $message['status']=1;
            $message['message']='检验成功';
        }else {
            $message['status']=0;
            $message['message']='检验失败';
        }
        $this->ajaxReturn($message,'JSON');

    }


    public static function curl_post($url,$array){
        $header = array(
            'Content-Type:application/json',
        );
        $curl = curl_init();
        //设置提交的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data = $array;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        $error = curl_error($curl);
        //关闭URL请求
        curl_close($curl);
        if($error) throw new Exception('请求发生错误：' . $error);
        //获得数据并返回
        return $data;
    }

}
