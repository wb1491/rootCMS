<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body>
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="h_a">搜索</div>
  <form method="post" action="{:url('Addons/Wxpay/index',array('isadmin'=>1))}">
    <div class="search_type cc mb10">
      <div class="mb10"> 
        <span class="mr20">订单创建时间：
        <input type="text" name="start_time" class="input length_2 J_date" value="{$start_time}" style="width:80px;">-<input type="text" class="input length_2 J_date" name="end_time" value="{$end_time}" style="width:80px;">
        状态：
        <?php echo  \Form::select(array(
            0 => '等待付款',
            6 => '交易成功',
            5 => '已付款',
            -1 => '交易关闭', 
        ),$order_status,'name="order_status"','不限') ?>
        关键字：
        <?php echo  \Form::select(array(
            'ordersn' => '订单号',
            'subject' => '订单名称',
            'userid' => '会员ID',
            'username' => '用户名',
			'tradeno' => '微信支付单号',
        ),$field,'name="field"') ?>
        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="{$keyword}" placeholder="请输入关键字...">
        <button class="btn">搜索</button>
        </span>
      </div>
    </div>
  </form>
  <div class="table_list">
    <table width="100%" cellspacing="0">
      <thead>
        <tr>
          <td width="105">订单SN</td>
          <td width="170" align="center">微信支付单号</td>
          <td>订单名称</td>
          <td width="50" align="center">会员ID</td>
          <td width="100" align="center">会员名</td>
          <td width="50" align="center">金额</td>
          <td width="50">订单状态</td>
          <td width="110" align="center">创建时间</td>
          <td width="110" align="center">成功时间</td>
        </tr>
      </thead>
      <tbody>
        <volist name="data" id="vo">
        <tr>
          <td>{$vo.ordersn}</td>
          <td align="center">{$vo.tradeno}</td>
          <td>{$vo.subject}</td>
          <td align="center">{$vo.userid}</td>
          <td align="center">{$vo.username}</td>
          <td align="center">{$vo.price}</td>
          <td><?php 
            //0 => '等待付款',
            //6 => '交易成功',
            //5 => '已付款',
            //-1 => '交易关闭',
             switch ($vo['order_status']){
                 case 0:
                     echo "等待付款";
                     break;
                 case 5:
                     echo "已付款";
                     break;
                 case 6:
                     echo "交易成功";
                     break;
                 case -1;
                     echo "交易关闭";
                     break;
             }
          ?></td>
          <td align="center">{$vo.createtime|date="Y-m-d H:i:s",###}</td>
          <td align="center"><?php
          if($vo['pay_time']>0){
              echo date("Y-m-d H:i:s",$vo['pay_time']);
          }else{
              echo "";
          }?></td>
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