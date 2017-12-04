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

function ajaxCarList(){
    var row = $('#OrderGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择运单", 'info');return false;
    }
    $('#carorderDlg').dialog('open').dialog('setTitle','发车列表');
    $('#carorderGrid').datagrid({
        url:ajaxCarUrl,
        columns:[[
            {field:'driver',title:'司机',width:150},
            {field:'carnumber',title:'车牌号',width:150},
            {field:'startdate',title:'发车时间',width:200,formatter:Common.TimeFormatter},
            {field:'cardriveid',title:'发车id',width:100,hidden:'true'}

        ]],
      /*  onLoadSuccess:function(data){
            $("a[name='lookButton']").linkbutton({plain:true,iconCls:'fa fa-check'});
        },*/
        toolbar: [{
            iconCls: 'fa fa-check',
            handler: function(){chooseCar(row.id);}
        }]
    });
}
function  btnDetailed(id){
   id=row.id;
    alert(id);
    var OpeninfoIntroButton = '<a href="javascript:void(0);" name="lookButton" class="easyui-linkbutton"  onclick="chooseCar('+id+')"></a>';
    return OpeninfoIntroButton;
}
function RouteformatStatus(val,rowData,row){
    if(val==1){
        val="<span style='color: green'>启用</span>";
    }else {
        val="<span style='color: red'>禁用</span>";
    }
    return val;
}
function chooseCar(orderid){
    var row = $('#carorderGrid').datagrid('getSelected');//发车行
    var orderid=orderid;//运单id
    if(row==null){
        $.messager.alert('Warning',"请选择发车", 'info');return false;
    }
    if (row){
        $.messager.confirm('装车提示','真的要装车?',function(r){
            if (r){
                var durl=chooseCarUrl;//装车更新哪一个数据库表
                $.getJSON(durl,{id:row.cardriveid,orderid:orderid},function(result){
                    if (result.status){
                        $('#carorderDlg').dialog('close');
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

/*
function cascade(route) {
    $("#"+route).combobox({
        url:ajaxRouteUrl,
        valueField:'id',
        textField:'name'
    });
}*/

function timeStatus(val,rowData,row){
    if(val==null){
        return "";
    }else{
    return Common.TimeFormatter(val);
    }
}
function Status(val,rowData,row){
    if(val==0){
        return "";
    }else if(val==1){
        return "已装车";
    }else if(val==2){
        return "已到站";
    }
}