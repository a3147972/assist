function login(dom) {
    var url = $(dom).attr('action');
    var username = $('input[name=username]').val();
    var password = $('input[name=password]').val();

    if (username == '') {
        dialog_alert('发生错误了', '请输入用户名');
        return false;
    }
    if (password == '') {
        dialog_alert('发生错误了', '请输入密码');
        return false;
    }

    $.ajax({
        url : url,
        data : {
            username : username,
            password : password,
        },
        type : 'post',
        dataType : 'json',
        success : function (i) {
            if (i.status == 1) {
                window.location.href = i.url;
            } else {
                dialog_alert('发生错误了' ,i.info);
            }
        }
    })
    return false;
}

function dialog_alert(title, content)
{
    var d = dialog({
        title: title,
        content: content,
        width : '300px',
    });
    d.showModal();
}