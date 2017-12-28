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
        $.messager.confirm('提示','确定要获取吗?',function(r){
            if (r){
                var durl=lookUrl;
                showOverlay();
                $.getJSON(durl,function(result){
                    console.log(result.message);
                    if (result.status){
                        hideOverlay();
                        $('#IdcardGrid').datagrid('reload');    // reload the user data
                    } else {
                        hideOverlay();
                        $.messager.alert('错误提示',result.message,'error');
                    }
                },'json').error(function(data){
                    hideOverlay();
                    var info=eval('('+data.responseText+')');
                    $.messager.confirm('错误提示',info.message,function(r){});
                });
            }
        });
}
function showOverlay() {
    $("#overlay").css("height",$(document).height());
    $("#overlay").css("width",$(document).width());
    // fadeTo第一个参数为速度，第二个为透明度
    // 多重方式控制透明度，保证兼容性，但也带来修改麻烦的问题
    $("#overlay").fadeTo(200, 0.5);
}
function hideOverlay() {
    $("#overlay").fadeOut(200);
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
            $('#lookResponse').dialog('open').dialog('setTitle','查看');
            $('#lookResponseForm').form('load',row);
    }
}


function Statustrans(val,rowData,row){
    if(val==1){
        val="<span style='color:red'>是</span>";
    }else {
        val="<span style='color: green'>否</span>";
    }
    return val;
}