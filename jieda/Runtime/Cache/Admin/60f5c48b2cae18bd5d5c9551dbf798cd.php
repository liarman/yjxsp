<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<link href="/Public/statics/easyui/themes/default/easyui.css" rel="stylesheet" />
	<link href="/Public/statics/easyui/themes/color.css" rel="stylesheet" />
	<link href="/Public/statics/easyui/themes/icon.css" rel="stylesheet" />
	<script src="/Public/statics/easyui/jquery.min.js"></script>
	<script src="/Public/statics/easyui/jquery.easyui.min.js"></script>
	<script src="/Public/statics/easyui/common.js"></script>
	<script src="/Public/statics/easyui/locale/easyui-lang-zh_CN.js"></script>
</head>
<body>
<div id='Loading' style="position:absolute;z-index:1000;top:0px;left:0px;width:100%;height:100%;background:#fff ;text-align:center;padding-top: 10%;">
	<h1><image src='/tpl/Public/images/loading3.gif'/></h1></div>
<table id="groupGrid" class="easyui-datagrid" style="width:92%;height:800px" url="<?php echo U('Admin/Rule/ajaxGroup');?>" pagination="true"  toolbar="#toolbar" singleSelect="true">
	<thead>
	<tr>
		<th field="title" width="200" >用户组名称</th>
	</tr>
	</thead>
</table>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addGroup()">添加</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editGroup()">编辑</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyGroup()">删除</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-lock" plain="true" onclick="giveAuth()">分配权限</a>
</div>
<!-- 添加 -->
<div id="addGroup" class="easyui-dialog" title="添加" style="width:400px;padding:10px 20px;" closed="true" modal="true">
	<form id="addGroupForm" method="post">
		<table>
			<tr>
				<td>用户组名称:</td>
				<td><input name="title" class="easyui-textbox" data-options="delay:1000,required:true,height:30" /></td>
			</tr>
			<tr>
				<td></td>
				<td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="addGroupSubmit()" style="width:90px">保存</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#addGroup').dialog('close')" style="width:90px">取消</a></td>
			</tr>
		</table>

	</form>
</div>
<div id="editGroup" class="easyui-dialog" title="编辑" style="width:400px;padding:10px 20px;" closed="true" modal="true">
	<form id="editGroupForm" method="post">
		<input type="hidden" name="id" value="">
		<table>
			<tr>
				<td>名称:</td>
				<td><input name="title" class="easyui-textbox" data-options="delay:1000,required:true,height:30" id="editRuleTitle"/></td>
			</tr>
			<tr>
				<td></td>
				<td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="editGroupSubmit()" style="width:90px">保存</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#editGroup').dialog('close')" style="width:90px">取消</a></td>
			</tr>
		</table>

	</form>
</div>
<div id="giveAuthGroup" class="easyui-dialog" title="分配权限" style="width:400px;padding:10px 20px;height: 600px;" closed="true" modal="true">
	<form id="giveAuthForm" method="post">
		<ul id="authtree" class="easyui-tree" ></ul>

	</form>
</div>
<script type="text/javascript">
	var url;
	var loading = false; //为防止onCheck冒泡事件设置的全局变量
	//添加会员对话窗
	function giveAuth(){
		var row = $('#groupGrid').datagrid('getSelected');
		if(row==null){
			$.messager.alert('Warning',"请选择一个用户组", 'info');return false;
		}
		$('#giveAuthGroup').dialog('open').dialog('setTitle','分配权限');
		$('#giveAuthForm').form('clear');
		$('#authtree').tree({
			url: "<?php echo U('Admin/Rule/ajaxAuthTree');?>/id/"+row.id,
			checkbox:true,
			onBeforeLoad: function (node, param) {
				loading = true;
			},
			onLoadSuccess: function (node, data) {
				loading = false;
			},
			onCheck: function (node, checked) {
				if (loading) {
					return;
				} else {
					var rules=getChecked($('#authtree').tree('getChecked'));
					$.post("<?php echo U('Admin/Rule/rule_group');?>", {
						rules: rules,
						id: row.id
					}, function (result) {
						if (result == "fail")
							alert("操作失败");
					});
				}
			}
		});
	}
	function addGroup(){
		$('#addGroup').dialog('open').dialog('setTitle','添加用户组');
		$('#addGroupForm').form('clear');
		url="<?php echo U('Admin/Rule/add_group');?>";
	}
	function addGroupSubmit(){
		$('#addGroupForm').form('submit',{
			url: url,
			onSubmit: function(){
				return $(this).form('validate');
			},
			success:function(data){
				data=$.parseJSON(data);
				if(data.status==1){
					$.messager.alert('Info', data.message, 'info');
					$('#addGroup').dialog('close');
					$('#groupGrid').datagrid('reload');
				}else {
					$.messager.alert('Warning', data.message, 'info');
					$('#addrule').dialog('close');
					$('#groupGrid').datagrid('reload');
				}
			}
		});
	}
	function editGroupSubmit(){
		$('#editGroupForm').form('submit',{
			url: url,
			onSubmit: function(){
				return $(this).form('validate');
			},
			success:function(data){
				data=$.parseJSON(data);
				if(data.status==1){
					$.messager.alert('Info', data.message, 'info');
					$('#editGroup').dialog('close');
					$('#groupGrid').datagrid('reload');
				}else {
					$.messager.alert('Warning', data.message, 'info');
					$('#editGroup').dialog('close');
					$('#groupGrid').datagrid('reload');
				}
			}
		});
	}
	//编辑会员对话窗
	function editGroup(){
		var row = $('#groupGrid').datagrid('getSelected');
		if(row==null){
			$.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
		}
		if (row){
			$('#editGroup').dialog('open').dialog('setTitle','编辑');
			$('#editGroupForm').form('load',row);
			url ="<?php echo U('Admin/Nav/edit_group');?>"+'/id/'+row.id;
		}
	}
	function destroyGroup(){
		var row = $('#groupGrid').datagrid('getSelected');
		if(row==null){
			$.messager.alert('Warning',"请选择要删除的行", 'info');return false;
		}
		if (row){
			$.messager.confirm('删除提示','真的要删除?',function(r){
				if (r){
					var durl="<?php echo U('Admin/Rule/delete_group');?>";
					$.getJSON(durl,{id:row.id},function(result){
						if (result.status){
							$('#groupGrid').datagrid('reload');    // reload the user data
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
	function getChecked(nodes){
		var s = '';
		for (var i = 0; i < nodes.length; i++) {
			if (s != '')
				s += ',';
			s += nodes[i].id;
		}
		return s;
	}
</script>
</body>
</html>