var editor;
KindEditor.ready(function(K) {
    editor = K.create('.baseinfo', {
        allowFileManager : false
    });
});
var editor2;
KindEditor.ready(function(K) {
    editor2 = K.create('.editbaseinfo', {
        allowFileManager : false
    });
});
var url;
function addTown(){
    $('#addTown').dialog('open').dialog('setTitle','添加');
    $('#addTownForm').form('clear');
    url="{:U('Admin/Town/addTown')}";
}
function addTownSubmit(){
    $('.baseinfo').val(editor.html());
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
    $('.editbaseinfo').val(editor2.html());
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
        editor2.html(row.baseinfo);
        $('#editTown').dialog('open').dialog('setTitle','编辑');
        $('#editTownForm').form('load',row);
        url ="{:U('Admin/Town/editTown')}"+'/id/'+row.id;
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
                var durl="{:U('Admin/Town/deleteTown')}";
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