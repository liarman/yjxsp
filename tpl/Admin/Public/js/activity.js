var url;
var editor;
KindEditor.ready(function(K) {
    editor = K.create('.addintro', {
        allowFileManager : false
    });
});
var editor2;
KindEditor.ready(function(K) {
    editor2 = K.create('.editintro', {
        allowFileManager : false
    });
});

function addActivity(){
        $('#addActivity').dialog('open').dialog('setTitle','添加');
        $('#addActivityForm').form('clear');
        url=addActivityUrl;
}

function addActivitySubmit(){
    $('.addintro').val(editor.html());
    $('#addActivityForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addActivity').dialog('close');
                $('#villageGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addActivity').dialog('close');
                $('#villageGrid').datagrid('reload');
            }
        }
    });
}
function editActivitySubmit(){
    $('.editintro').val(editor2.html());
    $('#editActivityForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editActivity').dialog('close');
                $('#ActivityGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editActivity').dialog('close');
                $('#ActivityGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editActivity(){

        var row = $('#ActivityGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            editor2.html(row.intro);
            $('#editActivity').dialog('open').dialog('setTitle','编辑');
            $('#editActivityForm').form('load',row);
            url = editActivityUrl +'/id/'+row.id;
        }
    }



/*function editActivitySubmit(){
    $('.editintro').val(editor2.html());
    $('#editActivityForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editActivity').dialog('close');
                $('#villageGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editActivity').dialog('close');
                $('#villageGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editActivity(){
    var row = $('#activityGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        editor2.html(row.intro);//文本编辑器显示内容
        $('#editActivity').dialog('open').dialog('setTitle','编辑');
        $('#editActivityForm').form('load',row);
        url =editActivityUrl+'/id/'+row.id;
    }
}*/
function destroyActivity(){

        var row = $('#ActivityGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
        }
        if (row){
            $.messager.confirm('删除提示','真的要删除?',function(r){
                if (r){
                    var durl= deleteActivityUrl;
                    $.getJSON(durl,{id:row.id},function(result){
                        if (result.status){
                            $('#ActivityGrid').datagrid('reload');    // reload the user data
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
function openinfos() {
    var row = $('#villageGrid').datagrid('getSelected');
    if (row == null) {
        $.messager.alert('Warning', "请选择村部", 'info');
        return false;
    }
    $('#openinfoDlg').dialog('open').dialog('setTitle', '公开信息');
    $('#openinfoGrid').datagrid({
        url: "{:U('Admin/Activity/ajaxOpeninfos')}/village_id/" + row.id,
        singleSelect: true,
        columns: [[
            {field: 'name', title: '姓名', width: 100},
            {field: 'idcard', title: '身份证号码', width: 100},
            {field: 'sex', title: '性别', width: 100},
            {field: 'nation', title: '民族', width: 100},
            {field: 'birthday', title: '出生日期', width: 100},
            {field: 'educational', title: '学历', width: 100},
            {field: 'personalkind', title: '人员类别', width: 100},
            {field: 'partybranch', title: '所在党支部', width: 100},
            {field: 'jointime', title: '加入党组织日期', width: 100},
            {field: 'turntime', title: '转为正式党员日期', width: 100},
            {field: 'position', title: '工作岗位', width: 100},
            {field: 'address', title: '家庭住址', width: 100},
            {field: 'phone', title: '联系电话', width: 100},
            {field: 'tel', title: '固定电话', width: 100},
            {field: 'outwardflow', title: '外出流向', width: 100},
            {field:'look',title:'查看',width: 100,formatter: btnDetailed}
        ]],
        onLoadSuccess:function(data){
            $("a[name='lookOpenInfoIntroButton']").linkbutton({plain:true,iconCls:'icon-search'});
        },
        toolbar: [{
            iconCls: 'icon-add',
            handler: function () {
                addOpeninfo(row.id);
            }
        }, '-', {
            iconCls: 'icon-edit',
            handler: function () {
                editOpeninfo(row.id);
            }
        }, '-', {
            iconCls: 'icon-remove',
            handler: function () {
                destroyOpeninfo(row.id)
            }
        }, '-', {
            iconCls: 'icon-man',
            handler: function () {
                addProgress(row.id)
            }
        }]
    })
}

function  btnDetailed(value,row,index){
    id=row.id;
    var OpeninfoIntroButton = '<a href="javascript:void(0);" name="lookOpenInfoIntroButton" class="easyui-linkbutton"  onclick="openInfoIntro('+id+')"></a>';
    return OpeninfoIntroButton;
}
function openInfoIntro(){
    var row = $('#openinfoGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('错误',"请选择要查看的行", 'info');return false;
    }
    if (row){
        $('#lookOpeninfoIntro').dialog('open').dialog('setTitle','查看');
        $('#lookOpeninfoIntroForm').form('load',row);
    }
}
function addOpeninfo(village_id){
    $('#addOpeninfo').dialog('open').dialog('setTitle','添加');
    $('#openinfoAddForm').form('clear');
    $('#addOpeninfoId').val(village_id);
    url="{:U('Admin/Activity/addopeninfo')}";
}
function editOpeninfo(village_id){
    var row = $('#openinfoGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#editOpeninfo').dialog('open').dialog('setTitle','编辑');
        $('#openinfoEditForm').form('load',row);
        $('#editOpeninfoId').val(village_id);
        url ="{:U('Admin/Activity/editopeninfo')}"+'/id/'+row.id;
    }
}
function addOpeninfoSubmit(){
    $('#openinfoAddForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addOpeninfo').dialog('close');
                $('#openinfoGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addOpeninfo').dialog('close');
                $('#openinfoGrid').datagrid('reload');
            }
        }
    });
}
function editOpeninfoSubmit(){
    $('#openinfoEditForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editOpeninfo').dialog('close');
                $('#openinfoGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editOpeninfo').dialog('close');
                $('#openinfoGrid').datagrid('reload');
            }
        }
    });
}
function destroyOpeninfo(){
    var row = $('#openinfoGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl="{:U('Admin/Activity/deleteopeninfo')}";
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#openinfoGrid').datagrid('reload');    // reload the user data
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
function boundary() {
    var row = $('#villageGrid').datagrid('getSelected');
    if (row == null) {
        $.messager.alert('Warning', "请选择村部", 'info');
        return false;
    }
    $('#villageBoundaryDlg').dialog('open').dialog('setTitle', '行政边界');
    $('#villageBoundaryGrid').datagrid({
        url: "{:U('Admin/Activity/ajaxBoundary')}/village_id/" + row.id,
        columns: [[
            {field: 'longitude', title: '经度', width: 100},
            {field: 'latitude', title: '纬度', width: 100}
        ]],
        toolbar: [{
            iconCls: 'icon-add',
            handler: function () {
                addActivityBoundary(row.id);
            }
        }, '-', {
            iconCls: 'icon-edit',
            handler: function () {
                editActivityBoundary(row.id);
            }
        }, '-', {
            iconCls: 'icon-remove',
            handler: function () {
                destroyActivityBoundary(row.id)
            }
        }]
    })
}


function addActivityBoundary(village_id){
    $('#addActivityBoundary').dialog('open').dialog('setTitle','添加');
    $('#villageBoundaryAddForm').form('clear');
    $('#addActivityBoundaryActivityId').val(village_id);
    url="{:U('Admin/Activity/addActivityBoundary')}";
}
function editActivityBoundary(village_id){
    var row = $('#villageBoundaryGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#editActivityBoundary').dialog('open').dialog('setTitle','编辑');
        $('#villageBoundaryEditForm').form('load',row);
        $('#editActivityBoundaryActivityId').val(village_id);
        url ="{:U('Admin/Activity/editActivityBoundary')}"+'/id/'+row.id;
    }
}



function addActivityBoundarySubmit(){
    $('#villageBoundaryAddForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addActivityBoundary').dialog('close');
                $('#villageBoundaryGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addActivityBoundary').dialog('close');
                $('#villageBoundaryGrid').datagrid('reload');
            }
        }
    });
}
function editActivityBoundarySubmit(){
    $('#villageBoundaryEditForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editActivityBoundary').dialog('close');
                $('#villageBoundaryGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editActivityBoundary').dialog('close');
                $('#villageBoundaryGrid').datagrid('reload');
            }
        }
    });
}
function destroyActivityBoundary(){
    var row = $('#villageBoundaryGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl="{:U('Admin/Activity/deleteActivityBoundary')}";
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#villageBoundaryGrid').datagrid('reload');    // reload the user data
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

function type(val,rowData,row){
    if(val==0){
        val="村";
    }else if(val==1){
        val="社区";
    }else{
        val="企业";
    }
    return val;
}
function doSearch(){
    $('#villageGrid').datagrid('load',{
        name: $('#namesearch').val(),
        townid: $('#townsearch').combobox('getValue')

    });
}
$("#townsearch").combobox({
    url:"{:U('Admin/Town/ajaxTownAll')}",
    valueField:'id',
    textField:'name'
});
function attr(data){
    $('#progressTitle1').textbox('setValue', data[0].progresstitle1);
    $('#progressTime1').textbox('setValue', data[0].progresstime1);
    $('#progressTitle2').textbox('setValue', data[0].progresstitle2);
    $('#progressTime2').textbox('setValue', data[0].progresstime2);
    $('#progressTitle3').textbox('setValue', data[0].progresstitle3);
    $('#progressTime3').textbox('setValue', data[0].progresstime3);
    $('#progressTitle4').textbox('setValue', data[0].progresstitle4);
    $('#progressTime4').textbox('setValue', data[0].progresstime4);
    $('#progressTitle5').textbox('setValue', data[0].progresstitle5);
    $('#progressTime5').textbox('setValue', data[0].progresstime5);
    $('#progressTitle6').textbox('setValue', data[0].progresstitle6);
    $('#progressTime6').textbox('setValue', data[0].progresstime6);
    $('#progressTitle7').textbox('setValue', data[0].progresstitle7);
    $('#progressTime7').textbox('setValue', data[0].progresstime7);
    $('#progressTitle8').textbox('setValue', data[0].progresstitle8);
    $('#progressTime8').textbox('setValue', data[0].progresstime8);
    $('#progressTitle9').textbox('setValue', data[0].progresstitle9);
    $('#progressTime9').textbox('setValue', data[0].progresstime9);
    $('#progressTitle10').textbox('setValue', data[0].progresstitle10);
    $('#progressTime10').textbox('setValue', data[0].progresstime10);
    if(data[0].status1==1){
        $("#status1").attr("checked", true);
    }if(data[0].status2==1){
        $("#status2").attr("checked", true);
    }if(data[0].status3==1){
        $("#status3").attr("checked", true);
    }if(data[0].status4==1){
        $("#status4").attr("checked", true);
    }if(data[0].status5==1){
        $("#status5").attr("checked", true);
    }if(data[0].status6==1){
        $("#status6").attr("checked", true);
    }if(data[0].status7==1){
        $("#status7").attr("checked", true);
    }if(data[0].status8==1){
        $("#status8").attr("checked", true);
    }if(data[0].status9==1){
        $("#status9").attr("checked", true);
    }if(data[0].status10==1){
        $("#status10").attr("checked", true);
    }
}
