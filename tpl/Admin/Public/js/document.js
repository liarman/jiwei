var url;
function addDocument(){
    $('#addDocument').dialog('open').dialog('setTitle','添加');
    $('#addDocumentForm').form('clear');
    url=addDocumentUrl;
}

function addDocumentSubmit(){
    $('#addDocumentForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addDocument').dialog('close');
                $('#documentGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addDocument').dialog('close');
                $('#documentGrid').datagrid('reload');
            }
        }
    });
}
function editDocumentSubmit(){
    $('#editDocumentForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editDocument').dialog('close');
                $('#documentGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editDocument').dialog('close');
                $('#documentGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editDocument(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#editDocument').dialog('open').dialog('setTitle','编辑');

        $('#editDocumentForm').form('load',row);
          url =editDocumentUrl+'/id/'+row.id;
    }
}

function destroyDocument(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deleteDocumentUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#documentGrid').datagrid('reload');    // reload the user data
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

function doSearch(){
    $('#documentGrid').datagrid('load',{
        name: $("input[name='name']").val()
    });
}
function weddingStatus(val,rowData,row){
    if(val==1){
        val="未婚";
    }else if(val==2){
        val="已婚";
    }else if (val==3){
        val="离异";
    }else{
        val="丧偶";
    }
    return val;
}
function familyList(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择一个干部档案", 'error');return false;
    }
    if (row){
        $('#familyDlg').dialog('open').dialog('setTitle','家庭成员及重要社会关系列表');
        $('#familyGrid').datagrid({
            url: ajaxFamilyListUrl +'/document_id/'+row.id,
            columns:[[
                {field:'appellation',title:'称谓',width:100,align:'center'},
                {field:'name',title:'姓名',width:50,align:'center'},
                {field:'face',title:'政治面貌',width:100,align:'center'},
                {field:'workplace',title:'工作单位及职务',width:100,align:'center'},
                {field:'idcard',title:'身份证',width:100,align:'center'}
            ]],
            toolbar: [{
                iconCls: 'fa fa-plus',
                handler: function(){addFamily(1,row.id);}
            }, '-', {
                iconCls: 'fa fa-edit',
                handler: function () {
                    addFamily(2,row.id)
                }
            }, '-', {
                iconCls: 'fa fa-remove',
                handler: function () {
                    destroyFamily(row.id)
                }
            }]
        });
    }
}
function destroyFamily(){
    var row = $('#familyGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deleteFamilyUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#familyGrid').datagrid('reload');    // reload the user data
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
//编辑会员对话窗
function addFamily(type,document_id){
    if(type==1){
        $('#addFamily').dialog('open').dialog('setTitle','添加');
        $("#addFamilyForm").form('reset');
        $("#document_id").val(document_id);
        $("#family_id").val();
    }else {
        $("#document_id").val(document_id);
        var row = $('#familyGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#addFamily').dialog('open').dialog('setTitle','编辑');
            $('#addFamilyForm').form('load',row);
        }
    }
}
function addFamilySubmit(){
    $('#addFamilyForm').form('submit',{
        url: addFamilyUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addFamily').dialog('close');
                $('#familyGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addFamily').dialog('close');
                $('#familyGrid').datagrid('reload');
            }
        }
    });
}
$(document).ready(init);
function init() {
    $('#documentGrid').datagrid({
       /* url: ajaxorderurl,
        singleSelect: false,
        selectOnCheck: true,
        checkOnSelect: true,
        columns: [[
            {field: 'ck', checkbox:"true", width: 100},
            {field: 'orderno', title: '运单编号', width: 160},
            {field: 'shipper', title: '发货人姓名', width: 80},
            {field: 'shippertel', title: '发货人电话', width: 110},
            {field: 'goodsname', title: '货物名称', width: 80},
            {field: 'goodscount', title: '件数', width: 110},
            {field: 'receivername', title: '收件人姓名', width: 100},
            {field: 'receiveraddress', title: '收件人电话', width: 100},
            {field: 'receivertel', title: '收件人地址', width: 100}
        ]],*/
        onLoadSuccess:function(data){
            if(data){
                $.each(data.rows, function(index, item){
                    if(item.checked){
                        $('#documentGrid').datagrid('checkRow', index);
                    }
                });
            }
        }/*,
        toolbar: [{
            iconCls: 'fa fa-truck',
            id:'carButton',
            text:'装车',
            handler: function(){ajaxCarList();}
        }]*/
    });
}