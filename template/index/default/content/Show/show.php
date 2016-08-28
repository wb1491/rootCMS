<template file='Content/header_file.php'/>
<!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">收费确认</h4>
      </div>
      <div class="modal-body">
        此内容需要额外的费用才能查看，本次收费{$charge}元，将从账户余额中扣除！
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="mycharge" data-dismiss="modal"> 确 认 </button>
      </div>
    </div>
  </div>
</div>

<div class="conbox Mfont">
  <h4 class="con-cru">
    <a href="{$config_siteurl}">{$Config.sitename}</a> &gt; 
    <navigate catid="$catid" space=" &gt; " />
  </h4>
  <div class="content">
    <h2 class="con-tit">{$title}</h2>
    <p class="con-info">  <span id="hits">0</span>   {$updatetime}    </p>
    <h5 class="con-dis border Mbreak">{$description}</h5>
    <if condition='!empty($imgs)'>
    <ul class="photocon" id="Gallery">
      <volist name="imgs" id="vo">
      <li><a title="{$vo.alt}" href="{$vo.url}"><img src="{$vo.url}" alt="{$vo.alt}" /></a></li>
      </volist>
    </ul>
    <elseif condition="!empty($videourl)"/>
    <div id="a1" style="margin:auto 10px;"></div>
    <script type="text/javascript" src="{$Config_siteurl}statics/mgtv/ckplayer/ckplayer.js" charset="utf-8"></script>
    <script type="text/javascript">
        var vlinks = [ "{$videourl|base64_encode}" ];
        var vi = 0;
        var len = JSONLength(vlinks);
        var charged = false;

        function playvideo() {
            var flashvars = {
                f: getstr(vlinks[vi]),
                p: 1,
                i: '{$Config_siteurl}statics/mgtv/images/logo.png',
                e: 0,
                c: 0,
                loaded: 'loadedHandler'
            };

            var video = [getstr(vlinks[vi]) + '->video/mp4'];
            var realh = document.body.clientHeight;
            var wwidth = realh / 0.6;
            var wheight = realh - 30;
            CKobject.embed('{$Config_siteurl}statics/mgtv/ckplayer/ckplayer.swf', 'a1', 'ckplayer_a1', wwidth, wheight, false, flashvars, video);
        }
        function playerstop() {
            vi++;
            if (vi < len) {
                if (CKobject.getObjectById('ckplayer_a1').getType()) {
                    CKobject.getObjectById('a1').newAddress("{html5->" + getstr(vlinks[vi]) + "->video/mp4}");
                } else {
                    CKobject.getObjectById('ckplayer_a1').newAddress("{f->" + getstr(vlinks[vi]) + "}");
                }
            } else {
                alert('所有视频播放完毕~');
            }
        }

        function ckmarqueeadv() {
            return escape("<font color='#ffffff'>{$title}</font>");
        }

        $(function ($) {
            
            <if condition="$charge">
            $('#myModal').modal('show');
            
            $("#mycharge").on("click",function(){
                $.post('{:url("member/Charge/collection")}',{catid:{$catid},id:{$id}},function(result){
                    if(result.status == 1){
                        charged = true;
                        $("#myModal").modal('hide');
                    }
                    if(result.hasOwnProperty("msg") && result.msg.length > 0){
                        alert(result.msg);
                    }
                });
            });
    
            $('#myModal').on('hidden.bs.modal', function (e) {
                if(charged){
                    playvideo();
                }else{
                    window.history.go(-1);
                }
            });
            <else/>
            playvideo();
            </if>
        });

        function getstr(str) {
            var c1, c2, c3, c4;
            var base64DecodeChars = new Array(
                    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
                    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
                    -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63, 52, 53, 54, 55, 56, 57,
                    58, 59, 60, 61, -1, -1, -1, -1, -1, -1, -1, 0, 1, 2, 3, 4, 5, 6,
                    7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24,
                    25, -1, -1, -1, -1, -1, -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36,
                    37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1,
                    -1, -1
                    );
            var i = 0, len = str.length, string = '';
            while (i < len) {
                do {
                    c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff]
                } while (
                        i < len && c1 == -1
                        );
                if (c1 == -1)
                    break;

                do {
                    c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff]
                } while (
                        i < len && c2 == -1
                        );
                if (c2 == -1)
                    break;
                string += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));
                do {
                    c3 = str.charCodeAt(i++) & 0xff;
                    if (c3 == 61)
                        return string;
                    c3 = base64DecodeChars[c3]
                } while (
                        i < len && c3 == -1
                        );
                if (c3 == -1)
                    break;
                string += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));
                do {
                    c4 = str.charCodeAt(i++) & 0xff;
                    if (c4 == 61)
                        return string;
                    c4 = base64DecodeChars[c4]
                } while (
                        i < len && c4 == -1
                        );
                if (c4 == -1)
                    break;
                string += String.fromCharCode(((c3 & 0x03) << 6) | c4)
            }
            return string;
        }
        function JSONLength(obj) {
            var size = 0, key;
            for (key in obj) {
                if (obj.hasOwnProperty(key))
                    size++;
            }
            return size;
        };
    </script>
    </if>
    <div class="content Mbreak">
      <php>
        $tcontent = preg_replace("/font-family:[^;]+;/","",$content);
        $tcontent = preg_replace("/font:[^;]+;/","",$tcontent);
        echo $tcontent;
      </php>
    </div>
    <ul class="prenext">
      <li>    <previous target="1" msg="   " /></li>
      <li>   <next target="1" msg="   " /></li>
    </ul>
  </div>
</div>
<script type="text/javascript">
$(function (){
	//点击
	$.get("{$config_siteurl}api.php?m=Hits&catid={$catid}&id={$id}", function (data) {
	    $("#hits").html(data.views);
	}, "json");
});
</script>