<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<link href="/Public/statics/easyui/themes/default/easyui.css" rel="stylesheet" />
	<link href="/Public/statics/easyui/themes/color.css" rel="stylesheet" />
	<link href="/Public/statics/easyui/themes/icon.css" rel="stylesheet" />
	<script src="/Public/statics/easyui/jquery.min.js"></script>
	<script src="/Public/statics/easyui/jquery.easyui.min.js"></script>
	<script src="/Public/statics/easyui/locale/easyui-lang-zh_CN.js"></script>
	<script src="/Public/statics/easyui/common.js"></script>
</head>
<body>
<div id='Loading' style="position:absolute;z-index:1000;top:0px;left:0px;width:100%;height:100%;background:#fff ;text-align:center;padding-top: 10%;">
	<h1><image src='/tpl/Public/images/loading3.gif'/></h1></div>
<table id="ruleGrid" class="easyui-treegrid" style="width:92%;height:800px" url="<?php echo U('Admin/Rule/ajaxRules');?>" pagination="true" idField="id" treeField="title" toolbar="#toolbar">
	<thead>
	<tr>
		<th field="title" width="200" >权限名称</th>
		<th field="name" width="400">URL</th>
		<th field="action" width="200" align="center" formatter="formatAction">操作</th>
	</tr>
	</thead>
</table>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add(0)">添加</a>
</div>
<!-- 添加 -->
<div id="addrule" class="easyui-dialog" title="添加权限" style="width:400px;padding:10px 20px;" closed="true" modal="true">
	<form id="rulefm" method="post">
		<table>
			<tr>
				<td>上级:</td>
				<td><lablel class="parent"></lablel><input type="hidden" name="pid" value="0"></td>
			</tr>
			<tr>
				<td>名称:</td>
				<td><input name="title" class="easyui-textbox" data-options="delay:1000,required:true,height:30" /></td>
			</tr>
			<tr>
				<td>url:</td>
				<td>
					<input name="name" class="easyui-textbox" data-options="delay:1000,required:true,height:30" />
				</td>
			</tr>
			<tr>
				<td></td>
				<td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="addRule()" style="width:90px">保存</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#addrule').dialog('close')" style="width:90px">取消</a></td>
			</tr>
		</table>

	</form>
</div>
<div id="editrule" class="easyui-dialog" title="添加权限" style="width:400px;padding:10px 20px;" closed="true" modal="true">
	<form id="ruleeditfm" method="post">
		<table>
			<tr>
				<td>上级:</td>
				<td><lablel class="parent"></lablel><input type="hidden" name="pid" value="" id="editRulePid"><input type="hidden" name="id" value="" id="editRuleId"></td>
			</tr>
			<tr>
				<td>名称:</td>
				<td><input name="title" class="easyui-textbox" data-options="delay:1000,required:true,height:30" id="editRuleTitle"/></td>
			</tr>
			<tr>
				<td>url:</td>
				<td>
					<input name="name" class="easyui-textbox" data-options="delay:1000,required:true,height:30" id="editRuleName"/>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="editRuleSubmit()" style="width:90px">保存</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#editrule').dialog('close')" style="width:90px">取消</a></td>
			</tr>
		</table>

	</form>
</div>
<script type="text/javascript">
	var url;
	//添加会员对话窗
	function add(id){
		$('#addrule').dialog('open').dialog('setTitle','添加权限');
		$('#rulefm').form('clear');
		if(id>0){
			$("input[name='pid']").val(id);
			$.getJSON("<?php echo U('Admin/Rule/get');?>/id/"+id,function(data){
				$(".parent").empty().append(data.title);
			})
		}
		url="<?php echo U('Admin/Rule/add');?>";
	}
	function addRule(){
		$('#rulefm').form('submit',{
			url: url,
			onSubmit: function(){
				return $(this).form('validate');
			},
			success:function(data){
				data=$.parseJSON(data);
				if(data.status==1){
					$.messager.alert('Info', data.message, 'info');
					$('#addrule').dialog('close');
					$('#ruleGrid').treegrid('reload');
				}else {
					$.messager.alert('Warning', data.message, 'info');
					$('#addrule').dialog('close');
					$('#ruleGrid').treegrid('reload');
				}
			}
		});
	}
	function editRuleSubmit(){
		$('#ruleeditfm').form('submit',{
			url: url,
			onSubmit: function(){
				return $(this).form('validate');
			},
			success:function(data){
				data=$.parseJSON(data);
				if(data.status==1){
					$.messager.alert('Info', data.message, 'info');
					$('#editrule').dialog('close');
					$('#ruleGrid').treegrid('reload');
				}else {
					$.messager.alert('Warning', data.message, 'info');
					$('#editrule').dialog('close');
					$('#ruleGrid').treegrid('reload');
				}
			}
		});
	}
	//编辑会员对话窗
	function editRule(id){
		if (id>0){
			$('#editrule').dialog('open').dialog('setTitle','编辑');
			$.getJSON("<?php echo U('Admin/Rule/get');?>/id/"+id,function(data){
				$("#editRulePid").val(data.pid);
				$("#editRuleId").val(data.id);
				$("#editRuleTitle").textbox('setValue',data.title);
				$("#editRuleName").textbox('setValue',data.name);
			})
			url ="<?php echo U('Admin/Rule/edit','','');?>"+'/id/'+id;
		}
	}
	function deleteRule(id){
		$.messager.confirm('删除提示','确定要删除吗?',function(r){
			if (r){
				var durl="<?php echo U('Admin/Rule/delete');?>";
				$.getJSON(durl,{id:id},function(result){
					if (result.status){
						$('#ruleGrid').treegrid('reload');    // reload the user data
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

		function formatAction(value,row,index){
			id=row.id;
			if (row.addlevel==1){
				var add = '<a href="javascript:void(0);" onclick="add('+id+')">添加子权限</a> ';
			}else{
				add="";
			}
			var del = '<a href="javascript:void(0);" onclick="deleteRule('+id+')">删除</a>';
			var edit = '<a href="javascript:void(0);" onclick="editRule('+id+')">编辑</a>';
			return add+"&nbsp;&nbsp;"+edit+"&nbsp;&nbsp;"+del;
		}

</script>
</body>
</html>