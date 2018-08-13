var url;
function addDocument(){
    $('#addDocument').dialog('open').dialog('setTitle','添加');
    $('#addDocumentForm').form('clear');
    url=addDocumentUrl;
}
function formatDetail(val,rowData,row){
    console.log(row);
    val="<span class='icon icon-detail' onclick='detail("+rowData['id']+")'></span>";
    return val;
}
function detail (id){
    window.open(detailUrl+"/id/"+id);
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
//编辑会员对话窗
/**家庭成员列表**/
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
/**删除家庭成员**/
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
/**增加家庭成员**/
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
/**近亲属被追究刑事责任情况列表**/
function familyPunishList(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择一个干部档案", 'error');return false;
    }
    if (row){
        $('#familyPunishDlg').dialog('open').dialog('setTitle','近亲属被追究刑事责任情况');
        $('#familyPunishGrid').datagrid({

            url: ajaxPunishListUrl +'/document_id/'+row.id,
            columns:[[
                {field:'name',title:'姓名',width:50,align:'center'},
                {field:'punish_date',title:'被追究时间',width:100,align:'center'},
                {field:'punish_reason',title:'被追究原因',width:100,align:'center'},
                {field:'process_stage',title:'处理阶段',width:100,align:'center'},
                {field:'process_result',title:'处理结果',width:100,align:'center'}
            ]],
            toolbar: [{
                iconCls: 'fa fa-plus',
                handler: function(){addPunish(1,row.id);}
            }, '-', {
                iconCls: 'fa fa-edit',
                handler: function () {
                    addPunish(2,row.id)
                }
            }, '-', {
                iconCls: 'fa fa-remove',
                handler: function () {
                    destroyPunish(row.id)
                }
            }]
        });
    }
}

/**删除近亲属被追究刑事责任情况**/
function destroyPunish(){
    var row = $('#familyPunishGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deletePunishUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#familyPunishGrid').datagrid('reload');    // reload the user data
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
/**增加家庭成员**/
function addPunish(type,document_id){
    if(type==1){
        $('#addPunish').dialog('open').dialog('setTitle','添加');
        $("#addPunishForm").form('reset');
        $("#addPunish input[name='document_id']").val(document_id);
        $("#punish_id").val();
    }else {
        $("#addPunish input[name='document_id']").val(document_id);
        var row = $('#familyPunishGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#addPunish').dialog('open').dialog('setTitle','编辑');
            $('#addPunishForm').form('load',row);
        }
    }
}
function addPunishSubmit(){
    $('#addPunishForm').form('submit',{
        url: addPunishUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addPunish').dialog('close');
                $('#familyPunishGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addPunish').dialog('close');
                $('#familyPunishGrid').datagrid('reload');
            }
        }
    });
}


/**共同生活家庭成员每人年度总收入**/
function incomeList(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择一个干部档案", 'error');return false;
    }
    if (row){
        $('#incomeDlg').dialog('open').dialog('setTitle','共同生活家庭成员每人年度总收入');
        $('#incomeGrid').datagrid({
            url: ajaxIncomeListUrl +'/document_id/'+row.id,
            columns:[[
                {field:'name',title:'姓名',width:50,align:'center'},
                {field:'relation',title:'关系',width:100,align:'center'},
                {field:'year_income',title:'年收入（万元）',width:100,align:'center'},
            ]],
            toolbar: [{
                iconCls: 'fa fa-plus',
                handler: function(){addIncome(1,row.id);}
            }, '-', {
                iconCls: 'fa fa-edit',
                handler: function () {
                    addIncome(2,row.id)
                }
            }, '-', {
                iconCls: 'fa fa-remove',
                handler: function () {
                    destroyIncome(row.id)
                }
            }]
        });
    }
}

/**删除近亲属被追究刑事责任情况**/
function destroyIncome(){
    var row = $('#incomeGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deleteIncomeUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#incomeGrid').datagrid('reload');    // reload the user data
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
/**增加家庭成员**/
function addIncome(type,document_id){
    if(type==1){
        $('#addIncome').dialog('open').dialog('setTitle','添加');
        $("#addIncomeForm").form('reset');
        $("#addIncome input[name='document_id']").val(document_id);
        $("#income_id").val();
    }else {
        $("#addIncome input[name='document_id']").val(document_id);
        var row = $('#incomeGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#addIncome').dialog('open').dialog('setTitle','编辑');
            $('#addIncomeForm').form('load',row);
        }
    }
}
function addIncomeSubmit(){
    $('#addIncomeForm').form('submit',{
        url: addIncomeUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addIncome').dialog('close');
                $('#incomeGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addIncome').dialog('close');
                $('#incomeGrid').datagrid('reload');
            }
        }
    });
}



/**本人配偶共同生活子女共有房产**/
function houseList(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择一个干部档案", 'error');return false;
    }
    if (row){
        $('#houseDlg').dialog('open').dialog('setTitle','本人配偶共同生活子女共有房产');
        $('#houseGrid').datagrid({
            url: ajaxHouseListUrl +'/document_id/'+row.id,
            columns:[[
                {field:'name',title:'产权人姓名',width:100,align:'center'},
                {field:'house_source',title:'房产来源',width:100,align:'center'},
                {field:'area',title:'建筑面积（㎡）',width:100,align:'center'},
                {field:'buydate',title:'交易时间（年月）',width:100,align:'center'},
                {field:'address',title:'具体位置',width:100,align:'center'},
                {field:'house_property',title:'房产性质功能类型',width:100,align:'center'},
                {field:'trade_price',title:'交易价格（万元）',width:100,align:'center'},
            ]],
            toolbar: [{
                iconCls: 'fa fa-plus',
                handler: function(){addHouse(1,row.id);}
            }, '-', {
                iconCls: 'fa fa-edit',
                handler: function () {
                    addHouse(2,row.id)
                }
            }, '-', {
                iconCls: 'fa fa-remove',
                handler: function () {
                    destroyHouse(row.id)
                }
            }]
        });
    }
}

/**删除近亲属被追究刑事责任情况**/
function destroyHouse(){
    var row = $('#houseGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deleteHouseUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#houseGrid').datagrid('reload');    // reload the user data
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
/**增加家庭成员**/
function addHouse(type,document_id){
    if(type==1){
        $('#addHouse').dialog('open').dialog('setTitle','添加');
        $("#addHouseForm").form('reset');
        $("#addHouse input[name='document_id']").val(document_id);
        $("#house_id").val();
    }else {
        $("#addHouse input[name='document_id']").val(document_id);
        var row = $('#houseGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#addHouse').dialog('open').dialog('setTitle','编辑');
            $('#addHouseForm').form('load',row);
        }
    }
}
function addHouseSubmit(){
    $('#addHouseForm').form('submit',{
        url: addHouseUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addHouse').dialog('close');
                $('#houseGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addHouse').dialog('close');
                $('#houseGrid').datagrid('reload');
            }
        }
    });
}





/**本人配偶共同生活子女持股基金**/
function stockList(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择一个干部档案", 'error');return false;
    }
    if (row){
        $('#stockDlg').dialog('open').dialog('setTitle','本人配偶共同生活子女持股基金');
        $('#stockGrid').datagrid({
            url: ajaxStockListUrl +'/document_id/'+row.id,
            columns:[[
                {field:'name',title:'持有人姓名',width:100,align:'center'},
                {field:'stock_code',title:'股票名称或代码',width:100,align:'center'},
                {field:'stock_count',title:'持股数量',width:100,align:'center'},
                {field:'market_value',title:'填报前一交易日市值（万元）',width:200,align:'center'},
            ]],
            toolbar: [{
                iconCls: 'fa fa-plus',
                handler: function(){addStock(1,row.id);}
            }, '-', {
                iconCls: 'fa fa-edit',
                handler: function () {
                    addStock(2,row.id)
                }
            }, '-', {
                iconCls: 'fa fa-remove',
                handler: function () {
                    destroyStock(row.id)
                }
            }]
        });
    }
}

/**删除近亲属被追究刑事责任情况**/
function destroyStock(){
    var row = $('#stockGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deleteStockUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#stockGrid').datagrid('reload');    // reload the user data
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
/**增加家庭成员**/
function addStock(type,document_id){
    if(type==1){
        $('#addStock').dialog('open').dialog('setTitle','添加');
        $("#addStockForm").form('reset');
        $("#addStock input[name='document_id']").val(document_id);
        $("#stock_id").val();
    }else {
        $("#addStock input[name='document_id']").val(document_id);
        var row = $('#stockGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#addStock').dialog('open').dialog('setTitle','编辑');
            $('#addStockForm').form('load',row);
        }
    }
}
function addStockSubmit(){
    $('#addStockForm').form('submit',{
        url:addStockUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addStock').dialog('close');
                $('#stockGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addHouse').dialog('close');
                $('#stockGrid').datagrid('reload');
            }
        }
    });
}


/**持有护照**/
function passportList(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择一个干部档案", 'error');return false;
    }
    if (row){
        $('#passportDlg').dialog('open').dialog('setTitle','本人配偶共同生活子女持股基金');
        $('#passportGrid').datagrid({
            url: ajaxPassportListUrl +'/document_id/'+row.id,
            columns:[[
                {field:'passportno',title:'护照号（含港澳台通行证）',width:150,align:'center'},
                {field:'signdate',title:'签发日期',width:100,align:'center'},
                {field:'enddate',title:'有效期至',width:100,align:'center'},
                {field:'custodian',title:'保管机构',width:100,align:'center'},
                {field:'remark',title:'备 注',width:100,align:'center'},
            ]],
            toolbar: [{
                iconCls: 'fa fa-plus',
                handler: function(){addPassport(1,row.id);}
            }, '-', {
                iconCls: 'fa fa-edit',
                handler: function () {
                    addPassport(2,row.id)
                }
            }, '-', {
                iconCls: 'fa fa-remove',
                handler: function () {
                    destroyPassport(row.id)
                }
            }]
        });
    }
}

/**删除近亲属被追究刑事责任情况**/
function destroyPassport(){
    var row = $('#passportGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deletePassportUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#passportGrid').datagrid('reload');    // reload the user data
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
/**增加家庭成员**/
function addPassport(type,document_id){
    if(type==1){
        $('#addPassport').dialog('open').dialog('setTitle','添加');
        $("#addPassportForm").form('reset');
        $("#addPassport input[name='document_id']").val(document_id);
        $("#passport_id").val();
    }else {
        $("#addPassport input[name='document_id']").val(document_id);
        var row = $('#passportGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#addPassport').dialog('open').dialog('setTitle','编辑');
            $('#addPassportForm').form('load',row);
        }
    }
}
function addPassportSubmit(){
    $('#addPassportForm').form('submit',{
        url:addPassportUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addPassport').dialog('close');
                $('#passportGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addPassport').dialog('close');
                $('#passportGrid').datagrid('reload');
            }
        }
    });
}




/**出国境情况**/
function abroadList(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择一个干部档案", 'error');return false;
    }
    if (row){
        $('#abroadDlg').dialog('open').dialog('setTitle','出国境情况');
        $('#abroadGrid').datagrid({
            url: ajaxAbroadListUrl +'/document_id/'+row.id,
            columns:[[
                {field:'dest_country',title:'所到国家、地区',width:100,align:'center'},
                {field:'startdate',title:'去时间',width:100,align:'center'},
                {field:'enddate',title:'回来时间',width:100,align:'center'},
                {field:'reason',title:'事由',width:100,align:'center'},
                {field:'approval',title:'审批机构',width:100,align:'center'},
                {field:'passport',title:'所用护照',width:100,align:'center'},
            ]],
            toolbar: [{
                iconCls: 'fa fa-plus',
                handler: function(){addAbroad(1,row.id);}
            }, '-', {
                iconCls: 'fa fa-edit',
                handler: function () {
                    addAbroad(2,row.id)
                }
            }, '-', {
                iconCls: 'fa fa-remove',
                handler: function () {
                    destroyAbroad(row.id)
                }
            }]
        });
    }
}

/**出入境**/
function destroyAbroad(){
    var row = $('#abroadGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deleteAbroadUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#abroadGrid').datagrid('reload');    // reload the user data
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
/**增加家庭成员**/
function addAbroad(type,document_id){
    if(type==1){
        $('#addAbroad').dialog('open').dialog('setTitle','添加');
        $("#addAbroadForm").form('reset');
        $("#addAbroad input[name='document_id']").val(document_id);
        $("#abroad_id").val();
    }else {
        $("#addAbroad input[name='document_id']").val(document_id);
        var row = $('#abroadGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#addAbroad').dialog('open').dialog('setTitle','编辑');
            $('#addAbroadForm').form('load',row);
        }
    }
}
function addAbroadSubmit(){
    $('#addAbroadForm').form('submit',{
        url:addAbroadUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addAbroad').dialog('close');
                $('#abroadGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addAbroad').dialog('close');
                $('#abroadGrid').datagrid('reload');
            }
        }
    });
}



/**经商办企业情况**/
function companyList(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择一个干部档案", 'error');return false;
    }
    if (row){
        $('#companyDlg').dialog('open').dialog('setTitle','配偶子女其他近亲属经商办企业');
        $('#companyGrid').datagrid({
            url: ajaxCompanyListUrl +'/document_id/'+row.id,
            columns:[[
                {field:'name',title:'公司、企业名称',width:100,align:'center'},
                {field:'regdate',title:'注册时间',width:100,align:'center'},
                {field:'business',title:'经营业务范围',width:100,align:'center'},
                {field:'regaddress',title:'注册地',width:100,align:'center'},
                {field:'legal_person',title:'法人代表',width:100,align:'center'},
            ]],
            toolbar: [{
                iconCls: 'fa fa-plus',
                handler: function(){addCompany(1,row.id);}
            }, '-', {
                iconCls: 'fa fa-edit',
                handler: function () {
                    addCompany(2,row.id)
                }
            }, '-', {
                iconCls: 'fa fa-remove',
                handler: function () {
                    destroyCompany(row.id)
                }
            }]
        });
    }
}

/**出入境**/
function destroyCompany(){
    var row = $('#companyGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deleteCompanyUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#companyGrid').datagrid('reload');    // reload the user data
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
/**增加家庭成员**/
function addCompany(type,document_id){
    if(type==1){
        $('#addCompany').dialog('open').dialog('setTitle','添加');
        $("#addCompanyForm").form('reset');
        $("#addCompany input[name='document_id']").val(document_id);
        $("#company_id").val();
    }else {
        $("#addCompany input[name='document_id']").val(document_id);
        var row = $('#companyGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#addCompany').dialog('open').dialog('setTitle','编辑');
            $('#addCompanyForm').form('load',row);
        }
    }
}
function addCompanySubmit(){
    $('#addCompanyForm').form('submit',{
        url:addCompanyUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addCompany').dialog('close');
                $('#companyGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addCompany').dialog('close');
                $('#companyGrid').datagrid('reload');
            }
        }
    });
}




/**子女婚姻情况**/
function childWeddingList(){
    var row = $('#documentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择一个干部档案", 'error');return false;
    }
    if (row){
        $('#childWeddingDlg').dialog('open').dialog('setTitle','子女婚姻情况');
        $('#childWeddingGrid').datagrid({
            url: ajaxChildWeddingListUrl +'/document_id/'+row.id,
            columns:[[
                {field:'name',title:'子女姓名',width:100,align:'center'},
                {field:'mate_name',title:'配偶姓名',width:100,align:'center'},
                {field:'country',title:'国家、地区',width:100,align:'center'},
                {field:'work_dept',title:'工作（学习）单位',width:100,align:'center'},
                {field:'position',title:'职务',width:100,align:'center'},
                {field:'submit_date',title:'登记时间',width:100,align:'center'},
            ]],
            toolbar: [{
                iconCls: 'fa fa-plus',
                handler: function(){addChildWedding(1,row.id);}
            }, '-', {
                iconCls: 'fa fa-edit',
                handler: function () {
                    addChildWedding(2,row.id)
                }
            }, '-', {
                iconCls: 'fa fa-remove',
                handler: function () {
                    destroyChildWedding(row.id)
                }
            }]
        });
    }
}

/**出入境**/
function destroyChildWedding(){
    var row = $('#childWeddingGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deleteChildWeddingUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#childWeddingGrid').datagrid('reload');    // reload the user data
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
/**增加家庭成员**/
function addChildWedding(type,document_id){
    if(type==1){
        $('#addChildWedding').dialog('open').dialog('setTitle','添加');
        $("#addChildWeddingForm").form('reset');
        $("#addChildWedding input[name='document_id']").val(document_id);
        $("#abroad_id").val();
    }else {
        $("#addChildWedding input[name='document_id']").val(document_id);
        var row = $('#childWeddingGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#addChildWedding').dialog('open').dialog('setTitle','编辑');
            $('#addChildWeddingForm').form('load',row);
        }
    }
}
function addChildWeddingSubmit(){
    $('#addChildWeddingForm').form('submit',{
        url:addChildWeddingUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addChildWedding').dialog('close');
                $('#childWeddingGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addChildWedding').dialog('close');
                $('#childWeddingGrid').datagrid('reload');
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