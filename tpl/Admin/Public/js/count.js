/**
 * Created by zhanghui on 2018/9/20.
 */
function doSearch(){
    $('#documentGrid').datagrid('load',{
        name: $("input[name='name']").val(),
        tname: $("#document_search_depart").combotree("getValue")
    });
}


