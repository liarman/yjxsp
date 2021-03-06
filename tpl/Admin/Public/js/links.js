var piceditor;
KindEditor.ready(function(K) {
    piceditor = K.editor({
        allowFileManager:false
    });
    K('#addlinkimg').click(function() {
        piceditor.loadPlugin('image', function() {
            piceditor.plugin.imageDialog({
                fileUrl : K('#thumb').val(),
                clickFn : function(url, title) {
                    $('.addimg').textbox("setValue", GLOBALUrl +url);
                    piceditor.hideDialog();
                }
            });
        });
    });
    K('#editlinkimg').click(function() {
        piceditor.loadPlugin('image', function() {
            piceditor.plugin.imageDialog({
                fileUrl : K('#thumb').val(),
                clickFn : function(url, title) {
                    $('.editimg').textbox("setValue", GLOBALUrl +url);
                    piceditor.hideDialog();
                }
            });
        });
    });
});
var url;
function addLink(){
    $('#addLink').dialog('open').dialog('setTitle','添加轮播图');
    $('#addLinkForm').form('clear');
    url= addUrl ;
}
function addLinkSubmit(){
    $('#addLinkForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addLink').dialog('close');
                $('#linkGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addLink').dialog('close');
                $('#linkGrid').datagrid('reload');
            }
        }
    });
}
function editLinkSubmit(){
    $('#editLinkForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editLink').dialog('close');
                $('#linkGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editLink').dialog('close');
                $('#linkGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editLink(){
    var row = $('#linkGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#editLink').dialog('open').dialog('setTitle','编辑');
        $('#editLinkForm').form('load',row);
        url = editUrl +'/id/'+row.id;
    }
}
function destroyLink(){
    var row = $('#linkGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl= deleteUrl ;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#linkGrid').datagrid('reload');    // reload the user data
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
function formatCat(val,rowData,row){
    if(val==1){
        val="<span style='color: green'>首页</span>";
    }else {
        val="<span style='color: green'>超市</span>";
    }
    return val;
}