<div class="row">

  <div class="col-sm-12">
    <div class="widget-body">
      <Admintemplate file="common/Nav.php"/>
      <div class="tab-content padding-10">
        <div id="tab1" class="tab-pane fade active in">
          <form method='post' class="form-horizontal"  action="{:url('admin/Config/additionsave')}">
            <header>云平台设置</header>
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">帐号:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="CLOUD_USERNAME" value="{$addition.CLOUD_USERNAME}" size="40">
                </div><div class="col-md-4"><span class="gray"> http://www.linuxxt.cn 会员帐号</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">密码:</label>
                <div class="col-md-6"><input type="password" class="form-control"  name="CLOUD_PASSWORD" value="{$addition.CLOUD_PASSWORD}" size="40">
                </div><div class="col-md-4"><span class="gray"> http://www.linuxxt.cn 会员密码</span></div>
              </div>
            </fieldset>
            <header>Cookie配置</header>
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">Cookie有效期:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="COOKIE_EXPIRE" value="{$addition.COOKIE_EXPIRE}" size="40">
                </div><div class="col-md-4"><span class="gray"> 单位秒</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Cookie有效域名:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="COOKIE_DOMAIN" value="{$addition.COOKIE_DOMAIN}" size="40">
                </div><div class="col-md-4"><span class="gray"> 例如：“.linuxxt.cn”表示这个域名下都可以访问</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Cookie路径:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="COOKIE_PATH" value="{$addition.COOKIE_PATH}" size="40">
                </div><div class="col-md-4"><span class="gray"> 一般是“/”</span></div>
              </div>
            </fieldset>
            <header>Session配置</header>
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">Session前缀:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="SESSION_PREFIX" value="{$addition.SESSION_PREFIX}" size="40">
                </div><div class="col-md-4"><span class="gray"> 一般为空即可</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Session域名:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="SESSION_OPTIONS[domain]" value="{$addition.SESSION_OPTIONS.domain}" size="40">
                </div><div class="col-md-4"><span class="gray"> 一般是“.linuxxt.cn”</span></div>
              </div>
            </fieldset>
            <header>错误设置</header>
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">显示错误信息:</label>
                <div class="col-md-6"><input name="SHOW_ERROR_MSG" type="radio" value="1" <if condition=" $addition['SHOW_ERROR_MSG'] ">checked</if>> 开启 <input name="SHOW_ERROR_MSG" type="radio" value="0" <if condition=" !$addition['SHOW_ERROR_MSG'] ">checked</if>> 关闭</div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">错误显示信息:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="ERROR_MESSAGE" value="{$addition.ERROR_MESSAGE}" size="40"></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">错误定向页面:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="ERROR_PAGE" value="{$addition.ERROR_PAGE}" size="40">
                </div><div class="col-md-4"><span class="gray"> 例如：http://www.linuxxt.cn/error.html</span></div>
              </div>
            </fieldset>
            <header>URL设置</header>
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">URL不区分大小写:</label>
                <div class="col-md-6"><input name="URL_CASE_INSENSITIVE" type="radio" value="1" <if condition=" $addition['URL_CASE_INSENSITIVE'] ">checked</if>> 开启 <input name="URL_CASE_INSENSITIVE" type="radio" value="0" <if condition=" !$addition['URL_CASE_INSENSITIVE'] ">checked</if>> 关闭</div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">URL访问模式:</label>
                <div class="col-md-6"><select class="form-control" name="URL_MODEL" id="URL_MODEL" >
                    <option value="0" <if condition="$addition['URL_MODEL'] eq '0' "> selected</if>>普通模式</option>
                    <option value="1" <if condition="$addition['URL_MODEL'] eq '1' "> selected</if>>PATHINFO 模式</option>
                    <option value="2" <if condition="$addition['URL_MODEL'] eq '2' "> selected</if>>REWRITE  模式</option>
                    <option value="3" <if condition="$addition['URL_MODEL'] eq '3' "> selected</if>>兼容模式</option>
                  </select> </div><div class="col-md-4"><span class="gray"> 除了普通模式外其他模式可能需要服务器伪静态支持，同时需要写相应伪静态规则！</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">PATHINFO模式参数分割线:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="URL_PATHINFO_DEPR" value="{$addition.URL_PATHINFO_DEPR}" size="40">
                </div><div class="col-md-4"><span class="gray"> 例如：“/”</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">URL伪静态后缀:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="URL_HTML_SUFFIX" value="{$addition.URL_HTML_SUFFIX}" size="40">
                </div><div class="col-md-4"><span class="gray"> 例如：“.html”</span></div>
              </div>
            </fieldset>
            <header>表单令牌</header>
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">使用说明:</label>
                <div class="col-md-6">开启前，需要在行为管理 view_filter 行为里添加 phpfile:TokenBuildBehavior 行为才能正常启用。</div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">是否开启令牌验证:</label>
                <div class="col-md-6"><input name="TOKEN_ON" type="radio" value="1" <if condition=" $addition['TOKEN_ON'] ">checked</if>> 开启 <input name="TOKEN_ON" type="radio" value="0" <if condition=" !$addition['TOKEN_ON'] ">checked</if>> 关闭</div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">表单隐藏字段名称:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="TOKEN_NAME" value="{$addition.TOKEN_NAME}" size="40">
                </div><div class="col-md-4"><span class="gray"> 令牌验证的表单隐藏字段名称！</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">令牌哈希验证规则:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="TOKEN_TYPE" value="{$addition.TOKEN_TYPE}" size="40">
                </div><div class="col-md-4"><span class="gray"> 令牌哈希验证规则 默认为MD5</span></div>
              </div>
            </fieldset>
            <header>分页配置</header>
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">默认分页数:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="PAGE_LISTROWS" value="{$addition.PAGE_LISTROWS}" size="40">
                </div><div class="col-md-4"><span class="gray"> 默认20！</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">分页变量:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="VAR_PAGE" value="{$addition.VAR_PAGE}" size="40">
                </div><div class="col-md-4"><span class="gray"> 默认：page，建议不修改</span></div>
              </div>
            </fieldset>
            <header>杂项配置</header>
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">默认分页模板:</label>
                <div class="col-md-6">
                  <textarea class="form-control" name="PAGE_TEMPLATE">{$addition.PAGE_TEMPLATE}</textarea>
                  <br/>
                </div><div class="col-md-4"><span class="gray"> 当没有设置分页模板时，默认使用该项设置</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">默认模块:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="DEFAULT_MODULE" value="{$addition.DEFAULT_MODULE}" size="40">
                </div><div class="col-md-4"><span class="gray"> 默认：Content，建议不修改，填写时注意大小写</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">默认时区:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="DEFAULT_TIMEZONE" value="{$addition.DEFAULT_TIMEZONE}" size="40"></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">AJAX 数据返回格式:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="DEFAULT_AJAX_RETURN" value="{$addition.DEFAULT_AJAX_RETURN}" size="40">
                </div><div class="col-md-4"><span class="gray"> 默认AJAX 数据返回格式,可选JSON XML ...</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">默认参数过滤方法:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="DEFAULT_FILTER" value="{$addition.DEFAULT_FILTER}" size="40">
                </div><div class="col-md-4"><span class="gray"> 默认参数过滤方法 用于 $this->_get('变量名');$this->_post('变量名')...</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">默认语言:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="DEFAULT_LANG" value="{$addition.DEFAULT_LANG}" size="40">
                </div><div class="col-md-4"><span class="gray"> 默认语言</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">数据缓存类型:</label>
                <div class="col-md-6"><select class="form-control" name="DATA_CACHE_TYPE" id="DATA_CACHE_TYPE" >
                    <option value="File" <if condition="$addition['DATA_CACHE_TYPE'] eq 'File' "> selected</if>>File</option>
                    <option value="Memcache" <if condition="$addition['DATA_CACHE_TYPE'] eq 'Memcache' "> selected</if>>Memcache</option>
                    <option value="Redis" <if condition="$addition['DATA_CACHE_TYPE'] eq 'Redis' "> selected</if>>Redis</option>
                    <option value="Xcache" <if condition="$addition['DATA_CACHE_TYPE'] eq 'Xcache' "> selected</if>>Xcache</option>
                  </select>
                  </div><div class="col-md-4"><span class="gray"> 数据缓存类型,支持:File|Memcache</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">子目录缓存:</label>
                <div class="col-md-6"><input name="DATA_CACHE_SUBDIR" type="radio" value="1" <if condition=" $addition['DATA_CACHE_SUBDIR'] ">checked</if>> 是 <input name="DATA_CACHE_SUBDIR" type="radio" value="0" <if condition=" !$addition['DATA_CACHE_SUBDIR'] ">checked</if>> 否
                </div><div class="col-md-4"><span class="gray"> 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">函数加载:</label>
                <div class="col-md-6"><input type="text" class="form-control"  name="LOAD_EXT_FILE" value="{$addition.LOAD_EXT_FILE}" size="40">
                </div><div class="col-md-4"><span class="gray"> 加载rootCms/application/common/目录下的扩展函数，扩展函数建议添加到extend.php。多个用逗号间隔。</span></div>
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