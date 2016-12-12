<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <form name="myform" action="{:url('delete')}" method="post" class="J_ajaxForm">
    <div class="table_list">
      <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <td  align="left" width="20"><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></td>
            <td align="left">VIPID</td>
            <td align="left">VIP名称</td>
            <td align="left">时间</td>
            <td align="left">状态</td>
            <td align="left">所需金额</td>
            <td align="left">操作</td>
          </tr>
        </thead>
        <tbody>
          <volist name="data" id="vo">
            <tr>
              <td align="left"><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x"  value="{$vo.id}" name="id[]"></td>
              <td align="left">{$vo.id}</td>
              <td align="left">{$vo.typename}</td>
              <td align="left">{:getStartTime($vo['starttime'])}</td>
              <td align="left">
                <if condition=" $vo['status'] eq '1' ">启用</if>
                <if condition=" $vo['status'] eq '0' "><font color=red>禁用</font></if>
              </td>
              <td align="left">{$vo.amount}元</td>
              <td align="left">
                <a href="{:url('edit', array('id'=>$vo['id']) )}">[修改]</a>
                <a href="{:url('delete', array('id'=>$vo['id']) )}">[删除]</a>
              </td>
            </tr>
          </volist>
        </tbody>
      </table>
      <div class="p10">
        <div class="pages"> {$Page} </div>
      </div>
    </div>
    <div class="">
      <div class="btn_wrap_pd">
        <button class="btn  mr10 J_ajax_submit_btn" type="submit">删除</button>
      </div>
    </div>
  </form>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
<script src="{$config_siteurl}statics/js/content_addtop.js"></script>
<script>
//会员信息查看
function member_infomation(userid, modelid, name) {
	omnipotent("member_infomation", GV.DIMAUB+'index.php?g=Member&m=Member&a=memberinfo&userid='+userid+'', "个人信息",1)
}
</script>
</body>
</html>