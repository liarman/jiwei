<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台权限管理
 */
class IdcardResponseController extends AdminBaseController{

    /**
     *
     * 查看
     */
    public  function look(){
        $data=I('get.');
        $idcardid=$data['idcardid'];
        $sql = "select  i.name as name ,i.cardid as cardid ,r.responsevalue as responsevalue from qfant_idcard_response r  ,qfant_idcard i where r.idcardid=i.id and i.id='$idcardid'";
        $data=D('IdcardResponse')->query($sql,"");
        $this->ajaxReturn($data,'JSON');
    }


}
