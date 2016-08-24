<php>
  $SEO['title'] = "成为VIP -- ".$Config['sitename'];
</php>
<template file='Content/header_file.php'/>
<template file="Content/header_nav.php"/>

<if condition="$iswx">

<script type="text/javascript">
//调用微信JS api 支付
function jsApiCall()
{
    if(!isWeixin()) {
        alert("请在微信端进行支付");
        return false;
    }
    
    WeixinJSBridge.invoke(
        'getBrandWCPayRequest',
        <?php echo $jsApiParameters; ?>,
        function(res){
            WeixinJSBridge.log(res.err_msg);
            //alert(res.err_code+res.err_desc+res.err_msg);
            if(res.err_msg == 'get_brand_wcpay_request:cancel') {
                alert("您已取消了此次支付");
                return;
            } else if(res.err_msg == 'get_brand_wcpay_request:fail') {
                alert("支付失败，请重新尝试");
                return;
            } else if(res.err_msg == 'get_brand_wcpay_request:ok') {
                alert("支付成功！");
                location.href="{:url('doupdate','ordersn='.$ordersn.'&vipid='.$vipid)}";
            } else {
                alert("未知错误"+res.error_msg);
                return;
            
            }
        }
    );
}
 
function isWeixin(){
   return /MicroMessenger/.test(navigator.userAgent);
}
function callpay()
{
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
            document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
        }
    }else{
        jsApiCall();
    }
}
</script>
<div class="container userprofile">
  <div class="row indexblock">
    <div class="col-md-12 paddingfix">
      <ul class="nav nav-tabs">
        <li <if condition=" !$type ">class="active"</if>>
        <a target="_slef" href="{:url('Charge/index')}">确认支付订单</a>
        </li>
      </ul>
      <div class="tab-content border">
        <div class="row" id="favoritesList">
            <div class="featured-box featured-box-secundary default info-content">
              <div class="box-content clearfix">
                <br/>
                <font color="#9ACD32">
                  <b>您选择的套餐为:
                  <span style='color:#f00;font-size:16'>{:getVipRank($vipid,'typename')}</span>,应付：
                  <span style="color:#f00;font-size:50px">{$price}</span>元</b>
                </font>
                <br/>
                <br/>
                <div align="center">
                  <button class="btn-pay" type="button" onclick="callpay()" >立即支付</button>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<else />
<?php $_SESSION['i'] = 30;?>
<script type="text/javascript" charset="UTF-8" src="{$Config_siteurl}statics/addons/wxpay/qrcode.js"></script>
<div class="container userprofile">
  <div class="row indexblock">
    <div class="col-md-12 paddingfix">
      <ul class="nav nav-tabs">
        <li <if condition=" !$type ">class="active"</if>>
        <a target="_slef" href="{:url('')}">请扫描二维码</a>
        </li>
      </ul>
      <div class="tab-content border">
        <div class="row" id="favoritesList">
            <div class="featured-box featured-box-secundary default info-content">
              <div class="box-content clearfix" style="text-align: center;">
                <br/>
                <div>订单编号：{$ordersn}</div>
                <font color="#9ACD32">
                  <b>应付:
                  <span style="color:#f00;font-size:50px">{$price}</span>元</b>
                </font>
                <div align="center" id="qrcode"></div>
                <br/>
                <div id="result"></div>
                <br/>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    function Check()
    {
        var out_trade_no = "<?php echo $ordersn; ?>"; 
        var pay_url = '{:url("orderQuery","ordersn=".$ordersn)}';
        //ThinkAjax.send('__URL__/orderQuery','ajax=1&out_trade_no='+out_trade_no,'','result');
        // 获取订单json数据
        $.getJSON(pay_url, function(json){
            //return;
            if(json.status != 'ok') {
                //alert(json.msg);
                //clearInterval(flg);
                //location.href = "{:url('index')}";
                $("#result").html(json.msg);
                if(json.url.length>0){
                    location.href = json.url;
                }
            }else{
                //alert("支付成功");
                $("#result").html("订单支付成功");
                location.href = "{:url('doupdate','ordersn='.$ordersn.'&vipid='.$vipid)}";
            }
        });
    }
    setInterval(Check,3000); 
    if(<?php echo $unifiedOrderResult["code_url"] != NULL; ?>)
    {
        var url = "<?php echo $code_url;?>";
        //参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
        var qr = qrcode(10, 'H');
        qr.addData(url);
        qr.make();
        var wording=document.createElement('p');
        wording.innerHTML = "微信二维码支付";
        var code=document.createElement('DIV');
        code.innerHTML = qr.createImgTag();
        var element=document.getElementById("qrcode");
        element.appendChild(wording);
        element.appendChild(code);
    }
</script>
</if>
<template file="Content/footer.php"/>