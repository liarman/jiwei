/**
 * Created by zhanghui on 2018/9/20.
 */
function doSearch(){
    $('#documentGrid').datagrid('load',{
        name: $("input[name='name']").val(),
        tname: $("#document_search_depart").combotree("getValue")
    });
}
function formatDetail(val,rowData,row){
    //val="<span class='icon icon-detail'onclick='detail("+rowData['id']+")'></span>";
    detailBtn = '<a href="javascript:void(0);" onclick="detail('+rowData['id']+')">详情</a>';
    return detailBtn;
}

