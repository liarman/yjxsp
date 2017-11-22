var url;
function addEquipment(){
    $('#addEquipment').dialog('open').dialog('setTitle','添加');
    $('#addEquipmentForm').form('clear');
    url="{:U('Admin/Equipment/addEquipment')}";
}

function addEquipmentSubmit(){
    $('#addEquipmentForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addEquipment').dialog('close');
                $('#EquipmentGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addEquipment').dialog('close');
                $('#EquipmentGrid').datagrid('reload');
            }
        }
    });
}
function editEquipmentSubmit(){
    $('#editEquipmentForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editEquipment').dialog('close');
                $('#EquipmentGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editEquipment').dialog('close');
                $('#EquipmentGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editEquipment(){
    var row = $('#EquipmentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#editEquipment').dialog('open').dialog('setTitle','编辑');
        $('#editEquipmentForm').form('load',row);
        url ="{:U('Admin/Equipment/editEquipment')}"+'/id/'+row.id;
    }
}

function destroyEquipment(){
    var row = $('#EquipmentGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl="{:U('Admin/Equipment/deleteEquipment')}";
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#EquipmentGrid').datagrid('reload');    // reload the user data
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

function  formatArea(val,rowData,row){
    if(val==1){
        val="谯城区";
    }else if(val==2){
        val="涡阳县";
    }else if(val==3){
        val="蒙城县";
    }else if(val==4){
        val="利辛县";
    }else{

    }
    return val;
}