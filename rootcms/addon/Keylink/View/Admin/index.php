<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="h_a">功能说明</div>
  <div class="prompt_text">
    <ol>
      <li>关键词内联插件是一款对内容输出前进行相应的关键字匹配替换。</li>
      <li>合理设置关键词数量，能有效地为服务器减轻负担。</li>
      <li>可以在编辑器中加个开关，下标 enablekeylink  = 1 关闭关键词替换</li>
    </ol>
  </div>
  <form class="J_ajaxForm" action="{:url('delete','isadmin=1')}" method="post">
    <div class="table_list">
      <table width="100%">
        <thead>
          <tr>
            <td width="50"><label>
                <input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x">
                选择</label></td>
            <td>关键词</td>
            <td>地址</td>
            <td width="50" align="center">替换次数</td>
            <td width="100" align="center">操作</td>
          </tr>
        </thead>
        <volist name="data" id="vo">
          <tr>
            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="keylinkid[]" value="{$vo.keylinkid}"></td>
            <td>{$vo.word}</td>
            <td><a href="{$vo.url}" target="_blank">{$vo.url}</a></td>
            <td align="center">{$vo.frequency}</td>
            <td align="center"><a href="{:url('edit',array('isadmin'=>1,'keylinkid'=>$vo['keylinkid']))}" class="mr5">[编辑]</a> <a class="J_ajax_del" href="{:url('delete',array('isadmin'=>1,'keylinkid'=>$vo['keylinkid']))}">[删除]</a>
          </tr>
        </volist>
      </table>
      <div class="p10">
        <div class="pages"> {$Page} </div>
      </div>
      <div class="btn_wrap">
        <div class="btn_wrap_pd">
          <label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>
          <button class="btn J_ajax_submit_btn" type="submit">删除</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
</body>
</html>