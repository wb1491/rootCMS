<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
  <div class="wrap J_check_wrap">
    <Admintemplate file="Common/Nav"/>
    <div class="h_a">课型信息</div>
    <form class="form-horizontal" action="{:url('Stuinfo/savedata')}" method="post">
      <if condition="!empty($info)">
        <input type="hidden" name="id" value="{$info.id}"/>
      </if>
      <div class="table_full">
        <table width="100%">
          <tr>
            <th width="100">姓名</th>
            <td><input type="text" name="name" class="form-control" placeholder="姓名" 
                       required maxlength="20" minlength="2" value="{$info.name}"/>
            </td>
          </tr>
          <tr>
            <th>性别</th>
            <td><label>
                <input type="radio" name="sex" value="0"<if condition="$info.sex eq 0 || empty($info)"> checked</if>>男
              </label>
              <label>
                <input type="radio" name="sex" value="1"<if condition="$info.sex eq 1 "> checked</if>>女
              </label>
            </td>
          </tr>
          <tr>
            <th>联系电话</th>
            <td>
              <input type="phone" class="form-control" name="phone" placeholder="联系电话"
                     value="{$info.phone}"/>
            </td>
          </tr>
          <tr>
            <th>手机<font color="red">*</font></th>
            <td>
              <input type="mobile" name="mobile" class="form-control" placeholder="手机号码" 
                     required maxlength="11" minlength="11" value="{$info.mobile}"/>
            </td>
          <tr>
            <th>Email</th>
            <td><input type="email" name="email" class="form-control" placeholder="Email" value="{$info.email}"/></td>
          </tr>
          <tr>
            <th>报名人数</th>
            <td>
              <select class="form-control" name="number" placeholder="报名人数">
                <option value="1"<if condition="empty($info) || $info.number eq 1"> selected="selected"</if>>1人</option>
                <option value="2"<if condition="$info.number eq 2"> selected="selected"</if>>2人</option>
                <option value="3"<if condition="$info.number eq 3"> selected="selected"</if>>3人</option>
                <option value="4"<if condition="$info.number eq 4"> selected="selected"</if>>4人以上</option>
              </select>
            </td>
          </tr>
          <tr>
            <th>课型选择<font color="red">*</font></th>
            <td>
              <select class="form-control"  name="cateid" required>
                <option value="">选择课型</option>
                <volist name="stucate" id="vo">
                  <option value="{$vo.id}" <if condition=" $vo['id'] eq $info['cateid']"> selected="selected"</if>>
                  {$vo.catename}
                  </option>
                </volist>
              </select>
            </td>
          </tr>
          <tr>
            <th>联系地址</th>
            <td>
              <input type="text" name="address" class="form-control" style="width:80%;" placeholder="联系地址" value="{$info.address}">
            </td>
          </tr>
          <tr>
            <th>备注</th>
            <td>
              <textarea class="form-control" name="bz" rows="3" style="width:80%;height:120px;" placeholder="填写备注信息">{$info.bz|str_replace="<br/>","\\n",###}</textarea>
            </td>
          </tr>
          <tr>
            <th>状态</th>
            <td>
              <select class="form-control"  name="status" required>
                <option value="">选择状态</option>
                <volist name="status" id="vo">
                  <option value="{$vo.id}" <if condition=" $vo['id'] eq $info['status']"> selected="selected"</if>>
                  {$vo.name}
                  </option>
                </volist>
              </select>
            </td>
          </tr>
        </table>
        <div class="btn_wrap">
          <div class="btn_wrap_pd">             
            <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
          </div>
        </div>
    </form>
  </div>

  <script src="{$config_siteurl}statics/js/common.js?v"></script>
</body>
</html>