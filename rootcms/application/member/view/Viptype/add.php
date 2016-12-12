<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="h_a">添加VIP会员等级</div>
  <form name="myform" action="{:url('add')}" method="post" class="J_ajaxForm">
  <div class="table_full">
  <table width="100%" class="table_form">
		<tr>
			<th width="80">VIP等级名</th> 
			<td><input type="text" name="typename"  class="input" id="typename"/></td>
		</tr>
		<tr>
			<th>限制时间</th> 
            <td><?php echo \Form::select($stattime,0,"name='starttime'")?></td>
		</tr>
		<tr>
			<th>状态</th> 
            <td><?php echo \Form::radio(array("禁用","启用"),1,"name='status'");?></td>
		</tr>
		<tr>
			<th>金额</th> 
			<td><input type="text" name="amount" id="amount" value="" class="input"/></td>
		</tr>
        
	</table>
  </div>
   <div class="">
      <div class="btn_wrap_pd">             
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">添加</button>
      </div>
    </div>
  </form>
</div>
<script src="{$config_siteurl}statics/js/common.js"></script>
</body>
</html>