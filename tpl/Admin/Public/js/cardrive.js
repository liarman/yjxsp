var url;
function addCardrive(){
    $('#addCardrive').dialog('open').dialog('setTitle','添加');
    $('#addCardriveForm').form('clear');

    url= addCardriveUrl ;
}

function addCardriveSubmit(){
    $('#addCardriveForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addCardrive').dialog('close');
                $('#CardriveGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addCardrive').dialog('close');
                $('#CardriveGrid').datagrid('reload');
            }
        }
    });
}
function editCardriveSubmit(){
    $('#editCardriveForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editCardrive').dialog('close');
                $('#CardriveGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editCardrive').dialog('close');
                $('#CardriveGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editCardrive(){
    var row = $('#CardriveGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#editCardrive').dialog('open').dialog('setTitle','编辑');
        $('#editCardriveForm').form('load',row);
        url = editCardriveUrl +'/id/'+row.id;
    }
}

function destroyCardrive(){
    var row = $('#CardriveGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl= deleteCardriveUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#CardriveGrid').datagrid('reload');    // reload the user data
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
function cararrive(){
    var row = $('#CardriveGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择发出车辆", 'info');return false;
    }
    $('#arriveDlg').dialog('open').dialog('setTitle','到站信息');
    $('#arriveGrid').datagrid({
        url: addarriveListUrl +'/cardriveid/'+row.cardriveid,
        columns:[[
            {field:'name',title:'到达地',width:100,align:'center'},
            {field:'arrivedate',title:'到达时间',width:270,align:'center',formatter:Common.TimeFormatter}
        ]],
        toolbar: [{
            iconCls: 'fa fa-plus',
            handler: function(){addarrive(row);}
        }]
    });
}
function orderList(){
    var row = $('#CardriveGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要查看的运单的车辆", 'info');return false;
    }
    $('#orderListDlg').dialog('open').dialog('setTitle','运单列表');
    $('#orderListGrid').datagrid({
        url: orderListUrl +'/id/'+row.cardriveid,
        singleSelect: false,
        selectOnCheck: true,
        checkOnSelect: true,
        columns: [[
            {field: 'ck', checkbox:"true", width: 100},
            {field: 'oid', title: '运单id',hidden:'true' ,width: 100},
            {field: 'orderno', title: '运单编号', width: 100},
            {field: 'shipper', title: '寄件人姓名', width: 100},
            {field: 'shippertel', title: '寄件人电话', width: 100},
            {field: 'receivername', title: '收件人姓名', width: 100},
            {field: 'receiveraddress', title: '收件人电话', width: 100},
            {field: 'receivertel', title: '收件人地址', width: 100},
            {field: 'rname', title: '目的地', width: 100},
        ]],
        onLoadSuccess:function(data){
           if(data){
               $.each(data.rows, function(index, item){
                   if(item.checked){
                      $('#orderListGrid').datagrid('checkRow', index);
                        }
                    });
               }
           },
        toolbar: [{
            iconCls: 'fa fa-file-text-o',
            id:'ButonGetCheck',
            text:'打印',
            handler: function(){printList();}
        }]
    });
}
function printList(){
    $('#ButonGetCheck').click(function(){
        var checkedItems = $('#orderListGrid').datagrid('getChecked');
        var ids = [];
        $.each(checkedItems, function(index, item){
            ids.push(item.oid);
            });
        ids=ids.join("@@");
        if (ids.length>0) {
            var url=printUrl;
            $.getJSON(url,{id:ids}, function(datap){
                console.log(datap);
                var LODOP=getLodop(document.getElementById('LODOP'),document.getElementById('LODOP_EM'));
                LODOP.PRINT_INIT("");
                LODOP.SET_PRINT_STYLE("FontSize",12);
                var y=150;
                $.each(datap,function (i,n) {
                    LODOP.ADD_PRINT_TEXT(y,0,100,30,n.shipper);
                    LODOP.ADD_PRINT_TEXT(y,100,100,30,n.goodsname);
                    LODOP.ADD_PRINT_TEXT(y,200,300,30,n.receiveraddress+"  "+n.receivername+"  "+n.receivertel);
                    LODOP.ADD_PRINT_TEXT(y,500,55,30,n.goodscount);
                    y+=34;
                });
                LODOP.PREVIEW();
            });
        }else {
            alert("要打印运单列表为空！");
            return;
        }

        });

    /*var selRow = $('#orderListGrid').datagrid("getChecked");
    if(selRow.length==0){
        alert("请至少选择一行数据!");
        return false;
    }
    var ids=[];
    for (var i = 0; i < selRow.length; i++) {
        //获取自定义table 的中的checkbox值
        var id=selRow[i].OTRECORDID;   //OTRECORDID这个是你要在列表中取的单个id
        ids.push(id); //然后把单个id循环放到ids的数组中
    }
    console.log(ids);*/


}
function addarrive(row){
    $('#addarrive').dialog('open').dialog('setTitle','添加');
    $('#addarriveForm').form('clear');
    $('#route').combobox({});
    $('#addarrive_id').val(row.id);
    $('#cardrive_id').val(row.cardriveid);
    url= addarriveUrl;
}
function addarriveSubmit(){
    $('#addarriveForm').form('submit',{
        url: addarriveUrl,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addarrive').dialog('close');
                $('#arriveGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addarrive').dialog('close');
                $('#arriveGrid').datagrid('reload');
            }
        }
    });
}


$('#number').combobox({});
