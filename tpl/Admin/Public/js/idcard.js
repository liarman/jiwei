var url;
function uploadSubmit() {
    $.ajaxFileUpload( {
        url: impIdcardUrl,
        secureuri: false,
        fileElementId: 'uploadIdcard',
        dataType: 'json',
        success: function (data) {
            if (data.status == 1) {
                $.messager.alert('Info', data.message, 'info');
                $('#IdcardGrid').datagrid('reload');
            } else {
                $.messager.alert('Warning', data.message, 'info');
                $('#IdcardGrid').datagrid('reload');
            }
        }
    });
}

function ajaxRequest(){
    var row = $('#IdcardGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要检验的行", 'info');return false;
    }
    if (row){
        var id=row.id;
        $.messager.confirm('提示','真的要检验?',function(r){
            if (r){
                var durl=lookUrl;
                $.getJSON(durl,{id:id},function(result){
                    if (result.status){
                        $('#IdcardGrid').datagrid('reload');    // reload the user data
                    } else {
                        $.messager.alert('错误提示',result.message,'error');
                    }
                },'json').error(function(data){
                    var info=eval('('+data.responseText+')');
                    $.messager.confirm('错误提示',info.message,function(r){});
                });
            }
        });
    }
}
function  ResponseValue(){
    var row = $('#IdcardGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要查看的行", 'info');return false;
    }
    if (row){
        var id=row.id;
        $('#resopnseValueDlg').dialog('open').dialog('setTitle','检验结果');
        $('#resopnseValueGrid').datagrid({
            url:responseUrl+'/idcardid/'+id,
            columns:[[
                {field:'cardid',title:'身份证号码',width:150},
                {field:'name',title:'姓名',width:80},
                {field:'responsevalue',title:'结果',width:110}
            ]]
        });
    }
}

function  lookResponseValue(){
    var row = $('#IdcardGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要查看的行", 'info');return false;
    }
    if (row){
        if(row.responsevalue){
            $('#lookResponse').dialog('open').dialog('setTitle','查看');
            $('#lookResponseForm').form('load',row);
        }else{
            $.messager.alert('Warning',"选择的行没校验，请先进行校验！", 'info');return false;
        }
    }
}
