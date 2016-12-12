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
            <td align="left">姓名</td>
            <td align="left">课程名称</td>
            <td align="left">人数</td>
            <td align="left">报名时间</td>
            <td align="left">手机号码</td>
            <td align="left">联系号码</td>
            <td align="left">状态</td>
            <td align="left">操作</td>
          </tr>
        </thead>
        <tbody>
          <volist name="data" id="vo">
            <tr>
              <td align="left"><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x"  value="{$vo.id}" name="id[]"></td>
              <td align="left">{$vo.name}</td>
              <td align="left">{$vo.cateid|getStuCate}</td>
              <td align="left">{$vo.number}人</td>
              <td align="left">{$vo.createtime|date="Y-m-d H:i:s",###}</td>
              <td align="left">{$vo.mobile}</td>
              <td align="left">{$vo.phone}</td>
              <td align="left">{$vo.status|getStuStatus}</td>
              <td align="left"><a href="{:url('edit', array('id'=>$vo['id']) )}">[修改]</a></td>
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
</body>
</html>