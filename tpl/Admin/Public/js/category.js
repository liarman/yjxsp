$(document).ready(function(){
    KindEditor.ready(function(K){
        var editor = K.editor({
            allowFileManager:false
        });
        K('#addcategoryimg').click(function() {
            editor.loadPlugin('image', function() {
                editor.plugin.imageDialog({
                    fileUrl : K('#thumb').val(),
                    clickFn : function(url, title) {
                        $('.addimg').textbox("setValue", GLOBALUrl +url);
                        editor.hideDialog();
                    }
                });
            });
        });
        K('#editcategoryimg').click(function() {
            editor.loadPlugin('image', function() {
                editor.plugin.imageDialog({
                    fileUrl : K('#thumb').val(),
                    clickFn : function(url, title) {
                        $('.editimg').textbox("setValue", GLOBALUrl +url);
                        editor.hideDialog();
                    }
                });
            });
        });
    });
});
var url;
function addCategory(){
    $('#addCategory').dialog('open').dialog('setTitle','添加');
    $('#addCategoryForm').form('clear');
    $('#pid').combobox({});
    url= addUrl ;
}
function addCategorySubmit(){
    $('#addCategoryForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addCategory').dialog('close');
                $('#categoryGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addCategory').dialog('close');
                $('#categoryGrid').datagrid('reload');
            }
        }
    });
}
function editCategorySubmit(){
    $('#editCategoryForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editCategory').dialog('close');
                $('#categoryGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editCategory').dialog('close');
                $('#categoryGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editCategory(){
    var row = $('#categoryGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        $('#editCategory').dialog('open').dialog('setTitle','编辑');
        $('#editCategoryForm').form('load',row);
        if(row.recommend==1){ $(".editcategoryCheckbox").attr("checked", true);}
        if(row.pid==0){
            $('.pidedit').combobox('loadData',[{'id':0,'name':'无'}]);
        }else {
            // $('.pidedit').combobox('loadData',{});
            $('.pidedit').combobox({url: categoryLevelUrl });
            $('.pidedit').combobox('setValue', row.pid);
        }
        url = editUrl +'/id/'+row.id;
    }
}
function destroyCategory(){
    var row = $('#categoryGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl= deleteUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#categoryGrid').datagrid('reload');    // reload the user data
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
$('#pid').combobox({});
function formatRecommend(val,rowData,row){
    if(val==1){
        val="<span style='color: green'>是</span>";
    }else {
        val="<span style='color: red'>否</span>";
    }
    return val;
}

function listChild(){
    var row = $('#categoryGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择查看子类的行", 'info');return false;
    }
    $('#childDlg').dialog('open').dialog('setTitle','子类列表');
    $('#CaChildGrid').datagrid({
        url:ajaxChildUrl+'/id/'+row.id,
        columns:[[
            {field:'name',title:'名称',width:100},
            {field:'imgurl',title:'图片',width:100,formatter:imgFormatter},
            {field:'sort',title:'排序',width:100,align:'right'}
        ]]
    });
}