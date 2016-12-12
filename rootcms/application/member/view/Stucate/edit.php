<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="h_a">课型信息</div>
  <form class="J_ajaxForm" action="{:url('edit')}" method="post" id="myform">
  <div class="table_full">
      <table width="100%">
        <tr>
          <th width="100">课型名称</th>
          <td><input type="text" name="catename" value="{$data.catename}" class="input" id="catename"style="width: 48%;"></input></td>
        </tr>
        <tr>
          <th>课型描述</th>
          <td><textarea name="description" rows="2" cols="20" id="description" class="inputtext"
                        style="height:100px;width:500px;">{$data.description}</textarea></td>
        </tr>
        <tr>
          <th>是否启用</th>
          <td>
          <input type="radio" name="status" value="0"  <if condition="$data['status'] eq 0">checked</if>>启用<label>  &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio" name="status" value="1" <if condition="$data['status'] eq 1">checked</if>>禁止</label></td>
        </tr>
      </table>
      <input type="hidden" name="id" value="{$data.id}" />
    
  </div>
  <div class="">
      <div class="btn_wrap_pd">             
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
      </div>
    </div>
    </form>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
</body>
</html>