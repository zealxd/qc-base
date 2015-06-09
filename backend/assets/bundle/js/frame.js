/**
 * 后台菜单js
 */

//iframe 加载事件
var iframe_default = document.getElementById('iframe_default');
$(iframe_default.contentWindow.document).ready(function() {
    $('#loading').hide();
    $(iframe_default).show();
});

var USUALL = [], /*常用的功能模块*/
TEMP = [],
SUALL = USUALL.concat('-', [{
        name: '最近操作',
        disabled: true
    }], TEMP),
SUBMENU_CONFIG = SUBMENU, /*主菜单区*/
imgpath = '',
times = 0,
getdescurl = '',
searchurl = '',
token = "";

//一级菜单展示
$(function() {
    var html = [];
    //console.log(SUBMENU_CONFIG);
    $.each(SUBMENU_CONFIG, function(i, o) {
        html.push('<li><a href="" title="' + o.tip + '" data-id="' + o.id + '">' + o.name + '</a></li>');
    });
    $('#J_B_main_block').html(html.join(''));

    //后台位在第一个导航
    $('#J_B_main_block li:first > a').click();
});

function checkMenuNext() {
    var B_menunav = $('#B_menunav');
    var menu_next = $('#menu_next');
    if (B_menunav.offset().top + B_menunav.height() >= $(window).height() || B_menunav.offset().top < B_menunav.parent().offset().top) {
        menu_next.show();
    } else {
        menu_next.hide();
    }
}

$(window).on('resize', function() {
    setTimeout(function() {
        checkMenuNext();
    }, 100);
});

//上一页下一页的点击
(function() {
    var menu_next = $('#menu_next');
    var B_menunav = $('#B_menunav');
    menu_next.on('click', 'a', function(e) {
        e.preventDefault();
        if (e.target.className === 'pre') {
            if (B_menunav.offset().top < B_menunav.parent().offset().top) {
                B_menunav.animate({'marginTop': '+=28px'}, 100);
            }
        } else if (e.target.className === 'next') {
            if (B_menunav.offset().top + B_menunav.height() >= $(window).height()) {
                B_menunav.animate({'marginTop': '-=28px'}, 100);
            }
        }
    });
})();
//一级导航点击
$('#J_B_main_block').on('click', 'a', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).parent().addClass('current').siblings().removeClass('current');
    var data_id = $(this).attr('data-id'), data_list = SUBMENU_CONFIG[data_id], html = [], child_html = [], child_index = 0, B_menubar = $('#B_menubar');

    if (B_menubar.attr('data-id') == data_id) {
        return false;
    }
    ;
    show_left_menu(data_list['items']);
    B_menubar.html(html.join('')).attr('data-id', data_id);

    //检查是否应该出现上一页、下一页
    checkMenuNext();

    //显示左侧菜单
    function show_left_menu(data) {

        for (var attr in data) {
            if (data[attr] && typeof (data[attr]) === 'object') {
                //循环子对象

                if (!data[attr].url && attr === 'items') {
                    //子菜单添加识别属性
                    $.each(data[attr], function(i, o) {
                        child_index++;
                        o.isChild = true;
                        o.child_index = child_index;
                    });
                }
                show_left_menu(data[attr]); //继续执行循环(筛选子菜单)
            } else {
                if (attr === 'name') {
                    data.url = data.url ? data.url : '#';
                    if (!(data['isChild'])) {
                        //一级菜单
                        html.push('<dt><a href="' + data.url + '" data-id="' + data.id + '">' + data.name + '</a></dt>');
                    } else {
                        //二级菜单
                        child_html.push('<li><a href="' + data.url + '" data-id="' + data.id + '">' + data.name + '</a></li>');

                        //二级菜单全部push完毕
                        if (data.child_index == child_index) {
                            html.push('<dd style="display:block;"><ul>' + child_html.join('') + '</ul></dd>');
                            child_html = [];
                        }
                    }
                }
            }
        }
    }
    ;

});

//左边菜单点击
$('#B_menubar').on('click', 'a', function(e) {
    e.preventDefault();
    e.stopPropagation();


    var $this = $(this), _dt = $this.parent(), _dd = _dt.next('dd');
    $("#B_menubar li").removeClass('current');
    //当前菜单状态
    _dt.addClass('current').siblings('dt.current').removeClass('current');

    //子菜单显示&隐藏
    if (_dd.length) {
        _dt.toggleClass('current');
        _dd.toggle();
        //检查上下分页
        checkMenuNext();
        return false;
    }
    ;

    $('#loading').show().focus();//显示loading
    $('#B_history li').removeClass('current');
    var data_id = $(this).attr('data-id'), li = $('#B_history li[data-id=' + data_id + ']');
    var href = this.href;


    iframeJudge({
        elem: $this,
        href: href,
        id: data_id
    });

});


/*
 * 搜索
 */
var search_keyword = $('#J_search_keyword'),
        search = $('#J_search');
search.on('click', function(e) {
    e.preventDefault();
    var $this = $(this),
            search_val = $.trim(search_keyword.val());
    if (search_val) {
        iframeJudge({
            elem: $this,
            href: $this.data('url') + '&keyword=' + search_val,
            id: 'search'
        });
    }
});
//回车搜索
search_keyword.on('keydown', function(e) {
    if (e.keyCode == 13) {
        search.click();
    }
});


//判断显示或创建iframe
function iframeJudge(options) {
    var elem = options.elem,
            href = options.href,
            id = options.id,
            li = $('#B_history li[data-id=' + id + ']');
    if (!(elem instanceof jQuery)) {
        elem = $(elem);
    }

    if (li.length > 0) {
        //如果是已经存在的iframe，则显示并让选项卡高亮,并不显示loading
        var iframe = $('#iframe_' + id);
        $('#loading').hide();
        li.addClass('current');
        if (iframe[0].contentWindow && iframe[0].contentWindow.location.href !== href) {
            iframe[0].contentWindow.location.href = href;
        }
        $('#B_frame iframe').hide();
        $('#iframe_' + id).show();
        showTab(li);//计算此tab的位置，如果不在屏幕内，则移动导航位置
    } else {
        //创建一个并加以标识
        var iframeAttr = {
            src: href,
            id: 'iframe_' + id,
            frameborder: '0',
            scrolling: 'auto',
            height: '100%',
            width: '100%'
        };
        var iframe = $('<iframe/>').prop(iframeAttr).appendTo('#B_frame');
        var iframeTitle = elem.html();
        if (options.title) {
            iframeTitle = options.title;
        }
        $(iframe[0].contentWindow.document).ready(function() {
            $('#B_frame iframe').hide();
            $('#loading').hide();
            var li = $('<li tabindex="0"><span><a>' + iframeTitle + '</a><a class="del" title="关闭此页">关闭</a></span></li>').attr('data-id', id).addClass('current');
            li.siblings().removeClass('current');
            li.appendTo('#B_history');
            showTab(li);//计算此tab的位置，如果不在屏幕内，则移动导航位置
            //$(this).show().unbind('load');
        });


    }


}

//顶部点击一个tab页
$('#B_history').on('click focus', 'li', function(e) {
    e.preventDefault();
    e.stopPropagation();
    var data_id = $(this).data('id');
    $(this).addClass('current').siblings('li').removeClass('current');
    $('#iframe_' + data_id).show().siblings('iframe').hide();//隐藏其它iframe
});

//顶部关闭一个tab页
$('#B_history').on('click', 'a.del', function(e) {
    e.stopPropagation();
    e.preventDefault();
    var li = $(this).parent().parent(),
            prev_li = li.prev('li'),
            data_id = li.attr('data-id');
    li.hide(60, function() {
        $(this).remove();//移除选项卡
        $('#iframe_' + data_id).remove();//移除iframe页面
        var current_li = $('#B_history li.current');
        //找到关闭后当前应该显示的选项卡
        current_li = current_li.length ? current_li : prev_li;
        current_li.addClass('current');
        cur_data_id = current_li.attr('data-id');
        $('#iframe_' + cur_data_id).show();
    });
});

//刷新
$('#J_refresh').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    var id = $('#B_history .current').attr('data-id'), iframe = $('#iframe_' + id);
    if (iframe[0].contentWindow) {
        //common.js
        reloadPage(iframe[0].contentWindow);
    }
});

//全屏/非全屏
$('#J_fullScreen').toggle(function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(document.body).addClass('fullScreen');
}, function() {
    $(document.body).removeClass('fullScreen');
});

//下一个选项卡
$('#J_next').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    var ul = $('#B_history'),
            current = ul.find('.current'),
            li = current.next('li');
    showTab(li);
});

//上一个选项卡
$('#J_prev').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    var ul = $('#B_history'),
            current = ul.find('.current'),
            li = current.prev('li');
    showTab(li);
});

//显示顶部导航时作位置判断，点击左边菜单、上一tab、下一tab时公用
function showTab(li) {
    if (li.length) {
        var ul = $('#B_history'),
                li_offset = li.offset(),
                li_width = li.outerWidth(true),
                next_left = $('#J_next').offset().left - 9, //右边按钮的界限位置
                prev_right = $('#J_prev').offset().left + $('#J_prev').outerWidth(true);//左边按钮的界限位置
        if (li_offset.left + li_width > next_left) {//如果将要移动的元素在不可见的右边，则需要移动
            var distance = li_offset.left + li_width - next_left;//计算当前父元素的右边距离，算出右移多少像素
            ul.animate({left: '-=' + distance}, 200, 'swing');
        } else if (li_offset.left < prev_right) {//如果将要移动的元素在不可见的左边，则需要移动
            var distance = prev_right - li_offset.left;//计算当前父元素的左边距离，算出左移多少像素
            ul.animate({left: '+=' + distance}, 200, 'swing');
        }
        li.trigger('click');
    }
}

