var url;
function addDistrict(){
    $('#addDistrict').dialog('open').dialog('setTitle','添加');
    $('#addDistrictForm').form('clear');
    url= addUrl;
}
function addDistrictSubmit(){
    $('.addcontent').val(editor.html());
    $('#addDistrictForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addDistrict').dialog('close');
                $('#districtGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addrule').dialog('close');
                $('#districtGrid').datagrid('reload');
            }
        }
    });
}
function editDistrictSubmit(){
    $('.editcontent').val(editor2.html());
    $('#editDistrictForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editDistrict').dialog('close');
                $('#districtGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editDistrict').dialog('close');
                $('#districtGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editDistrict(){
    var row = $('#districtGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#editDistrict').dialog('open').dialog('setTitle','编辑');
        $('#editDistrictForm').form('load',row);
        editor2.html(row.contact);
        url = editUrl +'/id/'+row.id;
    }
}
function destroyDistrict(){
    var row = $('#districtGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl= deleteUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#districtGrid').datagrid('reload');    // reload the user data
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
function getChecked(nodes){
    var s = '';
    for (var i = 0; i < nodes.length; i++) {
        if (s != '')
            s += ',';
        s += nodes[i].id;
    }
    return s;
}

var editor;
KindEditor.ready(function(K) {
    editor = K.create('.addcontent', {
        allowFileManager : false
    });
});
var editor2;
KindEditor.ready(function(K) {
    editor2 = K.create('.editcontent', {
        allowFileManager : false
    });
});