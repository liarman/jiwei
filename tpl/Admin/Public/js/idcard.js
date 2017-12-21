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

