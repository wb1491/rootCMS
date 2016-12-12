<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap">
  <Admintemplate file="Common/Nav"/>
  <form class="J_ajaxForm" action="{:url('add?isadmin=1')}" method="post">
    <div class="h_a">基本属性</div>
    <div class="table_full">
      <table width="100%" class="table_form contentWrap">
        <tbody>
          <tr>
            <th width="80">类型名称</th>
            <td><input type="test" name="name" class="input length_6" id="name" value=""></td>
          </tr>
          <tr>
            <th>金额</th>
            <td><input type="test" name="price" class="input length_6" id="price" value="100"></td>
          </tr>
          <tr>
            <th>回调</th>
            <td><input type="test" name="callback" class="input length_6" id="callback" placeholder="例如：Demo，指 Demo.class.php文件">
            <span class="gray">支付成功后的回调处理，放在插件目录下的Callback文件夹下，填写对应的文件名即可。</span></td>
          </tr>
          <tr>
            <th>备注</th>
            <td><textarea name="remark" style="width:400px;"></textarea></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="btn_wrap1">
      <div class="btn_wrap_pd">
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">添加</button>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript" src="{$config_siteurl}statics/js/common.js"></script>
<script type="text/javascript" src="{$config_siteurl}statics/js/content_addtop.js"></script>
<script type="text/javascript">
//获取对应插件setting配置信息
function getAddonsSetting(name){
	$.get("{:url('Wechat/public_setting')}",{name:name},function(html){
		$('#setting').html(html);
	});
}
$(function(){
	$('form.J_ajaxForm select[name="addons"]').click(function(){
		var name = $(this).val();
		if(name == ''){
			$('#setting').html('');
			return false;
		}
		getAddonsSetting(name);
	});
});
</script>
</body>
</html>