<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="/Public/statics/easyui/themes/default/easyui.css" rel="stylesheet" />
    <link href="/Public/statics/easyui/themes/color.css" rel="stylesheet" />
    <link href="/Public/statics/easyui/themes/icon.css" rel="stylesheet" />
    <link href="/Public/statics/kindeditor/themes/default/default.css" rel="stylesheet" />
    <script src="/Public/statics/easyui/jquery.min.js"></script>
    <script src="/Public/statics/easyui/jquery.easyui.min.js"></script>
    <script src="/Public/statics/easyui/common.js"></script>
    <script src="/Public/statics/easyui/locale/easyui-lang-zh_CN.js"></script>
    <script src="/Public/statics/kindeditor/kindeditor-all-min.js"></script>
</head>
<body>
<div id='Loading' style="position:absolute;z-index:1000;top:0px;left:0px;width:100%;height:100%;background:#fff ;text-align:center;padding-top: 10%;">
    <h1><image src='/tpl/Public/images/loading3.gif'/></h1></div>
<table id="appGrid" class="easyui-datagrid" style="width:92%;height:660px" url="<?php echo U('Admin/AppWeixin/ajaxAppList');?>" pagination="true"  toolbar="#toolbar" singleSelect="true" rownumbers="true" idField="id" treeField="name" pageSize="20">
    <thead>
    <tr>
        <th field="appid" width="200" >appid</th>
        <th field="appsecert" width="200" >appsecert</th>
        <th field="accesstoken" width="200" >access token</th>
    </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addAppNav()">添加</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editAppNav()">编辑</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyAppNav()">删除</a>
</div>
<!-- 添加 -->
<div id="addApp" class="easyui-dialog" title="添加" style="width:400px;height:250px;padding:10px 20px;" closed="true" modal="true">
    <form id="addAppForm" method="post">
        <table>
            <tr>
                <td>appid:</td>
                <td><input name="appid" class="easyui-textbox" data-options="delay:1000,required:true,height:30" /></td>
            </tr>
            <tr>
                <td>appsecert:</td>
                <td>
                    <input name="appsecert" class="easyui-textbox" data-options="delay:1000,required:true,validType:'checkFloat',height:30" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="addAppNavSubmit()" style="width:90px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#addApp').dialog('close')" style="width:90px">取消</a></td>
            </tr>
        </table>

    </form>
</div>
<div id="editApp" class="easyui-dialog" title="编辑" style="width:400px;height:250px;padding:10px 20px;" closed="true" modal="true">
    <form id="editAppForm" method="post">
        <input type="hidden" name="id" value="">
        <table>
            <tr>
                <td>appid:</td>
                <td><input name="appid" class="easyui-textbox" data-options="delay:1000,required:true,height:30" /></td>
            </tr>
            <tr>
                <td>appsecert:</td>
                <td>
                    <input name="appsecert" class="easyui-textbox" data-options="delay:1000,required:true,validType:'checkFloat',height:30" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="editAppNavSubmit()" style="width:90px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#editApp').dialog('close')" style="width:90px">取消</a></td>
            </tr>
        </table>

    </form>
</div>
</body>
<script type="text/javascript">

    var url;
    function addAppNav(){
        $('#addApp').dialog('open').dialog('setTitle','添加');
        $('#addAppForm').form('clear');
        url="<?php echo U('Admin/AppWeixin/addAppNav');?>";
    }
    function addAppNavSubmit(){
        $('#addAppForm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success:function(data){
                data=$.parseJSON(data);
                if(data.status==1){
                    $.messager.alert('Info', data.message, 'info');
                    $('#addApp').dialog('close');
                    $('#appGrid').datagrid('reload');
                }else {
                    $.messager.alert('Warning', data.message, 'info');
                    $('#addApp').dialog('close');
                    $('#appGrid').datagrid('reload');
                }
            }
        });
    }
    function editAppNavSubmit(){
        $('#editAppForm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success:function(data){
                data=$.parseJSON(data);
                if(data.status==1){
                    $.messager.alert('Info', data.message, 'info');
                    $('#editApp').dialog('close');
                    $('#appGrid').datagrid('reload');
                }else {
                    $.messager.alert('Warning', data.message, 'info');
                    $('#editApp').dialog('close');
                    $('#appGrid').datagrid('reload');
                }
            }
        });
    }
    //编辑会员对话窗
    function editAppNav(){
        var row = $('#appGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            $('#editApp').dialog('open').dialog('setTitle','编辑');
            $('#editAppForm').form('load',row);
            url ="<?php echo U('Admin/AppWeixin/editAppNav');?>"+'/id/'+row.id;
        }
    }
    function destroyAppNav(){
        var row = $('#appGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
        }
        if (row){
            $.messager.confirm('删除提示','真的要删除?',function(r){
                if (r){
                    var durl="<?php echo U('Admin/AppWeixin/deleteAppNav');?>";
                    $.getJSON(durl,{id:row.id},function(result){
                        if (result.status){
                            $('#appGrid').datagrid('reload');    // reload the user data
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
</script>
</html>