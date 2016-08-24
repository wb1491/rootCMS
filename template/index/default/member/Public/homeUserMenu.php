<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<div class="row">
    <div class="col-md-12 col-sm-6 col-xs-6 container-fluid">
        <div class="row user_image">
            <div class="text-center margin_t15">{$username}</div>
            <div class="text-center margin-bottom5">
              <img src="{:url('api/Avatar/index',array('uid'=>$uid,'size'=>180))}" onerror="this.src='{$model_extresdir}images/noavatar.jpg'" id="menu-avatar"/>
              <div class="row">
              <?php
              $usinfo = service("Passport")->getInfo();
              ?>
                <div class="col-md-6 paddingfixright">{:getVipRank($usinfo['vip'],"typename",true)}</div>
                <div class="col-md-6 paddingfixleft">余额:<font color="red">{$usinfo.amount}</font>元</div>
              </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-6 col-xs-6 sidebar-nav paddingfix">
        <a class='list-group-item <if condition="CONTROLLER_NAME eq 'User'">active</if>' href="{:url('User/profile')}"> <i class="fa fa-user"></i> 用户中心 </a>
        <a class='list-group-item  <if condition="CONTROLLER_NAME eq 'Viewed'">active</if>' href="{:url('Viewed/index')}" target="_self"> <i class="fa fa-share-square"></i> 观看记录 </a>
        <a class='list-group-item  <if condition="CONTROLLER_NAME eq 'Amountlog'">active</if>'href="{:url('Amountlog/index')}"> <i class="fa fa-bookmark"></i> 资金日志 </a>
        <a class='list-group-item  <if condition="CONTROLLER_NAME eq 'Charge'">active</if>' href="{:url('Charge/index')}"> <i class="fa fa-cogs"></i> 账户充值 </a>
        {:tag('view_member_menu',$User)}
    </div>
</div>