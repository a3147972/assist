function ajaxForm(dom) {
    var url = $(dom).attr('action');
    var data = $(dom).serialize();

    $.ajax({
        url : url,
        data : data,
        type : 'post',
        dataType : 'json',
        success : function (i) {
            if (i.status == 1) {
                window.location.href = i.url;
            } else {
                dialog_alert('发生错误了', i.info);
            }
        }
    })

    return false;
}

function ajaxBtn(dom) {
    var url = $(dom).attr('data-href');

    $.ajax({
        url : url,
        dataType : 'json',
        success : function (i) {
            if (i.status == 1) {
                window.location.href = i. url;
            } else {
                dialog_alert('发生错误了', i.info);
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
    });
    d.showModal();
}

function dialog_confirm(title,content)
{
    var d = dialog({
        title: title,
        content: content,
        okValue: '取消',
        ok: function () {},
        cancelValue : '取消',
        cancel:function()
        {
            return false;
        }
    });
    d.showModal();
}