function ajaxForm(dom){
    var url = $(dom).attr('action');
    var data = $(dom).serialize();

    $.ajax({
        url : url,
        data : data,
        type : 'post',
        dataType : 'json',
        success:function(i) {
            if (i.status == 1) {
                var d = dialog({
                    title: '请求成功!',
                    content: i.info,
                    okValue : '确定',
                    ok : function(){
                        window.location.href = i.url;
                    }
                });
                d.showModal();
            } else {
                var d = dialog({
                    title: '发生错误了!',
                    content: i.info,
                    ok : function(){
                        if(i.url) {
                            window.location.href = i.url;
                        }
                    },
                    okValue : '确定',
                });
                d.showModal();
            }
        }
    })
    return false;
}

function ajaxBtn(dom) {
    var url = $(dom).attr('data-href');

    var d = dialog({
        title: '提示！',
        content: '您确定继续么?',
        ok: function () {
            $.ajax({
                url : url,
                dataType : 'json',
                success : function(i) {
                    if (i.status == 1) {
                        window.location.href = i.url;
                    } else {
                        var d = dialog({
                            title: '发生错误了!',
                            content: i.info,
                        });
                        d.showModal();
                    }
                }
            })
        },

        okValue : '确定',
        cancel : function(){},
        cancelValue : '取消',
    });
    d.showModal();
    return false;
}