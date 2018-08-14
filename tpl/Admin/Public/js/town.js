
var url;
function addTown(){
    $('#addTown').dialog('open').dialog('setTitle','添加乡镇');
    $('#addTownForm').form('clear');
    url=addTownUrl;
}
function addTownSubmit(){
    $('#addTownForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addTown').dialog('close');
                $('#townGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addTown').dialog('close');
                $('#townGrid').datagrid('reload');
            }
        }
    });
}
function editTownSubmit(){
    $('#editTownForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editTown').dialog('close');
                $('#townGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editTown').dialog('close');
                $('#townGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editTown(){
    var row = $('#townGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#addTown').dialog('open').dialog('setTitle','编辑');
        $('#addTownForm').form('load',row);
        url =addTownUrl+'/id/'+row.id;
    }
}
function destroyTown(){
    var row = $('#townGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deleteTownUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#townGrid').datagrid('reload');    // reload the user data
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
