<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="h_a">温馨提示</div>
  <div class="prompt_text">
    <p>请谨慎操作！</p>
  </div>
  <div class="h_a">批量栏目设置</div>
  <form action="{:url('Addons/BatchColumn/index',array('isadmin'=>1))}" method="post" name="myform" class="J_ajaxForm">
  <div class="table_full">
    <table width="100%" cellspacing="0">
        <thead>
          <tr>
            <th align="center" width="386">选择栏目范围</th>
            <th align="center">选择操作内容</th>
          </tr>
        </thead>
        <tbody  height="200" class="nHover td-line">
          <tr>
            <th align="center" rowspan="7"> <select name='catids[]' id='catids'  multiple="multiple"  style="height:99%; width:99%" title="按住“Ctrl”或“Shift”键可以多选，按住“Ctrl”可取消选择">
                <option value='0' selected>不限栏目</option>
                  {$string}
              </select>
              按住“Ctrl”或“Shift”键可以多选，按住“Ctrl”可取消选择</th>
          </tr>
          <tr>
            <th>
            <table width="100%" class="table_form">
                <tr>
                  <th width="130"><label><input name="act[]" class="J_check" type="checkbox" value="navigation"> 是否在导航显示</label></th>
                  <td><ul class="switch_list cc ">
                  <li>
                    <label>
                      <input type='radio' name='info[ismenu]' value='1' checked>
                      <span>在导航显示</span></label>
                  </li>
                  <li>
                    <label>
                      <input type='radio' name='info[ismenu]' value='0'  >
                      <span>不在导航显示</span></label>
                  </li>
                </ul></td>
                </tr>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="cattype"> 栏目类型转换</label></th>
                  <td><select name="cattype_id">
                  <option value="1" >转为单页栏目</option>
                  <option value="2" >转为外链栏目</option>
                  <option value="3" >转为普通栏目</option>
                </select></td>
                </tr>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="adminedit"> 后台增加/编辑信息</label></th>
                  <td><label><input type="checkbox"  value="1" checked  name="setting[generatehtml]">
                生成内容页；</label> 生成列表：
                <select name="setting[generatelish]">
                  <option value="0" selected>不生成</option>
                  <option value="1" >生成当前栏目</option>
                  <option value="2" >生成首页</option>
                  <option value="3" >生成父栏目</option>
                  <option value="4" >生成当前栏目与父栏目</option>
                  <option value="5" >生成父栏目与首页</option>
                  <option value="6" >生成当前栏目、父栏目与首页</option>
                </select></td>
                </tr>
                <if condition="isModuleInstall('Member')">
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="contribute"> 前台投稿审核</label></th>
                  <td><ul class="switch_list cc ">
                  <li>
                    <label>
                      <input type='radio' name="setting[member_check]" checked value='1'>
                      <span>需要审核</span></label>
                  </li>
                  <li>
                    <label>
                      <input type='radio' name="setting[member_check]"  value='0'>
                      <span>无需审核</span></label>
                  </li>
                </ul></td>
                </tr>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="contributeconfig"> 管理投稿</label></th>
                  <td><select name="setting[member_admin]">
                  <option value="0" >不能管理信息</option>
                <option value="1" selected>可管理未审核信息</option>
                <option value="2" >只可编辑未审核信息</option>
                <option value="3" >只可删除未审核信息</option>
                <option value="4" >可管理所有信息</option>
                <option value="5" >只可编辑所有信息</option>
                <option value="6" >只可删除所有信息</option>
                </select>
                <input type="checkbox"  value="1" checked name="setting[member_editcheck]" >
                编辑信息需要审核</td>
                </tr>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="contributelist"> 投稿生成列表</label></th>
                  <td><select name="setting[member_generatelish]">
                  <option value="0" selected>不生成</option>
                  <option value="1" >生成当前栏目</option>
                  <option value="2" >生成首页</option>
                  <option value="3" >生成父栏目</option>
                  <option value="4" >生成当前栏目与父栏目</option>
                  <option value="5" >生成父栏目与首页</option>
                  <option value="6" >生成当前栏目、父栏目与首页</option>
                </select></td>
                </tr>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="contributereward"> 投稿增加点数</label></th>
                  <td><input type="text" class="input" value="0" name="setting[member_addpoint]">
                <span class="gray"><b class="red  ">点数</b> (不增加请设为0,扣点请设为负数)</span></td>
                </tr>
                </if>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="indextemplate"> 栏目首页模板</label></th>
                  <td><select name="setting[category_template]" id="category_template">
                  <option value="category<?php echo config("TMPL_TEMPLATE_SUFFIX")?>" selected>默认栏目首页：category<?php echo config("TMPL_TEMPLATE_SUFFIX")?></option>
                  <volist name="tp_category" id="vo">
                    <option value="{$vo}">{$vo}</option>
                  </volist>
                </select></td>
                </tr>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="liststemplate"> 栏目列表模板</label></th>
                  <td><select name="setting[list_template]" id="list_template">
                  <option value="list<?php echo config("TMPL_TEMPLATE_SUFFIX")?>" selected>默认列表页：list<?php echo config("TMPL_TEMPLATE_SUFFIX")?></option>
                  <volist name="tp_list" id="vo">
                    <option value="{$vo}">{$vo}</option>
                  </volist>
                </select></td>
                </tr>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="showtemplate"> 栏目内容页模板</label></th>
                  <td><select name="setting[show_template]" id="show_template">
                  <option value="show<?php echo config("TMPL_TEMPLATE_SUFFIX")?>" selected>默认内容页：show<?php echo config("TMPL_TEMPLATE_SUFFIX")?></option>
                  <volist name="tp_show" id="vo">
                    <option value="{$vo}">{$vo}</option>
                  </volist>
                </select></td>
                </tr>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="listgenerate"> 栏目生成Html</label></th>
                  <td><ul class="switch_list cc ">
                  <li>
                    <label>
                      <input type="radio"  value="1" name="setting[ishtml]" onClick="$('#category_php_ruleid').css('display','none');$('#category_html_ruleid').css('display','');">
                      <span>栏目生成静态</span></label>
                  </li>
                  <li>
                    <label>
                      <input type="radio" checked="" value="0" name="setting[ishtml]" onClick="$('#category_php_ruleid').css('display','');$('#category_html_ruleid').css('display','none');">
                      <span>栏目不生成静态</span></label>
                  </li>
                </ul></td>
                </tr>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="showgenerate">内容页生成Html</label></th>
                  <td><ul class="switch_list cc ">
                  <li>
                    <label>
                      <input type="radio"  value="1" name="setting[content_ishtml]" onClick="$('#show_php_ruleid').css('display','none');$('#show_html_ruleid').css('display','')">
                      <span>内容页生成静态</span></label>
                  </li>
                  <li>
                    <label>
                      <input type="radio" checked="" value="0" name="setting[content_ishtml]" onClick="$('#show_php_ruleid').css('display','');$('#show_html_ruleid').css('display','none')">
                      <span>内容页不生成静态</span></label>
                  </li>
                </ul></td>
                </tr>
                <tr>
                  <th></th>
                  <td><div style="display:" id="category_php_ruleid"> {$category_php_ruleid} </div>
                <div style="display:none" id="category_html_ruleid"> {$category_html_ruleid} </div></td>
                </tr>
                <tr>
                  <th></th>
                  <td><div style="display:" id="show_php_ruleid"> {$show_php_ruleid} </div>
                <div style="display:none" id="show_html_ruleid"> {$show_html_ruleid} </div></td>
                </tr>
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="adminrole">后台角色权限</label></th>
                  <td><div class="user_group J_check_wrap">
                  <dl>
                  <volist name="Role_group" id="vo">
                    <dt>
                      <label><input type="checkbox" data-direction="y" data-checklist="J_check_priv_roleid{$vo.id}" class="checkbox J_check_all" <if condition=" $vo['id'] eq 1 "> disabled</if> />{$vo.name}</label>
                    </dt>
                    <dd>
                      <label><input  class="J_check" type="checkbox" data-yid="J_check_priv_roleid{$vo.id}"  name="priv_roleid[]" <if condition=" $vo['id'] eq 1 "> disabled</if>  value="init,{$vo.id}" ><span>查看</span></label>
                      <label><input class="J_check" type="checkbox" data-yid="J_check_priv_roleid{$vo.id}" name="priv_roleid[]" <if condition=" $vo['id'] eq 1 "> disabled</if>  value="add,{$vo.id}" ><span>添加</span></label>
                      <label><input class="J_check" type="checkbox" data-yid="J_check_priv_roleid{$vo.id}" name="priv_roleid[]" <if condition=" $vo['id'] eq 1 "> disabled</if>  value="edit,{$vo.id}" ><span>修改</span></label>
                      <label><input class="J_check" type="checkbox" data-yid="J_check_priv_roleid{$vo.id}" name="priv_roleid[]" <if condition=" $vo['id'] eq 1 "> disabled</if>  value="delete,{$vo.id}" ><span>删除</span></label>
                      <label><input class="J_check" type="checkbox" data-yid="J_check_priv_roleid{$vo.id}" name="priv_roleid[]" <if condition=" $vo['id'] eq 1 "> disabled</if>  value="listorder,{$vo.id}" ><span>排序</span></label>
                      <label><input class="J_check" type="checkbox" data-yid="J_check_priv_roleid{$vo.id}" name="priv_roleid[]" <if condition=" $vo['id'] eq 1 "> disabled</if>  value="push,{$vo.id}" ><span>推送</span></label>
                      <label><input class="J_check" type="checkbox" data-yid="J_check_priv_roleid{$vo.id}" name="priv_roleid[]" <if condition=" $vo['id'] eq 1 "> disabled</if>  value="move,{$vo.id}" ><span>移动</span></label>
                    </dd>
                   </volist>
                  </dl>
                </div></td>
                </tr>
                <if condition="isModuleInstall('Member')">
                <tr>
                  <th><label><input name="act[]" class="J_check" type="checkbox" value="memberrole">前台用户组权限</label></th>
                  <td><div class="user_group J_check_wrap">
                  <dl>
                  <volist name="Member_group" id="vo">
                    <dt>
                      <label><input type="checkbox" data-direction="y" data-checklist="J_check_priv_groupid{$vo.groupid}" class="checkbox J_check_all" <if condition=" $vo['id'] eq 1 "> disabled</if> />{$vo.name}</label>
                    </dt>
                    <dd>
                      <label><input  class="J_check" type="checkbox" data-yid="J_check_priv_groupid{$vo.groupid}"  name="priv_groupid[]" <if condition=" $vo['groupid'] eq 8 "> disabled</if>  value="add,{$vo.groupid}" ><span>允许投稿</span></label>
                    </dd>
                   </volist>
                  </dl>
                </div></td>
                </tr>
                </if>
              </table>
            </th>
          </tr>
        </tbody>
    </table>
  </div>
  <div class="btn_wrap">
      <div class="btn_wrap_pd">
        <input name="catid" type="hidden" value="10">
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">批量设置</button>
      </div>
   </div>
   </form>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script> 
</body>
</html>