<?php
    # 载入配置文件
    include './config.php';
?>
<!--
作者: 路人庚
QQ: 309581329
BLOG: gwalker.cn
-->
<!DOCTYPE html>
<html>
<head>
    <title>客户端请求模拟器</title>
	<meta charset="UTF-8">
    <style type="text/css">
        body{margin:0; padding:0;}
        input{padding:0 3px 0 3px;}
        textarea{padding:5px;};
    </style>
    <script type="text/javascript" src="./jquery.js"></script>
</head>
<body>
    <div style="height:30px;line-height:30px;background:#ccc;width:100%;text-align:right;font-size:14px;">
        <a style="text-decoration: none" href="javascript:void(0)">社区项目</a>&nbsp;&nbsp;&nbsp;&nbsp;
    </div>
        <div style="margin:40px 30px 20px 20px;float:left">
            <div style="font-size:20px;height:30px;color:#2481f8">客户端请求模拟器</div>

            <div style="height:30px;">
                请求地址:
                <input id="url" type="text" style="width:420px;" placeholder="请求地址" value="<?php echo $request['url'];?>">
            </div>
            <br>
            <div style="height:30px;">
                请求方式:
                <select name="request" id="request">
                    <option value='POST'<?php if($request['type']=='POST'){echo 'selected';}?> >POST</option>
                    <option value='GET' <?php if($request['type']=='GET'){echo 'selected';}?> >GET</option>
                </select>
            </div>
            <br>
            <div style="height:30px;">
                Referer&nbsp;:
                 <input id="referer" type="text" style="width:420px;" placeholder="referer" value="<?php echo $request['referer']?>">
            </div>
            <br>
            <div style="height:30px;">
                cookie&nbsp;&nbsp;:
                <input id="cookie" type="text" style="width:420px;" placeholder="格式: k0=v0;k1=v1">
            </div>
            <br>
            <div style="height:30px;">
                header&nbsp;:
                <input id="header" type="text" style="width:420px;" placeholder="格式: k0=v0;k1=v1">
            </div>
            <br>
            <textarea id="yuan" style="width:488px;height:200px;border:solid 1px #ccc;" placeholder="输入提交的数据"></textarea>

            <div style="margin-top:20px;">
                <input id="submit" type="button" value="提交" style="width:120px;float:right;cursor:pointer">
            </div>
        </div>

        <div style="margin:40px 10px 10px 10px;float:left;width:calc(100% - 600px);">
            <div style="font-size:20px;height:30px;color:#2481f8;">返回结果</div>
            <div id="redata" style="color:white;width:100%;min-height:500px;background:#272822;padding:10px;"></div>
        </div>
   
</body>
</html>
<script type="text/javascript">
    $('#submit').click(function(){
        var yuan=$('#yuan').val();
        var request=$('#request option:selected').val();
        var url = $('#url').val();
        var referer = $('#referer').val();
        var cookie = $('#cookie').val();
        var header = $('#header').val();
        jsondata={yuan:yuan,request:request,url:url,referer:referer,cookie:cookie,header:header};

        $('#redata').html('');
        url="ajax_server.php";
        $.post(url,jsondata,function(data){
            $('#redata').html(data)
        });
    });
</script>