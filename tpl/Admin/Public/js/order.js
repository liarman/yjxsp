var url;
function addOrder(){
    $('#addOrder').dialog('open').dialog('setTitle','添加');
    $('#addOrderForm').form('clear');
    url=addOrderUrl;
}

function addOrderSubmit(){
    $('#addOrderForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addOrder').dialog('close');
                $('#OrderGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addOrder').dialog('close');
                $('#OrderGrid').datagrid('reload');
            }
        }
    });
}
function editOrderSubmit(){
    $('#editOrderForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editOrder').dialog('close');
                $('#OrderGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editOrder').dialog('close');
                $('#OrderGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editOrder(){
    var row = $('#OrderGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#editOrder').dialog('open').dialog('setTitle','编辑');
        $('#editOrderForm').form('load',row);
          url =editOrderUrl+'/id/'+row.id;
    }
}

function destroyOrder(){
    var row = $('#OrderGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl=deleteOrderUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#OrderGrid').datagrid('reload');    // reload the user data
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
    $('#OrderGrid').datagrid('load',{
        goodsname: $('#goodsnamesearch').val(),
        receivername: $('#receivernamesearch').val(),
        shipper: $('#shippersearch').val()
    });
}

function  formatUnit(val,rowData,row){
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