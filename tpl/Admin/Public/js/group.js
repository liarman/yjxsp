var url;
var loading = false; //为防止onCheck冒泡事件设置的全局变量
//添加会员对话窗
function giveAuth(){
    var row = $('#groupGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择一个用户组", 'info');return false;
    }
    $('#giveAuthGroup').dialog('open').dialog('setTitle','分配权限');
    $('#giveAuthForm').form('clear');
    $('#authtree').tree({
        url: "{:U('Admin/Rule/ajaxAuthTree')}/id/"+row.id,
        checkbox:true,
        onBeforeLoad: function (node, param) {
            loading = true;
        },
        onLoadSuccess: function (node, data) {
            loading = false;
        },
        onCheck: function (node, checked) {
            if (loading) {
                return;
            } else {
                var rules=getChecked($('#authtree').tree('getChecked'));
                $.post("{:U('Admin/Rule/rule_group')}", {
                    rules: rules,
                    id: row.id
                }, function (result) {
                    if (result == "fail")
                        alert("操作失败");
                });
            }
        }
    });
}
function addGroup(){
    $('#addGroup').dialog('open').dialog('setTitle','添加用户组');
    $('#addGroupForm').form('clear');
    url="{:U('Admin/Rule/add_group')}";
}
function addGroupSubmit(){
    $('#addGroupForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addGroup').dialog('close');
                $('#groupGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addrule').dialog('close');
                $('#groupGrid').datagrid('reload');
            }
        }
    });
}
function editGroupSubmit(){
    $('#editGroupForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editGroup').dialog('close');
                $('#groupGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editGroup').dialog('close');
                $('#groupGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editGroup(){
    var row = $('#groupGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#editGroup').dialog('open').dialog('setTitle','编辑');
        $('#editGroupForm').form('load',row);
        url ="{:U('Admin/Nav/edit_group')}"+'/id/'+row.id;
    }
}
function destroyGroup(){
    var row = $('#groupGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl="{:U('Admin/Rule/delete_group')}";
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#groupGrid').datagrid('reload');    // reload the user data
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