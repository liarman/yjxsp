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
<table id="townGrid" class="easyui-datagrid" style="width:92%;height:800px" url="<?php echo U('Admin/Town/ajaxTownList');?>" pagination="true"  toolbar="#toolbar" singleSelect="true" rownumbers="true" idField="id" treeField="name" pageSize="20">
    <thead>
    <tr>
        <th field="name" width="200" >名称</th>
        <th field="longitude" width="200" >经度</th>
        <th field="latitude" width="200" >纬度</th>
    </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addTown()">添加</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editTown()">编辑</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyTown()">删除</a>
</div>
<!-- 添加 -->
<div id="addTown" class="easyui-dialog" title="添加" style="width:800px;height:650px;padding:10px 20px;" closed="true" modal="true">
    <form id="addTownForm" method="post">
        <table>
            <tr>
                <td>标题:</td>
                <td><input name="name" class="easyui-textbox" data-options="delay:1000,required:true,height:30" /></td>
            </tr>
            <tr>
                <td>经度:</td>
                <td>
                    <input name="longitude" class="easyui-textbox" data-options="delay:1000,required:true,validType:'checkFloat',height:30" />
                </td>
            </tr>
            <tr>
                <td>纬度:</td>
                <td><input name="latitude" class="easyui-textbox" data-options="delay:1000,required:true,validType:'checkFloat',height:30" /></td>
            </tr>
            <tr>
                <td>基本信息:</td>
                <td><textarea name="baseinfo" class="baseinfo" style="width:600px;height:400px;"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="addTownSubmit()" style="width:90px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#addTown').dialog('close')" style="width:90px">取消</a></td>
            </tr>
        </table>

    </form>
</div>
<div id="editTown" class="easyui-dialog" title="编辑" style="width:800px;height:650px;padding:10px 20px;" closed="true" modal="true">
    <form id="editTownForm" method="post">
        <input type="hidden" name="id" value="">
        <table>
            <tr>
                <td>标题:</td>
                <td><input name="name" class="easyui-textbox" data-options="delay:1000,required:true,height:30" /></td>
            </tr>
            <tr>
                <td>经度:</td>
                <td>
                    <input name="longitude" class="easyui-textbox" data-options="delay:1000,required:true,validType:'checkFloat',height:30" />
                </td>
            </tr>
            <tr>
                <td>纬度:</td>
                <td><input name="latitude" class="easyui-textbox" data-options="delay:1000,required:true,validType:'checkFloat',height:30" /></td>
            </tr>
            <tr>
                <td>基本信息:</td>
                <td><textarea name="baseinfo" class="editbaseinfo" style="width:600px;height:400px;"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="editTownSubmit()" style="width:90px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#editTown').dialog('close')" style="width:90px">取消</a></td>
            </tr>
        </table>

    </form>
</div>
</body>
<script type="text/javascript">
    var editor;
    KindEditor.ready(function(K) {
        editor = K.create('.baseinfo', {
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
    function addTown(){
        $('#addTown').dialog('open').dialog('setTitle','添加');
        $('#addTownForm').form('clear');
        url="<?php echo U('Admin/Town/addTown');?>";
    }
    function addTownSubmit(){
        $('.baseinfo').val(editor.html());
        $('#addTownForm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success:function(data){
                data=$.parseJSON(data);
                if(data.status==1){
                    $.messager.alert('Info', data.message, 'info');
                    $('#addTown').dialog('close');
                    $('#townGrid').datagrid('reload');
                }else {
                    $.messager.alert('Warning', data.message, 'info');
                    $('#addTown').dialog('close');
                    $('#townGrid').datagrid('reload');
                }
            }
        });
    }
    function editTownSubmit(){
        $('.editbaseinfo').val(editor2.html());
        $('#editTownForm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success:function(data){
                data=$.parseJSON(data);
                if(data.status==1){
                    $.messager.alert('Info', data.message, 'info');
                    $('#editTown').dialog('close');
                    $('#townGrid').datagrid('reload');
                }else {
                    $.messager.alert('Warning', data.message, 'info');
                    $('#editTown').dialog('close');
                    $('#townGrid').datagrid('reload');
                }
            }
        });
    }
    //编辑会员对话窗
    function editTown(){
        var row = $('#townGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
        }
        if (row){
            editor2.html(row.baseinfo);
            $('#editTown').dialog('open').dialog('setTitle','编辑');
            $('#editTownForm').form('load',row);
            url ="<?php echo U('Admin/Town/editTown');?>"+'/id/'+row.id;
        }
    }
    function destroyTown(){
        var row = $('#townGrid').datagrid('getSelected');
        if(row==null){
            $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
        }
        if (row){
            $.messager.confirm('删除提示','真的要删除?',function(r){
                if (r){
                    var durl="<?php echo U('Admin/Town/deleteTown');?>";
                    $.getJSON(durl,{id:row.id},function(result){
                        if (result.status){
                            $('#townGrid').datagrid('reload');    // reload the user data
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