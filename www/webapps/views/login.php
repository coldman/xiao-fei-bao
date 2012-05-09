<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="Keywords" content="" />
    <meta name="Descprition" content="" />
    <title>91消费宝业务员系统 - 登录</title>
    <script src="<?php echo $media_root.'js/jquery-1.7.2.min.js'; ?>" type="text/javascript"></script>
    <link href="<?php echo $media_root.'css/ligerui-dialog.css'; ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo $media_root.'css/dialog.css'; ?>" rel="stylesheet" type="text/css" />
    <script src="<?php echo $media_root.'js/base.js'; ?>" type="text/javascript"></script>
    <script src="<?php echo $media_root.'ligerDialog.js'; ?>" type="text/javascript"></script>
    <script src="<?php echo $media_root.'js/common.js'; ?>" type="text/javascript"></script>
    <script src="<?php echo $media_root.'js/LG.js'; ?>" type="text/javascript"></script>
    <link href="<?php echo $media_root.'css/login.css'; ?>" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        /* var FromUrl = getQueryStringByName("FromUrl");
        if (!FromUrl)
        {
            FromUrl = encodeURIComponent("index.aspx");
        } */
        $(function ()
        {
            $(".login-text").focus(function ()
            {
                $(this).addClass("login-text-focus");
            }).blur(function ()
            {
                $(this).removeClass("login-text-focus");
            });

            $(document).keydown(function (e)
            {
                if (e.keyCode == 13)
                {
                    dologin();
                }
            });

            $("#btnLogin").click(function ()
            {
                dologin();
            });


            function dologin()
            {
                var username = $("#txtUsername").val();
                var password = $("#txtPassword").val();
                if (username == "")
                {
                    alert('账号不能为空!');
                    $("#txtUsername").focus();
                    return;
                }
                if (password == "")
                {
                    alert('密码不能为空!');
                    $("#txtPassword").focus();
                    return;
                }
                $.ajax({
                    type: 'post', cache: false, dataType: 'json',
                    url: 'handler/validate.ashx',
                    data: [
                    { name: 'Action', value: 'Login' },
                    { name: 'username', value: username },
                    { name: 'password', value: password }
                    ],
                    success: function (result)
                    {
                        if (!result)
                        {
                            alert('登陆失败,账号或密码有误!');
                            $("#txtUsername").focus();
                            return;
                        } else
                        {
                            location.href = decodeURIComponent(FromUrl);
                        }
                    },
                    error: function ()
                    {
                        alert('发送系统错误,请与系统管理员联系!');
                    },
                    beforeSend: function ()
                    {
                        $.ligerDialog.waitting("正在登陆中,请稍后...");
                        $("#btnLogin").attr("disabled", true);
                    },
                    complete: function ()
                    {
                        $.ligerDialog.closeWaitting();
                        $("#btnLogin").attr("disabled", false);
                    }
                });
            }
        });
    </script>
</head>
<body style="padding:10px"> 
    <div id="login">
        <div id="loginlogo"></div>

        <div id="loginpanel">
            <div class="panel-h"></div>
            <div class="panel-c">
                <div class="panel-c-l">
                   <form id="login_form" name="login_form" action="" method="post">
                    <table cellpadding="0" cellspacing="0">
                        <tbody>
                         <tr>
                            <td align="left" colspan="2"> 
                             <h3>业务员系统账号登陆</h3>

                            </td>
                            </tr> 
                            <tr>
                            <td align="right">账号：</td><td align="left"><input type="text" name="username" id="username" class="login-text" value="" /></td>
                            </tr>
                            <tr>
                            <td align="right">密码：</td><td align="left"><input type="password" name="password" id="password" class="login-text" value="" /></td>
                            </tr> 
                            <!--<tr>
                            <td align="right">验证码：</td><td align="left"><text type="check" name="captcha" id="captacha" class="login-text" value="ABCDEF" /></td>
                            </tr> -->
                            <tr>
			    <td align="center" colspan="2">
				<input type="hidden" name="submitted" value="submitted" />
                                <input type="submit" id="btnLogin" value="登陆" class="login-btn" />
                            </td>
                            </tr> 
                        </tbody>
                    </table>
		    </form>
                </div>
                <div class="panel-c-r">
                <p>请从左侧输入登录账号和密码登录</p>

                <p>如果遇到系统问题，请联系网络管理员。</p>
                <p>如果没有账号，请联系网站管理员。 </p>
                <p>......</p>
                </div>
            </div>
            <div class="panel-f"></div>
        </div>

         <div id="logincopyright">Copyright ? 2012 91XiaoFeiBao </div>
    </div>
</body>
</html>


