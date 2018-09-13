var url;
//添加会员对话窗
function add(id){
    $('#addDepartment').dialog('open').dialog('setTitle','添加权限');
    $('#departmentForm').form('clear');
    $(".parent").empty();
    if(id>0){
        $("input[name='pid']").val(id);
        $.getJSON(getDepartmentUrl,{id:id},function(data){
            $(".parent").append(data.name);
        })
    }
    url =addDepartmentUrl;
}
function addDepartmentSubmit(){
    $('#departmentForm').form('submit',{
        url: addDepartmentUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addDepartment').dialog('close');
                $('#departmentGrid').treegrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addDepartment').dialog('close');
                $('#departmentGrid').treegrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editDepartment(id){
    if (id>0){
        $('#addDepartment').dialog('open').dialog('setTitle','编辑');
        $.getJSON(getDepartmentUrl,{id:id},function(data){
            $(".parent").empty().append(data.pname)
            $("#departmentPid").val(data.pid);
            $("#departmentId").val(data.id);
            $("#departmentSort").textbox('setValue',data.sort);
            $("#departmentName").textbox('setValue',data.name);
        })
        url =addDepartmentUrl+'/id/'+id;
    }
}
function deleteDepartment(id){
    $.messager.confirm('删除提示','确定要删除吗?',function(r){
        if (r){
            var durl=deleteDepartmentUrl;
            $.getJSON(durl,{id:id},function(result){
                if (result.status){
                    $('#departmentGrid').treegrid('reload');    // reload the user data
                } else {
                    $.messager.alert('错误提示',result.message,'error');
                }
            },'json').error(function(data){
                var info=eval('('+data.responseText+')');
                $.messager.confirm('错误提示',info.message,function(r){
                });
            });
        }
    });
}

function formatAction(value,row,index){
    id=row.id;
    var add = '<a href="javascript:void(0);" onclick="add('+id+')">添加子部门</a> ';
    var del = '<a href="javascript:void(0);" onclick="deleteDepartment('+id+')">删除</a>';
    var edit = '<a href="javascript:void(0);" onclick="editDepartment('+id+')">编辑</a>';
    return add+"&nbsp;&nbsp;"+edit+"&nbsp;&nbsp;"+del;
}
