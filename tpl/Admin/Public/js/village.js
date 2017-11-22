var editor;
KindEditor.ready(function(K) {
    editor = K.create('.addbaseinfo', {
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
function addVillage(){
    $('#addVillage').dialog('open').dialog('setTitle','添加');
    $('#addVillageForm').form('clear');
    $('#town').combobox({
        url:"{:U('Admin/Town/ajaxTownAll')}",
        valueField:'id',
        textField:'name',
        multiple:false
    });
    url="{:U('Admin/Village/addVillage')}";
}

function addVillageSubmit(){
    $('.addbaseinfo').val(editor.html());
    $('#addVillageForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addVillage').dialog('close');
                $('#villageGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addVillage').dialog('close');
                $('#villageGrid').datagrid('reload');
            }
        }
    });
}
function editVillageSubmit(){
    $('.editbaseinfo').val(editor2.html());
    $('#editVillageForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editVillage').dialog('close');
                $('#villageGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editVillage').dialog('close');
                $('#villageGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editVillage(){
    var row = $('#villageGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        editor2.html(row.baseinfo);//文本编辑器显示内容
        $('#editVillage').dialog('open').dialog('setTitle','编辑');
        $('#editVillageForm').form('load',row);
        $('#townedit').combobox({
            url:"{:U('Admin/Town/ajaxTownAll')}",
            valueField:'id',
            textField:'name',
            multiple:false,
            onLoadSuccess: function(){
                console.log(row);
                $('#townedit').combobox('setValue', row.townid);
            }
        });
        url ="{:U('Admin/Village/editVillage')}"+'/id/'+row.id;
    }
}
function destroyVillage(){
    var row = $('#villageGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl="{:U('Admin/Village/deleteVillage')}";
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#villageGrid').datagrid('reload');    // reload the user data
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
    $('#openinfoDlg').dialog('open').dialog('setTitle', '业主信息');
    $('#openinfoGrid').datagrid({
        url: "{:U('Admin/Village/ajaxOpeninfos')}/village_id/" + row.id,
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
    url="{:U('Admin/Village/addopeninfo')}";
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
        url ="{:U('Admin/Village/editopeninfo')}"+'/id/'+row.id;
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
                var durl="{:U('Admin/Village/deleteopeninfo')}";
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