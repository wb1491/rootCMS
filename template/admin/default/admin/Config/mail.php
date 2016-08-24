<div class="row">

  <div class="col-sm-12">
    <div class="widget-body">
      <Admintemplate file="common/Nav.php"/>
      <div class="tab-content padding-10">
        <div id="tab1" class="tab-pane fade active in">
          <form method='post' method='post' class="form-horizontal"  action="{:url('admin/Config/mail')}">
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">邮件发送模式</label>
                <div class="col-md-6">
                    <label class="checkbox-inline">
                      <input class="checkbox style-0" name="mail_type" checkbox="mail_type" value="1"  type="checkbox"  checked>
                      <span>SMTP 函数发送</span>
                    </label>
                </div>
              </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">邮件服务器</label>
                  <div class="col-md-6"><input class="form-control" type="text" class="input" name="mail_server" id="mail_server" size="30" value="{$Site.mail_server}"/></div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">邮件发送端口</label>
                  <div class="col-md-6"><input class="form-control" type="text" class="input" name="mail_port" id="mail_port" size="30" value="{$Site.mail_port}"/></div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">发件人地址</label>
                  <div class="col-md-6"><input class="form-control" type="text" class="input" name="mail_from" id="mail_from" size="30" value="{$Site.mail_from}"/></div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">发件人名称</label>
                  <div class="col-md-6"><input class="form-control" type="text" class="input" name="mail_fname" id="mail_fname" size="30" value="{$Site.mail_fname}"/></div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">密码验证</label>
                  <div class="col-md-6">
                    <label class="radio radio-inline">
                      <input class="radiobox" name="mail_auth" id="mail_auth" value="1" type="radio"  <if condition=" $Site['mail_auth'] == '1' ">checked</if>>
                      <span>开启</span>
                    </label>
                    <label class="radio radio-inline">
                      <input class="radiobox" name="mail_auth" id="mail_auth" value="0" type="radio" <if condition=" $Site['mail_auth'] == '0' ">checked</if>>
                      <span>关闭</span>
                    </label>
                  </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">验证用户名</label>
                <div class="col-md-6"><input class="form-control" type="text" class="input" name="mail_user" id="mail_user" size="30" value="{$Site.mail_user}"/></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">验证密码</label>
                <div class="col-md-6"><input class="form-control" type="password" class="input" name="mail_password" id="mail_password" size="30" value="{$Site.mail_password}"/></div>
              </div>
            </fieldset>
            <div class="form-actions">
              <div class="text-center">
                <button class="btn btn-primary" type="submit">
                <i class="fa fa-save"></i>
                提交
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>

</div>