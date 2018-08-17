function formatDetail(val,rowData,row){
    if(rowData['townid']>0){
        val="<span class='icon icon-detail' onclick='detail("+rowData['id']+")'></span>";
    }else {
        val="";
    }

    return val;
}
function detail (id){
    window.open(detailUrl+"/id/"+id);
}
function weddingStatus(val,rowData,row){
    if(val==1){
        val="未婚";
    }else if(val==2){
        val="已婚";
    }else if (val==3){
        val="离异";
    }else if(val==4){
        val="丧偶";
    }
    return val;
}