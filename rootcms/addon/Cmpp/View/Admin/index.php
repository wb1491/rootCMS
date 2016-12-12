<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="h_a">创蓝短信</div>
  <form class="J_ajaxForm" action="{:url('postSMS','isadmin=1')}" method="post" id="myform">
  <div class="table_full">
      <table width="100%">
        <tr>
          <th width='150'>剩余短信条数</td>
          <td><font id="lastsms" color='red'></font>条</td>
        </tr>
        <tr>
          <th>手机号码</th>
          <td>
            <textarea name="mobile" rows="2" cols="20" id="mobile" class="inputtext" style="height:50px;width:500px;">{$data.remark}</textarea>
            多个手机号码之间使用半角英文逗号“,”分隔，或者一行一个号码
          </td>
        </tr>
        <tr>
          <th>信息</th>
          <td>
            <textarea name="message" rows="2" cols="20" id="message" class="inputtext" style="height:100px;width:500px;">{$data.remark}</textarea>
          </td>
        </tr>
      </table>
    
  </div>
  <div class="">
      <div class="btn_wrap_pd">             
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">发送</button>
      </div>
    </div>
    </form>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
<script type="text/javascript">
$(function (){
	//点击
	$.get("{:url('queryBalance','isadmin=1')}", function (data) {
	    $("#lastsms").html(data.balance);
	}, "json");
});
</script>
</body>
</html>