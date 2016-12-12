<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body>
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="h_a">搜索</div>
  <form method="post" action="{:url('amontlog',array('isadmin'=>1))}">
    <div class="search_type cc mb10">
      <div class="mb10"> 
        <span class="mr20">资金日志创建时间：
        <input type="text" name="start_time" class="input length_2 J_date" value="{$start_time}" style="width:80px;">-<input type="text" class="input length_2 J_date" name="end_time" value="{$end_time}" style="width:80px;">
        类型：
        <?php echo  \Form::select(array(
            1 => '账户充值',
            2 => '升级VIP费用',
            3 => '观看付费内容',
        ),$order_status,'name="type"','不限') ?>
        关键字：
        <?php echo  \Form::select(array(
            'ordersn' => '订单号',
            'userid' => '会员ID',
            'username' => '用户名',
			'title' => '付费内容标题',
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
          <td width="50">ID</td>
          <td width="105" align="center">订单编号</td>
          <td width="50" align="center">会员ID</td>
          <td width="100" align="center">会员名</td>
          <td width="50" align="center">金额</td>
          <td width="80">类型</td>
          <td width="110" align="center">创建时间</td>
          <td>备注</td>
        </tr>
      </thead>
      <tbody>
        <volist name="data" id="vo">
        <tr>
          <td>{$vo.id}</td>
          <td align="center">{$vo.ordersn}</td>
          <td align="center">{$vo.userid}</td>
          <td align="center">{$vo.username}</td>
          <td align="center">{$vo.price}</td>
          <td><?php 
             switch ($vo['type']){
                 case 1:
                     echo "账户充值";
                     break;
                 case 2:
                     echo "升级VIP费用";
                     break;
                 case 3:
                     echo "观看付费内容";
                     break;
             }
          ?></td>
          <td align="center">{$vo.createtime|date="Y-m-d H:i:s",###}</td>
          <td>{$vo.msg}</td>
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