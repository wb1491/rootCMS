<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body>
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="btn_wrap_pd"><a href="{:url('add?isadmin=1')}" class="btn" title="添加新类型"><span class="add"></span>添加新类型</a>
  </div>
  <div class="table_list">
    <table width="100%" cellspacing="0">
      <thead>
        <tr>
          <td width="40">ID</td>
          <td width="150">名称</td>
          <td>备注</td>
          <td width="50" align="center">金额</td>
          <td width="70">状态</td>
          <td width="120" align="center">操作</td>
        </tr>
      </thead>
      <tbody>
        <volist name="data" id="vo">
        <tr>
          <td>{$vo.id}</td>
          <td><a href="{:url('Addons/Alipay/index',array('id'=>$vo['id']))}" target="_blank">{$vo.name}</a></td>
          <td>{$vo.remark}</td>
          <td align="center">{$vo.price}</td>
          <td><if condition="$vo['status'] eq 1">启用<else/>禁用</if> </td>
          <td align="center"><a href="{:url('edit?isadmin=1',array('id'=>$vo['id']))}" >修改</a> | <a href="{:url('delete?isadmin=1',array('id'=>$vo['id']))}" class="J_ajax_del" >删除</a></td>
        </tr>
        </volist>
      </tbody>
    </table>
    <div class="p10">
      <div class="pages"> {$Page} </div>
      </div>
  </div>
</div>
<script src="{$config_siteurl}statics/js/common.js"></script>
<script>
function otherShow(id){
	$('.otherlist').hide();
	$('#other_'+id).show();
}
</script>
</body>
</html>