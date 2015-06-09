/**
 * 通用js
 */

/**
 * 通用的弹窗
 *
 * 内含如下方法，用于取代原生的浏览器弹窗：
 *
 * - confirm 确定提示窗口，主要参数有 id：弹窗id、content：弹窗内容、width：弹窗宽度、url：确定跳转地址
 * - alert 页面提示框，主要参数有 type：提示类型（success或者error）、content：提示内容、width：弹窗宽度
 */
var cdialog = {
    confirm: function (args) {
        var id = args.id || 'confirm-dialog';
        var content = args.content || '确定吗？';
        var width = args.width || 200;
        var url = args.url || '';
        if (args.okCallback) {
            var okCallback = args.okCallback;
        }
        else {
            var okCallback = function () {
                window.location.href = url;
            };
        }
        var options = {
            id: id,
            content: content,
            width: width,
            okValue: '确定',
            quickClose: true,
            ok: okCallback,
            cancelValue: '取消',
            cancel: function () {}
        };
        if (args.title) {
            options.title = args.title;
        }
        var d = dialog(options);
        d.show(args.thisObj);
    },
    alert: function (args) {
        var type = args.type || 'success';
        //var title = args.title || (type == 'success' ? '成功' : '失败') + '提示';
        var content = args.content || '';
        var width = args.width || 200;
        var spanClass = (type == 'success') ? 'tips_success' : 'tips_error';
        content = '<span class="' + spanClass + '">' + content + '</span>';
        var d = dialog({
            //title: title,
            width: width,
            content: content
        });
        d.show();
        if (args.time) {
            setTimeout(function () {
                d.close().remove();
            }, args.time);
            if (args.url) {
                setTimeout(function () {
                    window.location.href = args.url;
                }, args.time);
            }
        }
        
    }
};

//提交锁定，防止重复提交
var submitLock = 0;

/**
 * 普通ajax请求响应
 *
 * @param json object 服务器返回的json数据
 * @param reload boolean 成功后是否刷新页面
 */
var handleAjaxReponse = function (json, reload) {
    var reload = reload || false;
    if (json.type == 'success') {
        cdialog.alert({content: json.message, time: json.time, url: json.url});
        if (reload) {
            setTimeout(function () {
                window.location.reload();
            }, json.time);
        }
    }
    else {
        cdialog.alert({type: json.type, content: json.message, time: json.time, width: 250});
    }
}

/**
 * 表单提交响应处理
 *
 * 根据服务器具体的响应提示不同的信息，可以自动定位字段错误并打印错误信息
 */
var handleFormResponse = function (json) {
    if (json.type == 'success') {
        cdialog.alert({content: json.message, time: json.time, url: json.url});
    }
    else if (json.data.errors.length <= 0) {
        cdialog.alert({type: json.type, content: json.message, time: json.time, width: 250});
    }
    else {
        var alertErrors = [];
        //这里循环把错误显示在字段后面
        $.each (json.data.errors, function(id, errors) {
            errors = errors.join('<br />');
            if ($("#" + id).next('.help-block').length > 0) {
                $("#" + id).next('.help-block').html(errors);
                $('.field-' + id).removeClass('has-success').addClass('has-error');
            }
            else {
                alertErrors.push(errors);
            }
        });
        //防止有些错误无法找到地方显示，则直接弹出窗口提示
        if (alertErrors.length > 0) {
            alertErrors = alertErrors.join('<br />');
            cdialog.alert({type: json.type, content: alertErrors, time: json.time, width: 250});
        }
    }
    if (json.time) {
        setTimeout(function () {
            submitLock = 0;
        }, json.time);
    }
    else {
        submitLock = 0;
    }
};

jQuery(function($) {

    //删除全选
    $(document).on('click', '.box-select-all', function() {
        if ($(this).is(":checked"))
        $(".box-delete").prop('checked', true);
        else
        $(".box-delete").prop('checked', false);
    });

    //删除提示
    $(document).on('click', '.link-delete', function() {
        var url = $(this).attr('href');
        var deleteDialog = dialog.get('delete-dialog');
        if (deleteDialog !== undefined) {
            deleteDialog.remove();
        }
        var deleteCallBack = function () {
            $.getJSON(url, function (json) {
                handleAjaxReponse(json, true);
            });
        }
        cdialog.confirm({thisObj:this, id:'delete-dialog', content: '确定要删除吗？', width: 150, okCallback: deleteCallBack});

        return false;
    });

    //批量删除
    $(document).on('click', '.batch-delete', function() {
        var length = $(".box-delete:checked").length;
        if (length == 0) {
            alert('请至少选择一项');
            return false;
        }
        if (!confirm('确认删除?'))
        return false;
        var ids = '';
        var url = $(this).data('url');

        $("#main-form").attr('action', url).trigger('submit');

    });

    //批量排序
    $(document).on('click', '.batch-sort', function() {
        var url = $(this).data('url');

        $("#main-form").attr('action', url).trigger('submit');
    });

    //class未ajax.form的表单全部以ajax请求提交
    $(".ajax-form").on('beforeSubmit', function() {
        var form = $(this);
        if (form.find('.has-error').length > 0) {
            return false;
        }
        if (submitLock == 1) {
            return false;
        }
        else {
            submitLock = 1;
        }

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: handleFormResponse
        });

        return false;
    });

    /**
     * 动态调整分页数
     */
    $("#page-size").on('blur', function () {
        var pageSize = parseInt($(this).val());
        var oldPageSize = parseInt($(this).data('oldPageSize'));
        var url = $(this).data('url');

        if (pageSize == oldPageSize) {
            return;
        }
        if (isNaN(pageSize)) {
            cdialog.alert({type: 'error', content: '分页数必须是数字!', time: 2000});
        }
        else {
            url += ((url.indexOf('?') === -1) ? '?' : '&') + 'page_size=' + pageSize;
            window.location.href = url;
        }
    });

    /**
     * 弹出tab使用方法，增加名为editByIframe的class 
     * 增加属性
     * @param data-href 你跳转处理数据的地址
     * @param data-id 跳转出去的iframe的ID,方便再次点击时候找到你已经打开的iframe
     * @param data-title iframe tab的名称
     */
    $(document).on('click', '.editByIframe', function() {
        var $this = $(this);
        var href = $this.data('href');
        var id = $this.data('id');
        var title = $this.data('title');
        window.parent.iframeJudge({
            elem: $this,
            href: href,
            id: id,
            title: title
        });
        return false;//阻止a本身的跳转
    });

    /**
     * 自动给table添加序号，tr内在添加序号的地方加class key_num
     */
    var len = $('table tr').length;
    for (var i = 1; i < len; i++) {
        $('table tr:eq(' + i + ') .key_num').text(i);
    }

    /*必填项加*号*/
    $(".required th label").after("<span class='must_red'>&nbsp;*</span>");


    //不支持placeholder浏览器下对placeholder进行处理
    if (document.createElement('input').placeholder !== '') {
        $('[placeholder]').focus(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function() {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur().parents('form').submit(function() {
            $(this).find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            });
        });
    }


    //提交按钮是否固定底部
    setBtnWrap();
    /*$(window).on('resize', function(){
      setBtnWrap(true);
      });*/




    //iframe页面f5刷新
    $(document).on('keydown', function(event) {
        var e = window.event || event;
        if (e.keyCode == 116) {
            e.keyCode = 0;

            var $doc = $(parent.window.document),
            id = $doc.find('#B_history .current').attr('data-id'),
            iframe = $doc.find('#iframe_' + id);
            if (iframe[0].contentWindow) {
                //common.js
                reloadPage(iframe[0].contentWindow);
            }

            //!ie
            return false;
        }

    });


});

//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
    var location = win.location;
    location.href = location.pathname + location.search;
}

function setBtnWrap(reset) {
    if (parent.Wind && parent.Wind.dialog) {
        //过滤弹窗
        return;
    }

    if ($('body').height() <= $(window).height()) {
        $('div.btn_wrap').removeClass('btn_wrap');
    } else {
        if (reset) {
            var par = $('button.J_ajax_submit_btn:last').parent().parent();
            if (!par.attr('class')) {
                //class一定为空
                par.addClass('btn_wrap');
            }
        }
    }
}
