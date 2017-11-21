<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="/Public/statics/easyui/themes/default/easyui.css" rel="stylesheet" />
    <link href="/Public/statics/easyui/themes/color.css" rel="stylesheet" />
    <link href="/Public/statics/easyui/themes/icon.css" rel="stylesheet" />
    <script src="/Public/statics/easyui/jquery.min.js"></script>
    <script src="/Public/statics/easyui/jquery.easyui.min.js"></script>
    <script src="/Public/statics/easyui/formatter.js"></script>
    <script src="/Public/statics/easyui/extend/validate.js"></script>
    <script src="/Public/statics/easyui/locale/easyui-lang-zh_CN.js"></script>
    <script src="/Public/statics/easyui/common.js"></script>
</head>
<body>
<div id='Loading' style="position:absolute;z-index:1000;top:0px;left:0px;width:100%;height:100%;background:#fff ;text-align:center;padding-top: 10%;">
    <h1><image src='/tpl/Public/images/loading3.gif'/></h1></div>
<table id="adminUserGrid" class="easyui-datagrid" style="width:92%;height:800px" url="<?php echo U('Admin/Rule/ajaxUserList');?>" pagination="true"  toolbar="#toolbar" singleSelect="true" rownumbers="true">
    <thead>
    <tr>
        <th field="username" width="200" >账号</th>
        <th field="groupname" width="200" >用户组</th>
        <th field="email" width="200" >邮箱</th>
        <th field="phone" width="200" >电话</th>
        <th field="status" width="200" formatter="formatStatus">状态</th>
        <th field="last_login_time" width="200"  formatter="Common.TimeFormatter">登录时间</th>
        <th field="last_login_ip" width="200" >登录ip</th>
    </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addAdminUser()">添加</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editAdminUser()">编辑</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyAdminUser()">删除</a>
</div>
<!-- 添加 -->
<div id="addAdminUser" class="easyui-dialog" title="添加" style="width:400px;padding:10px 20px;" closed="true" modal="true">
    <form id="addAdminUserForm" method="post">
        <table>
            <tr>
                <td>账号:</td>
                <td><input name="username" class="easyui-textbox" data-options="delay:1000,required:true,validType:'account[6,20]' " /></td>
            </tr>
            <tr>
                <td>密码:</td>
                <td><input name="password"  class="easyui-passwordbox" data-options="delay:1000,required:true,validType:'password[6,20]' " /></td>
            </tr>
            <tr>
                <td>用户组:</td>
                <td><input id="cc" name="groups" value=""  data-options="delay:1000,required:true,multiple:true">
                    <input id="group_ids" name="group_ids" value="" type="hidden">
                </td>
            </tr>
            <tr>
                <td>状态:</td>
                <td>
                    <input type="radio" name="status" checked="checked" value="1" class="easyui-validatebox addstatus" /><label>可用</label>
                    <input type="radio" name="status" value="0" class="easyui-validatebox addstatus" /><label>不可用</label>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="addAdminUserSubmit()" style="width:90px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#addAdminUser').dialog('close')" style="width:90px">取消</a></td>
            </tr>
        </table>

    </form>
</div>
<div id="editAdminUser" class="easyui-dialog" title="编辑" style="width:400px;padding:10px 20px;" closed="true" modal="true">
    <form id="editAdminUserForm" method="post">
        <input type="hidden" name="id" value="">
        <table>
            <tr>
                <td>账号:</td>
                <td><input name="username" class="easyui-textbox" data-options="delay:1000,required:true,validType:'account[6,20]' " /></td>
            </tr>
            <tr>
                <td>密码:</td>
                <td><input name="password" prompt="不填写则为原密码"  class="easyui-passwordbox" /></td>
            </tr>
            <tr>
                <td>用户组:</td>
                <td><input id="ccd" name="groups" value=""  data-options="delay:1000,required:true,multiple:true">
                    <input id="group_idsd" name="group_ids" value="" type="hidden">
                </td>
            </tr>
            <tr>
                <td>状态:</td>
                <td>
                    <input type="radio" name="status"
                           class="easyui-validatebox" checked="checked" value="1"><label>可用</label></input>
                    <input type="radio" name="status"
                           class="easyui-validatebox" value="0"><label>不可用</label></input>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="editAdminUserSubmit()" style="width:90px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#editAdminUser').dialog('close')" style="width:90px">取消</a></td>
            </tr>
        </table>

    </form>
</div>
<script type="text/javascript">
    var url;
    var loading = false; //为防止onCheck冒泡事件设置的全局变量

    function addAdminUser(){
        $('#addAdminUser').dialog('open').dialog('setTitle','添加账号');
        $('#addAdminUserForm').form('clear');
        $('#cc').combobox({
            url:"<?php echo U('Admin/Rule/ajaxGroupAll');?>",
            valueField:'id',
            textField:'title',
            onChange:function(){
                $("#group_ids").val($('#cc').combobox('getValues').join(','));
             }
        });
        var $radios = $('.addstatus');
        $radios.filter('[value=1]').prop('checked', true);
        url="<?php echo U('Admin/Rule/add_admin');?>";
    }
    function addAdminUserSubmit(){
        $('#addAdminUserForm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success:function(data){
                data=$.parseJSON(data);
                if(data.status==1){
                    $.messager.alert('Info', data.message, 'info');
                    $('#addAdminUser').dialog('close');
                    $('#adminUserGrid').datagrid('reload');
                }else {
                    $.messager.alert('Warning', data.message, 'info');
                    $('#addAdminUser').dialog('close');
                    $('#adminUserGrid').datagrid('reload');
                }
            }
        });
    }
    function editAdminUserSubmit(){
        $('#editAdminUserForm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success:function(data){
                data=$.parseJSON(data);
                if(data.status==1){
                    $.messager.alert('Info', data.message, 'info');
                    $('#editAdminUser').dialog('close');
                    $('#adminUserGrid').datagrid('reload');
                }else {
                    $.messager.alert('Warning', data.message, 'info');
                    $('#editAdminUser').dialog('close');
                    $('#adminUserGrid').datagrid('reload');
                }
            }
        });
    }
    //编辑会员对话窗
    function editAdminUser(){
        var row = $('#adminUserGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#editAdminUser').dialog('open').dialog('setTitle','编辑');
            $('#editAdminUserForm').form('load',{
                username:row.username,
                id:row.id,
                status:row.status
            });
            $('#ccd').combobox({
                url:"<?php echo U('Admin/Rule/ajaxGroupAll');?>/id/"+row.id,
                valueField:'id',
                textField:'title',
                onLoadSuccess:function(){
                   // $('#ccd').combobox('setValues','[]');
                },
                onChange:function(){
                    $("#group_idsd").val($('#ccd').combobox('getValues').join(','));
                }
            });
            url ="<?php echo U('Admin/Rule/edit_admin');?>"+'/id/'+row.id;
        }
    }
    function destroyAdminUser(){
        var row = $('#adminUserGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
        }
        if (row){
            $.messager.confirm('删除提示','真的要删除?',function(r){
                if (r){
                    var durl="<?php echo U('Admin/Rule/delete_users');?>";
                    $.getJSON(durl,{id:row.id},function(result){
                        if (result.status){
                            $('#adminUserGrid').datagrid('reload');    // reload the user data
                        } else {
                            $.messager.alert('错误提示',result.message,'error');
                        }
                    },'json').error(function(data){
                        var info=eval('('+data.responseText+')');
                        $.messager.confirm('错误提示',info.message,function(r){

                        });
                    });
                }
            });
        }
    }
    	function formatStatus(val,rowData,row){
            if(val==1){
                val="<span style='color: green'>可用</span>";
            }else {
                val="<span style='color: red'>不可用</span>";
            }
            return val;
    	}
</script>
</body>
</html>