<div class="row">

  <div class="col-sm-12">
    <div class="widget-body">
      <Admintemplate file="common/Nav.php"/>
      <div class="tab-content padding-10">
        <div id="tab1" class="tab-pane fade active in">
          <form method='post' class="form-horizontal"  action="{:url('admin/Config/attach')}">
            <fieldset>
              <div class="form-group">
                <label class="col-md-2 control-label">网站存储方案:</label>
                <div class="col-md-6"><?php echo \libs\util\Form::select($dirverList, $Site['attachment_driver'], 'name="attachment_driver"'); ?> <em>存储方案请放在 extend/libs/driver/attachment/ 目录下</em></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">允许上传附件大小:</label>
                <div class="col-md-6"><input type="text" class="form-control" name="uploadmaxsize" id="uploadmaxsize" size="10" value="{$Site.uploadmaxsize}"/>
                </div>
                <div class="col-md-4">  <span class="gray">KB</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">允许上传附件类型:</label>
                <div class="col-md-6">
                  <input type="text" class="form-control" name="uploadallowext" id="uploadallowext" size="50" value="{$Site.uploadallowext}"/>
                </div>
                <div class="col-md-4"> <span class="gray">多个用"|"隔开</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">前台允许上传附件大小:</label>
                <div class="col-md-6"><input type="text" class="form-control" name="qtuploadmaxsize" id="uploadmaxsize" size="10" value="{$Site.qtuploadmaxsize}"/>
                </div>
                <div class="col-md-4"> <span class="gray">KB</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">前台允许上传附件类型:</label>
                <div class="col-md-6"><input type="text" class="form-control" name="qtuploadallowext" id="uploadallowext" size="50" value="{$Site.qtuploadallowext}"/>
                </div>
                <div class="col-md-4"> <span class="gray">多个用"|"隔开</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">保存远程图片过滤域名:</label>
                <div class="col-md-6">
                  <input type="text" class="form-control" name="fileexclude" id="fileexclude" value="{$Site.fileexclude}"/>
                </div>
                <div class="col-md-4"> <span class="gray">多个用"|"隔开，域名以"/"结尾，例如：http://www.linuxxt.cn/</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">FTP服务器地址:</label>
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6 fix-right">
                      <input type="text" class="form-control" name="ftphost" id="ftphost" size="30" value="{$Site.ftphost}"/>
                    </div>
                    <div class="col-md-6">
                      <label class="col-md-6 control-label">FTP服务器端口:</label>
                      <div class="col-md-6 fix-right">
                      <input type="text" class="form-control" name="ftpport" id="ftpport" size="5" value="{$Site.ftpport}"/>
                      </div>
                    </div></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">FTP上传目录:</label>
                <div class="col-md-6"><input type="text" class="form-control" name="ftpuppat" id="ftpuppat" size="30" value="{$Site.ftpuppat}"/> 
                </div>
                <div class="col-md-4"> <span class="gray">"/"表示上传到FTP根目录</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">FTP用户名:</label>
                <div class="col-md-6"><input type="text" class="form-control" name="ftpuser" id="ftpuser" size="20" value="{$Site.ftpuser}"/></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">FTP密码:</label>
                <div class="col-md-6"><input type="password" class="form-control" name="ftppassword" id="ftppassword" size="20" value="{$Site.ftppassword}"/></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">FTP是否开启被动模式:</label>
                <div class="col-md-6"><input name="ftppasv" type="radio" value="1"  <if condition=" $Site['ftppasv'] == '1' ">checked</if> /> 开启 <input name="ftppasv" type="radio" value="0" <if condition=" $Site['ftppasv'] == '0' ">checked</if> /> 关闭</div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">FTP是否使用SSL连接:</label>
                <div class="col-md-6"><input name="ftpssl" type="radio" value="1"  <if condition=" $Site['ftpssl'] == '1' ">checked</if> /> 开启 <input name="ftpssl" type="radio" value="0" <if condition=" $Site['ftpssl'] == '0' ">checked</if> /> 关闭</div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">FTP超时时间:</label>
                <div class="col-md-6"><input type="text" class="form-control" name="ftptimeout" id="ftptimeout" size="5" value="{$Site.ftptimeout}"/>
                </div>
                <div class="col-md-4"> <span class="gray">秒</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">是否开启图片水印:</label>
                <div class="col-md-6">
                  <input class="radio_style" name="watermarkenable" value="1" <if condition="$Site['watermarkenable'] eq '1' "> checked</if> type="radio">
                  启用&nbsp;&nbsp;&nbsp;&nbsp;
                  <input class="radio_style" name="watermarkenable" value="0" <if condition="$Site['watermarkenable'] eq '0' "> checked</if>  type="radio">
                  关闭 </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">水印添加条件:</label>
                <div class="col-md-6">宽
                  <input type="text" class="form-control" name="watermarkminwidth" id="watermarkminwidth" size="10" value="{$Site.watermarkminwidth}" />
                  X 高
                  <input type="text" class="form-control" name="watermarkminheight" id="watermarkminheight" size="10" value="{$Site.watermarkminheight}" />
                  PX</div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">水印图片:</label>
                <div class="col-md-6"><input type="text" name="watermarkimg" id="watermarkimg" class="form-control" size="30" value="{$Site.watermarkimg}"/>
                  <span class="gray">水印存放路径从网站根目录起</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">水印透明度:</label>
                <div class="col-md-6"><input type="text" class="form-control" name="watermarkpct" id="watermarkpct" size="10" value="{$Site.watermarkpct}" />
                  <span class="gray">请设置为0-100之间的数字，0代表完全透明，100代表不透明</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">JPEG 水印质量:</label>
                <div class="col-md-6"><input type="text" class="form-control" name="watermarkquality" id="watermarkquality" size="10" value="{$Site.watermarkquality}" />
                  <span class="gray">水印质量请设置为0-100之间的数字,决定 jpg 格式图片的质量</span></div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">水印位置:</label>
                <div class="col-md-6">
                  <div class="locate clearfix">
                    <ul class="cc" id="J_locate_list">
                      <li class="<if condition="$Site['watermarkpos'] eq '1' "> current</if>"><a href="" data-value="1">左上</a></li>
                      <li class="<if condition="$Site['watermarkpos'] eq '2' "> current</if>"><a href="" data-value="2">中上</a></li>
                      <li class="<if condition="$Site['watermarkpos'] eq '3' "> current</if>"><a href="" data-value="3">右上</a></li>
                      <li class="<if condition="$Site['watermarkpos'] eq '4' "> current</if>"><a href="" data-value="4">左中</a></li>
                      <li class="<if condition="$Site['watermarkpos'] eq '5' "> current</if>"><a href="" data-value="5">中心</a></li>
                      <li class="<if condition="$Site['watermarkpos'] eq '6' "> current</if>"><a href="" data-value="6">右中</a></li>
                      <li class="<if condition="$Site['watermarkpos'] eq '7' "> current</if>"><a href="" data-value="7">左下</a></li>
                      <li class="<if condition="$Site['watermarkpos'] eq '8' "> current</if>"><a href="" data-value="8">中下</a></li>
                      <li class="<if condition="$Site['watermarkpos'] eq '9' "> current</if>"><a href="" data-value="9">右下</a></li>
                    </ul>
                    <input id="J_locate_input" name="watermarkpos" type="hidden" value="{$Site.watermarkpos}">
                  </div>
                </div>
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