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
        $name=I("post.name");
        $cardid=I("post.idcard");
        $status=I("post.status");
        $offset = ($page - 1) * $rows;
        $countsql = "select count(r.id) AS total from qfant_person r where 1=1 ";
        $sql = "SELECT i.*  FROM qfant_person i where 1=1 ";
        $param = array();
        if(!empty($name)){
            $countsql.=" and r.name like '%s' ";
            $sql.=" and i.name like '%s' ";
            array_push($param,'%'.$name.'%');
        }
        if(!empty($status)){
            $countsql.=" and r.status  =%d ";
            $sql.=" and i.status  =%d ";
            array_push($param,$status);
        }if($status=="1"){
            $countsql.=" and r.status  =%d ";
            $sql.=" and i.status  =%d ";
            array_push($param,$status);
        }if($status=="0"){
            $countsql.=" and r.status  =%d ";
            $sql.=" and i.status  =%d ";
            array_push($param,$status);
    }
        if(!empty($cardid)){
            $countsql.=" and r.cardid like '%s' ";
            $sql.=" and i.cardid like '%s' ";
            array_push($param,'%'.$cardid.'%');
        }
        array_push($param, $offset);
        array_push($param, $rows);
        $sql .= " limit %d,%d";
        $data = D('Person')->query($countsql, $param);
        $result['total'] = $data[0]['total'];
        $data = D('Person')->query($sql, $param);
        $result["rows"] = $data;
      //  var_dump($data);die;
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
              /*  if (empty($id)) {*/
                    $data['name'] = $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
                    $data['cardid'] = $cardid;
                    M('Idcard')->add($data);

                    $message['status'] = 1;
                    $message['message'] = '导入完成，保存成功!';
             /*   } else {
                    $message['status'] = 0;
                    $message['message'] = '第' . $i . '行身份证号重复，保存失败!';
                    break;
                }*/
            }
        } else {
            $message['status'] = 0;
            $message['message'] = '请选择上传文件！';
        }

        $this->ajaxReturn($message, 'JSON');

    }

    function  look()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $pagesize = 50;
        $total=D('Idcard')->count();
        $pagecount = $total % $pagesize == 0 ? $total/$pagesize : ceil($total/$pagesize); //页码总数
        $url = 'http://59.203.96.98:8000/';

        for($page;$page<=$pagecount;$page++){
            $sql ="select * from qfant_idcard ";
            $sql.= " limit ".(($page-1)*$pagesize).",".$pagesize;
            $dataCode = D('Idcard')->query($sql,"");
            $res = $this->curl_post($url, $dataCode);

            sleep(2);
        }
        if($res){
            $message['status']=1;
            $message['message']='检验成功';
        }else {
            $message['status']=0;
            $message['message']='检验失败';
        }
        $this->ajaxReturn($message,'JSON');

    }
    public function pinString($idcard){
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
                            "value": "<condition><item><GMSFHM>'.$idcard.'</GMSFHM></item></condition>"
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
        return $da;
    }

    public function curl_post($url,$dataCode){
        $header = array(
            'Content-Type:application/json',
        );

        $ch_list = array();
        $multi_ch = curl_multi_init();
        foreach($dataCode as $i=>$v){
            $post_data = $this->pinString($v["cardid"]);;
            $ch_list[$i] = curl_init();
            //设置提交的url
            curl_setopt($ch_list[$i], CURLOPT_URL, $url);
            //设置头文件的信息作为数据流输出
            curl_setopt($ch_list[$i], CURLOPT_HTTPHEADER, $header);
            //设置获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($ch_list[$i], CURLOPT_RETURNTRANSFER, 1);
            //设置post方式提交
            curl_setopt($ch_list[$i], CURLOPT_POST, 1);
            //设置post数据
            curl_setopt($ch_list[$i], CURLOPT_POSTFIELDS, $post_data);
            curl_multi_add_handle($multi_ch, $ch_list[$i]);

            $active = null;
            do {
                $mrc = curl_multi_exec($multi_ch, $active); //处理在栈中的每一个句柄。无论该句柄需要读取或写入数据都可调用此方法。
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            //该函数仅返回关于整个批处理栈相关的错误。即使返回 CURLM_OK 时单个传输仍可能有问题。
            while ($active && $mrc == CURLM_OK) {
                if (curl_multi_select($multi_ch) != -1) { //阻塞直到cURL批处理连接中有活动连接。
                    do {
                        $mrc = curl_multi_exec($multi_ch, $active);
                    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                }
            }
            //获取http返回的结果
            //  foreach ($ch_list as $k => $ch) {
            $result=curl_multi_getcontent($ch_list[$i]);//获取返回的结果
            $rescode =$this->addResponse($v,$result);
            curl_multi_remove_handle($multi_ch,$ch_list[$i]);
            curl_close($ch_list[$i]);
            //   }
        }
        //关闭URL请求
        curl_multi_close($multi_ch);
        //curl_close($curl);
        // if($error) throw new Exception('请求发生错误：' . $error);
        //获得数据并返回
        return $rescode ;
    }

    /**
     * 获取结果添加到数据库
     */
    public function  addResponse($v,$result){
        $responseStr=$this->transfunction($v,$result);
       /* $data['responsevalue']=$responseStr;
        $where['id']=$v['id'];;
        $result=D('Idcard')->editData($where,$data);*/
        return $responseStr;
    }

    /**
     * json转换字符串
     */
    function  transfunction($v,$result){
        $post_object = json_decode($result);
        $data1=$post_object->response;
        $data2=$data1->value;
        $resp=$data2->value;//保存的是xml
        $xml = simplexml_load_string( $resp);
        $jsonStr = json_encode($xml);
        $jsonArray = json_decode($jsonStr,true);
        $codearr= $jsonArray['body']['resultList'];//保存数组
        $coarr= $codearr['result'];
        if($coarr[0]){
            foreach( $coarr as $c){
                $data['qymc']=$c["QYMC"];
                $data['shtyxydm']=$c["SHTYXYDM"];
                $data['gmsfhm']=$c["GMSFHM"];
                $data['jydz']=$c["JYDZ"];
                $data['yqygx']=$c["YQYGX"];
                $data['xm']=$c["XM"];
                $data['idcardid']=$v['id'];
                unset($data['id']);
                D('IdcardResponse')->addData($data);
                $da1['status']=1;//存在公司,改变个人数据状态
                $where['id']=$v['id'];;
                $res= D('Idcard')->editData($where,$da1);
            }
        }
        return  $res;

    }

    function  lookres(){
        $id=I('get.idcardid');
        $sql="select * from qfant_company where idcardid='$id'";
        $data=D('Company')->query($sql,"");
        $this->ajaxReturn($data,'JSON');
    }

}
