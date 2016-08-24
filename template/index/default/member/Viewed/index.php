<php>
  $SEO['title'] = "观看记录 -- ".$Config['sitename'];
</php>
<template file='member/Publics/homeHeader.php'/>
<template file="Content/header_nav.php"/>

<div class="container userprofile">
  <div class="row indexblock">
    <div class="col-md-2">
      <template file="member/Publics/homeUserMenu.php"/>
    </div>
    <div class="col-md-10 sm-paddingfix">
      <ul class="nav nav-tabs">
        <li <if condition=" !$type ">class="active"</if>>
        <a target="_slef" href="{:url('Viewed/index')}">我的观看记录</a>
        </li>
      </ul>
      <div class="tab-content border">
          <if condition=" empty($favorite) ">
            <div class="favoritesList shareList">
              <div class="nothing" style="padding-top: 20px;padding-left:40px;">您还没有观看过付费视频。</div>
            </div>
          <else />
            <table class="table table-striped table-bordered text-left my-orders-table">
                <thead>
                  <tr class="first last">
                    <td class="text-center">标题</td>
                    <td class="text-center">观看时间</td>
                    <td class="text-center">付费金额</td>
                    </li>
                </thead>
                <tbody>
                  <volist name="favorite" id="vo">
                    <tr>
                      <td>观看“{$vo.title}”</td>
                      <td>{$vo.createtime|format_date}</td>
                      <td>{$vo.price}</td>
                    </tr>
                  </volist>
                </tbody>
            </table>
          </if>
      </div>
    </div>
  </div>
</div>

<template file="Content/footer.php"/>

<script>
function del(id) {
    $.dialog({
    id: 'delAll',
            title: false,
            border: false,
            follow: $("#del" + id)[0],
            content: '确认删除此分享吗？',
            okValue: '确认',
            ok: function () {
            $.ajax({
            type: "POST",
                    global: false, // 禁用全局Ajax事件.
                    url: _config['domainSite'] + "index.php?g=Member&m=Share&a=del",
                    data: {
                    'id': id
                    },
                    dataType: "json",
                    success: function (data) {
                    if (data['error'] == 20001) {
                    libs.userNotLogin('您未登录无法执行此操作！');
                    } else if (data['error'] == 20002) {
                    $.tipMessage("对不起，你无权删除", 1, 3000, 0, function () {
                    location.href = location.href;
                    });
                    } else if (data['error'] == 10000) {
                    location.href = location.href;
                    } else {
                    $.tipMessage(data['info'], 1, 3000, 0);
                    }
                    }
            });
            },
            cancelValue: '取消',
            cancel: function () {

            }
    });
}
</script>
</body>
</html>