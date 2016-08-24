<div class="row">

  <div class="col-sm-12">
    <div class="widget-body">
      <Admintemplate file="common/Nav.php"/>
      <div class="tab-content padding-10">
        <div id="tab1" class="tab-pane fade active in">
          <form method='post' class="form-horizontal"  action="{:url('admin/Config/index')}">
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">站点名称:</label>
                <div class="col-md-6">
                  <input type="text" class="form-control"  name="sitename" value="{$Site.sitename}" size="40">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">网站访问地址:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="siteurl" value="{$Site.siteurl}" size="40"></div>
                <div class="col-md-4"> <span class="gray"> 请以“/”结尾</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">附件访问地址:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="sitefileurl" value="{$Site.sitefileurl}" size="40"></div>
                <div class="col-md-4">  <span class="gray"> 非上传目录设置</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">联系邮箱:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="siteemail" value="{$Site.siteemail}" size="40"> </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">网站关键字:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="sitekeywords" value="{$Site.sitekeywords}" size="40"> </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">网站简介:</label>
                <div class="col-md-6"><textarea name="siteinfo" class="form-control">{$Site.siteinfo}</textarea> </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">后台指定域名访问:</label>
                <div class="col-md-6"><select class="form-control" name="domainaccess" id="domainaccess" >
                    <option value="1" <if condition="$Site['domainaccess'] eq '1' "> selected</if>>开启指定域名访问</option>
                    <option value="0" <if condition="$Site['domainaccess'] eq '0' "> selected</if>>关闭指定域名访问</option>
                  </select>
                </div>
                <div class="col-md-4">  <span class="gray"> （该功能需要配合“域名绑定”模块使用，需要在域名绑定模块中添加域名！）</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">是否生成首页:</label>
                <div class="col-md-6"><select class="form-control" name="generate" id="generate" onChange="generates(this.value);">
                    <option value="1" <if condition="$Site['generate'] eq '1' "> selected</if>>生成静态</option>
                    <option value="0" <if condition="$Site['generate'] eq '0' "> selected</if>>不生成静态</option>
                  </select></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">首页URL规则:</label>
                <div class="col-md-6">
                  <div style='<if condition=" $Site['generate'] eq 0 "> display:none</if>' id="index_ruleid_1">
                      <?php echo \libs\util\Form::select($IndexURL[1], $Site['index_urlruleid'], 'class="form-control" name="index_urlruleid" ' . ($Site['generate'] == 0 ? "disabled" : "") . ' id="index_urlruleid"'); ?>
                  </div>
                  <div style='<if condition=" $Site['generate'] eq 1 '> display:none</if>" id="index_ruleid_0"><?php echo \libs\util\Form::select($IndexURL[0], $Site['index_urlruleid'], 'class="form-control"name="index_urlruleid" ' . ($Site['generate'] == 1 ? "disabled" : "") . ' id="index_urlruleid"'); ?> <span class="gray"> 注意：该URL规则只有当首页模板中标签有开启分页才会生效。</span></div>
                </div>
                <div class="col-md-4">
                  <span class="gray"> 注意：该URL规则只有当首页模板中标签有开启分页才会生效。</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">首页模板:</label>
                <div class="col-md-6"><select class="form-control" name="indextp" id="indextp">
                    <volist name="indextp" id="vo">
                      <option value="{$vo}" <if condition="$Site['indextp'] eq $vo"> selected</if>>{$vo}</option>
                    </volist>
                  </select></div>
                <div class="col-md-4"> 
                  <span class="gray"> 新增模板以index_x<?php echo config("TMPL_TEMPLATE_SUFFIX") ?>形式</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">TagURL规则:</label>
                <div class="col-md-6"><?php echo \libs\util\Form::select($TagURL, $Site['tagurl'], 'class="form-control" name="tagurl" id="tagurl"', 'TagURL规则选择'); ?></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">验证码类型:</label>
                <div class="col-md-6"><select class="form-control" name="checkcode_type">
                    <option value="0" <if condition="$Site['checkcode_type'] eq '0' "> selected</if>>数字字母混合</option>
                    <option value="1" <if condition="$Site['checkcode_type'] eq '1' "> selected</if>>纯数字</option>
                    <option value="2" <if condition="$Site['checkcode_type'] eq '2' "> selected</if>>纯字母</option>
                  </select></div>
              </div>
            </fieldset>
            <div class="form-actions">
              <div class="row">
                <div class="col-md-12 text-center">
                  <button class="btn btn-primary" type="submit">
                    <i class="fa fa-save"></i>
                    提交
                  </button>

                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>

</div>
