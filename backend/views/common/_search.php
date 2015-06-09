
<div class="h_a">搜索</div>
<form method="post"  action="http://127.0.0.1:8080/phpwind9/admin.php?m=design&c=component" >
    <div class="search_type cc mb10">
        <label>关键字：</label><input type="text" class="input length_2 mr10" name="compid">
        <label>搜索类型：</label>
        <select class="select_2 mr10" name="flag">
            <option value="">模块分类</option>
            <option value="forum" >版块</option>
            <option value="html" >自定义html</option>
            <option value="image" >图片</option>
            <option value="link" >友情链接</option>
            <option value="searchbar" >搜索条</option>
            <option value="tag" >话题</option>
            <option value="thread" >帖子</option>
            <option value="user" >用户</option>
        </select>
        <button class="btn">搜索</button>
    </div>
    <input type="hidden" name="csrf_token" value="62532b5cc3d77233"/>
</form>
