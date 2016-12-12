<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body>
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="h_a">搜索</div>
  <form method="post" action="{:url('log',array('isadmin'=>1))}">
    <div class="search_type cc mb10">
      <div class="mb10"> 
        <span class="mr20">
        关键字：
        <?php echo  \Form::select(array(
            'orderid' => '订单ID',
            'subject' => '日志标题',
        ),$field,'name="field"') ?>
        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="{$keyword}" placeholder="请输入关键字...">
        <input type="hidden" name="isadmin" value="1">
        <button class="btn">搜索</button>
        </span>
      </div>
    </div>
  </form>
  <div class="table_list">
    <table width="100%" cellspacing="0">
      <thead>
        <tr>
          <td>日志ID</td>
          <td>订单ID</td>
          <td >是否系统</td>
          <td>标题</td>
          <td align="center">创建时间</td>
          <td align="center">详情</td>
        </tr>
      </thead>
      <tbody>
        <volist name="data" id="vo">
        <tr>
          <td>{$vo.id}</td>
          <td>{$vo.orderid}</td>
          <td><if condition="$vo['system'] ">系统<else/>其他</if></td>
          <td>{$vo.subject}</td>
          <td align="center">{$vo.createtime|date='Y-m-d H:i:s',###}</td>
          <td align="center"><a href="javascript:;;" onClick="otherShow({$vo.id})">查看订单详情</a></td>
        </tr>
        <tr id="log_{$vo.id}" style="display:none;" class="loglist">
          <td colspan="6">
          <textarea style=" height:200px; width:100%"><?php $log = json_decode($vo['log'],true);$log['other'] = unserialize($log['other']);print_r($log);?></textarea>
          </td>
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
	$('.loglist').hide();
	$('#log_'+id).show();
}
</script>
</body>
</html>