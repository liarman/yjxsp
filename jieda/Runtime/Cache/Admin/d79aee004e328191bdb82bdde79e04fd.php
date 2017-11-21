<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <link href="/Public/statics/easyui/themes/default/easyui.css" rel="stylesheet" />
    <link href="/Public/statics/easyui/themes/color.css" rel="stylesheet" />
    <link href="/Public/statics/easyui/themes/icon.css" rel="stylesheet" />
    <link href="/Public/statics/kindeditor/themes/default/default.css" rel="stylesheet" />
    <script src="/Public/statics/easyui/jquery.min.js"></script>
    <script src="/Public/statics/easyui/jquery.easyui.min.js"></script>
    <script src="/Public/statics/easyui/locale/easyui-lang-zh_CN.js"></script>
    <script src="/Public/statics/easyui/common.js"></script>
    <script src="/Public/statics/kindeditor/kindeditor-all-min.js"></script>

</head>
<body>
<div id='Loading' style="position:absolute;z-index:1000;top:0px;left:0px;width:100%;height:100%;background:#fff ;text-align:center;padding-top: 10%;">
    <h1><image src='/tpl/Public/images/loading3.gif'/></h1></div>
<table id="EquipmentGrid" class="easyui-datagrid" style="width:92%;height:670px" url="<?php echo U('Admin/Equipment/ajaxEquipmentList');?>" pagination="true" toolbar="#toolbar" singleSelect="true" rownumbers="true" pageSize="20">
    <thead>
    <tr>
        <th field="name" width="200" >设备名称</th>
        <th field="deviceid" width="150" >设备id</th>
        <th field="shareid" width="300" >分享id</th>
        <th field="uk" width="100" >分享uk</th>
        <th field="areaid" width="100" formatter="formatArea" >所属区域</th>
        <th field="openInfoUrl" width="545" >分享链接</th>
    </tr>
    </thead>
</table>

<div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addEquipment()">添加</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editEquipment()">编辑</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyEquipment()">删除</a>


</div>
<!-- 添加 -->
<div id="addEquipment" class="easyui-dialog" title="添加" style="width:450px;height:500px;padding:10px 20px;" closed="true" modal="true">
    <form id="addEquipmentForm" method="post">
        <table>
            <tr>
                <td>设备名称:</td>
                <td><input name="name" class="easyui-textbox" data-options="delay:1000,required:true,height:30" /></td>
            </tr>
            <tr>
                <td>设备id:</td>
                <td><input name="deviceid" class="easyui-textbox" data-options="delay:1000,required:true,height:30" /></td>
            </tr>
            <tr>
                <td>分享id:</td>
                <td><input name="shareid" class="easyui-textbox" data-options="delay:1000,required:true,validType:'checkFloat',height:30" />
                </td>
            </tr>
            <tr>
                <td>所属区域:</td>
                <td>
                    <select  panelMaxHeight="100px" name="areaid" class="easyui-combobox" style="width:174px" data-options="delay:1000,required:true,multiple:false">
                        <option value="1">谯城区</option>
                        <option value="2">涡阳县</option>
                        <option value="3">蒙城县</option>
                        <option value="4">利辛县</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>分享uk:</td>
                <td><input name="uk" class="easyui-textbox" data-options="delay:1000,required:true,validType:'checkFloat',height:30" />
                </td>
            </tr>


            <tr>
                <td></td>
                <td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="addEquipmentSubmit()" style="width:90px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#addEquipment').dialog('close')" style="width:90px">取消</a></td>
            </tr>
        </table>

    </form>
</div>
<div id="editEquipment" class="easyui-dialog" title="编辑" style="width:450px;height:500px;padding:10px 20px;" closed="true" modal="true">
    <form id="editEquipmentForm" method="post">
        <input type="hidden" name="id" value="">
        <table>
            <tr>
                <td>设备名称:</td>
                <td><input name="name" class="easyui-textbox" data-options="delay:1000,required:true,height:30" /></td>
            </tr>
            <tr>
                <td>设备id:</td>
                <td><input name="deviceid" class="easyui-textbox" data-options="delay:1000,required:true,height:30" /></td>
            </tr>
            <tr>
                <td>分享id:</td>
                <td><input name="shareid" class="easyui-textbox" data-options="delay:1000,required:true,validType:'checkFloat',height:30" />
                </td>
            </tr>
            <tr>
                <td>所属区域:</td>
                <td>
                    <select  panelMaxHeight="100px" name="areaid" class="easyui-combobox" style="width:174px" data-options="delay:1000,required:true,multiple:false">
                        <option value="1">谯城区</option>
                        <option value="2">涡阳县</option>
                        <option value="3">蒙城县</option>
                        <option value="4">利辛县</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>分享uk:</td>
                <td><input name="uk" class="easyui-textbox" data-options="delay:1000,required:true,validType:'checkFloat',height:30" />
                </td>
            </tr>

            <tr>
                <td></td>
                <td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="editEquipmentSubmit()" style="width:90px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#editEquipment').dialog('close')" style="width:90px">取消</a></td>
            </tr>
        </table>

    </form>
</div>
<script type="text/javascript">
    var url;
    function addEquipment(){
        $('#addEquipment').dialog('open').dialog('setTitle','添加');
        $('#addEquipmentForm').form('clear');
        url="<?php echo U('Admin/Equipment/addEquipment');?>";
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
            url ="<?php echo U('Admin/Equipment/editEquipment');?>"+'/id/'+row.id;
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
                    var durl="<?php echo U('Admin/Equipment/deleteEquipment');?>";
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
</script>
</body>
</html>