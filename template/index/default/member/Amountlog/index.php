<php>
  $SEO['title'] = "资金日志 -- ".$Config['sitename'];
</php>
<template file='member/Publics/homeHeader.php'/>
<template file="Content/header_nav.php"/>

<div class="container userprofile">
  <div class="row indexblock">
    <div class="col-md-2">
      <template file="member/Publics/homeUserMenu.php"/>
    </div>
    <div class="col-md-10 sm-paddingfix">
      <ul class="nav nav-tabs">
        <li <if condition="ACTION_NAME eq 'index'"> class="active"</if>>
        <a href="#tab_1" data-toggle="tab" href="{:url('Amountlog/index')}">我的资金日志</a>
        </li>
      </ul>
      <div class="tab-content border" id="favoritesList">
        <div class="tab-pane fade active in" id="tab_1">
          <if condition=" empty($log) ">
            <div class="favoritesList shareList">
              <div class="nothing" style="padding-top: 20px;padding-left:40px;">您没有资金记录。</div>
            </div>
            <else/>
            <table class="table table-striped table-bordered text-left my-orders-table">
              <thead>
                <tr class="first last">
                  <td class="text-center">订单编号</td>
                  <td class="text-center">类型</td>
                  <td class="text-center">金额</td>
                  <td class="text-center">时间</td>
                  <td class="text-center">备注</td>
                  </li>
              </thead>
              <tbody>
              <volist name="log" id="vo">
                <tr>
                  <td>{$vo.id}.&nbsp;&nbsp;{$vo.ordersn}</a></td>
                  <td>
                    <?php
                    
                    switch ($vo['type']){
                      case 1:
                          echo "账户充值";
                          break;
                      case 2:
                          echo "支付VIP会员费";
                          break;
                      case 3:
                          echo "付费观看";
                          break;
                    }
                    ?>
                  </td>
                  <td>{$vo.price}</td>
                  <td>{$vo.createtime|date="Y-m-d H:i:s",###}</td>
                  <td>{$vo.msg}</td>
                </tr>
              </volist>
              </tbody>
            </table>
          </if>
        </div>
        <div class="pages">
          {$Page}
        </div>
      </div>
    </div>
  </div>
</div>

<template file="Content/footer.php"/>