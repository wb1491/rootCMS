<div class="row">
  <div class="col-sm-12">
    <div class="widget-body">
      <Admintemplate file="common/Nav.php"/>
        <div class="tab-content padding-10">
          <div id="tab1" class="tab-pane fade active in">
            <form method='post'  class="form-horizontal"  action="{:url('admin/Config/extendsave')}">
              <input type="hidden" name="action" value="add"/>
              <header>添加扩展配置项</header>
              <fieldset>
                    <div class="form-group">
                      <label class="col-md-2 control-label">键名:</label>
                      <div class="col-md-6"><input type="text" class="form-control" name="fieldname" value=""></div>
                      <div class="col-md-4"><span class="gray"> 注意：只允许英文、数组、下划线</span></div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label">名称:</label>
                      <div class="col-md-6"><input type="text" class="form-control" name="setting[title]" value=""></div>
                      <div class="col-md-4"><span class="gray"></span></div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label">类型:</label>
                      <div class="col-md-6"><select name="type" class="form-control" onChange="extend_type(this.value)">
                              <option value="input" >单行文本框</option>
                              <option value="select" >下拉框</option>
                              <option value="textarea" >多行文本框</option>
                              <option value="radio" >单选框</option>
                              <option value="password" >密码输入框</option>
                          </select></div>
                      <div class="col-md-4"><span class="gray"></span></div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label">提示:</label>
                      <div class="col-md-6"><input type="text" class="form-control length_4" name="setting[tips]" value=""></div>
                      <div class="col-md-4"><span class="gray"></span></div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label">样式:</label>
                      <div class="col-md-6"><input type="text" class="form-control length_4" name="setting[style]" value=""></div>
                      <div class="col-md-4"><span class="gray"></span></div>
                    </div>
                    <div class="form-group" style="display:none">
                      <label class="col-md-2 control-label">选项:</label>
                      <div class="col-md-6"><textarea name="setting[option]" disabled="true" style="width:380px; height:150px;">选项名称1|选项值1</textarea></div>
                      <div class="col-md-4"><span class="gray"> 注意：每行一个选项</span></div>
                    </div>
                </fieldset>
            
            <div class="form-actions">
              <div class="text-center">
                  <button class="btn btn-primary" type="submit">
                      <i class="fa fa-plus"></i>
                      添加</button></div>
            </div>
          </form>
          <form method='post'   id="myform" class="form-horizontal"  action="{:url('admin/Config/extendsave')}">
            <header>扩展配置 ，用法：模板调用标签：<literal>{:cache('Config</literal>.键名')}，PHP代码中调用：<literal>cache('Config</literal>.键名');</header>
            <fieldset>
                  <volist name="extendList" id="vo">
                  <php>$setting = unserialize($vo['setting']);</php>
                  <div class="form-group">
                    <label class="col-md-2 control-label">
                      {$setting.title} <a href="{:url('Config/extend',array('fid'=>$vo['fid'],'action'=>'delete'))}" class="J_ajax_del" title="删除该项配置" style="color:#F00">X</a><span class="gray"><br/>键名：{$vo.fieldname}</span>
                    </label>
                    <div class="col-md-6">
                      <switch name="vo.type">
                        <case value="input">
                            <input type="text" class="form-control" name="{$vo.fieldname}" value="{$Site[$vo['fieldname']]}">
                        </case>
                        <case value="select">
                            <select name="{$vo.fieldname}" class="form-control">
                                <volist name="setting['option']" id="rs">
                                    <option value="{$rs.value}" <if condition=" $Site[$vo['fieldname']] == $rs['value'] ">selected</if>>{$rs.title}</option>
                                </volist>
                            </select>
                        </case>
                        <case value="textarea">
                            <textarea name="{$vo.fieldname}" class="form-control">{$Site[$vo['fieldname']]}</textarea>
                        </case>
                        <case value="radio">
                            <volist name="setting['option']" id="rs">
                                <input name="{$vo.fieldname}" class="form-control" value="{$rs.value}" type="radio"  <if condition=" $Site[$vo['fieldname']] == $rs['value'] ">checked</if>> {$rs.title}
                            </volist>
                        </case>
                        <case value="password">
                            <input type="password" class="form-control" name="{$vo.fieldname}" value="{$Site[$vo['fieldname']]}">
                        </case>
                      </switch>
                    </div>
                    <div class="col-md-4"><span class="gray"> {$setting.tips}</span></div>
                  </div>
                </volist>
                <div class="form-actions">
                    <div class="text-center">
                        <button class="btn btn-primary" type="submit">
                        <i class="fa fa-save"></i>
                        提交</button>
                    </div>
                </div>
                </fieldset>
              </form>
            </div>
          </div>
        </div>

      </div>

    </div>
    <script>
      function extend_type(type) {
          if (type == 'radio' || type == 'select') {
              $('.setting_radio').show();
              $('.setting_radio textarea').attr('disabled', false);
          } else {
              $('.setting_radio').hide();
              $('.setting_radio textarea').attr('disabled', true);
          }
      }
    </script>
</div>