<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body>
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="h_a">搜索</div>
  <form method="post" action="{:url('index',array('isadmin'=>1))}">
    <div class="search_type cc mb10">
      <div class="mb10"> 
        <span class="mr20">订单创建时间：
        <input type="text" name="start_time" class="input length_2 J_date" value="{$start_time}" style="width:80px;">-<input type="text" class="input length_2 J_date" name="end_time" value="{$end_time}" style="width:80px;">
        状态：
        <?php echo  \Form::select(array(
            0 => '未完成订单',
            1 => '交易成功',
            -1 => '等待付款',
            -2 => '已付款',
            -3 => '已发货',
            -4 => '交易关闭', 
        ),$tradestatus,'name="tradestatus"','不限') ?>
        关键字：
        <?php echo  \Form::select(array(
            'subject' => '订单名称',
            'userid' => '会员ID',
            'username' => '用户名',
			'tradeno' => '支付宝交易号',
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
          <td width="40">订单ID</td>
          <td width="120" align="center">支付宝交易号</td>
          <td>订单名称</td>
          <td width="50" align="center">会员ID</td>
          <td width="50" align="center">会员名</td>
          <td width="50" align="center">金额</td>
          <td width="70">订单状态</td>
          <td width="70" align="center">商品数量</td>
          <td width="120" align="center">创建时间</td>
          <td width="120" align="center">最后操作时间</td>
          <td width="80" align="center">订单详情</td>
        </tr>
      </thead>
      <tbody>
        <volist name="data" id="vo">
        <tr>
          <td>{$vo.id}</td>
          <td align="center">{$vo.tradeno}</td>
          <td>{$vo.subject}</td>
          <td align="center">{$vo.userid}</td>
          <td align="center">{$vo.username}</td>
          <td align="center">{$vo.price}</td>
          <td><?php echo \Addon\Alipay\Model\AlipayModel::getInstance()->getTradeStatusName($vo['tradestatus']) ?></td>
          <td align="center">{$vo.quantity}</td>
          <td align="center">{$vo.createtime|date="Y-m-d H:i:s",###}</td>
          <td align="center">{$vo.lasttime|date="Y-m-d H:i:s",###}</td>
          <td><a href="javascript:;;" onClick="otherShow({$vo.id})">查看订单详情</a></td>
        </tr>
        <tr id="other_{$vo.id}" style="display:none;" class="otherlist">
          <td colspan="11">
          <textarea style=" height:200px; width:100%"><?php print_r(unserialize($vo['other']));?></textarea>
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
	$('.otherlist').hide();
	$('#other_'+id).show();
}
</script>
</body>
</html>