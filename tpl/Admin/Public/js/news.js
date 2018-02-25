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
function addNews(){
    $('#addNews').dialog('open').dialog('setTitle','添加');
    $('#addNewsForm').form('clear');
    url=addNewsUrl;
}

function addNewsSubmit(){
    $('.addintro').val(editor.html());
    $('#addNewsForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#addNews').dialog('close');
                $('#villageGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#addNews').dialog('close');
                $('#villageGrid').datagrid('reload');
            }
        }
    });
}
function editNewsSubmit(){
    $('.editintro').val(editor2.html());
    $('#editNewsForm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success:function(data){
            data=$.parseJSON(data);
            if(data.status==1){
                $.messager.alert('Info', data.message, 'info');
                $('#editNews').dialog('close');
                $('#NewsGrid').datagrid('reload');
            }else {
                $.messager.alert('Warning', data.message, 'info');
                $('#editNews').dialog('close');
                $('#NewsGrid').datagrid('reload');
            }
        }
    });
}
//编辑会员对话窗
function editNews(){
    var row = $('#NewsGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要编辑的行", 'info');return false;
    }
    if (row){
        editor2.html(row.intro);
        $('#editNews').dialog('open').dialog('setTitle','编辑');
        $('#editNewsForm').form('load',row);
        url = editNewsUrl +'/id/'+row.id;
    }
}
function destroyNews(){
    var row = $('#NewsGrid').datagrid('getSelected');
    if(row==null){
        $.messager.alert('Warning',"请选择要删除的行", 'info');return false;
    }
    if (row){
        $.messager.confirm('删除提示','真的要删除?',function(r){
            if (r){
                var durl= deleteNewsUrl;
                $.getJSON(durl,{id:row.id},function(result){
                    if (result.status){
                        $('#NewsGrid').datagrid('reload');    // reload the user data
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
