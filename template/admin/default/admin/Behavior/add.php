<Admintemplate file="common/Nav"/>
<div class="widget-body tab-content padding-10">
  <h2>行为规则使用说明</h2>
  <div class="alert alert-info">
    <p><b>规则定义格式1：</b> </p>
    <ul style="color:#00F">
      <li>格式： table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max]</li>
    </ul>
    <p><b>规则字段解释：</b></p>
    <ul>
      <li>table->要操作的数据表，不需要加表前缀</li>
      <li>field->要操作的字段</li>
      <li>condition-><literal>操作的条件，目前支持字符串。条件中引用行为参数，使用{$parameter}的形式（该形式只对行为标签参数是为数组的有效，纯碎的参数使用{$self}）！</literal></li>
      <li>rule->对字段进行的具体操作，目前支持加、减 </li>
      <li>cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次 </li>
      <li>max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）</li>
    </ul>
    <p><b>规则定义格式2：</b> </p>
    <ul style="color:#00F">
      <li>格式： phpfile:$phpfile[|module:$module]</li>
    </ul>
    <p><b>规则字段解释：</b></p>
    <ul>
      <li>phpfile->直接调用已经定义好的行为文件。</li>
      <li>module->行为所属模块，没有该参数时，自动定位到 rootCMS\Applacation\Common\Behavior 目录。</li>
    </ul>
    <p><b>规则定义格式3：</b> </p>
    <ul style="color:#00F">
      <li>格式： sql:$sql[|cycle:$cycle|max:$max]</li>
    </ul>
    <p><b>规则字段解释：</b></p>
    <ul>
      <li>sql-><literal>需要执行的SQL语句，表前缀可以使用“cms_”代替。参数可以使用 {$parameter}的形式（该形式只对行为标签参数是为数组的有效，纯碎的参数使用{$self}）！</literal></li>
      <li>cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次 </li>
      <li>max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）</li>
    </ul>
  </div>
  <form class="form-horizontal" action="{:url('admin/Behavior/add')}" method="post">
    <header>基本属性</header>
    <fieldset>
      <div class="form-group">
        <label class="col-md-2 control-label">行为标识</label>
        <div class="col-md-6"><input type="test" name="name" class="form-control" id="name"/></div>
        <div class="col-md-4"><span class="gray">输入行为标识 英文字母</span></div>
      </div>
      <div class="form-group">
        <label class="col-md-2 control-label">行为名称</label>
        <div class="col-md-6"><input type="test" name="title" class="form-control" id="title"/></div>
        <div class="col-md-4"><span class="gray">输入行为名称</span></div>
      </div>
      <div class="form-group">
        <label class="col-md-2 control-label">行为类型</label>
        <div class="col-md-6"><select name="type" class="form-control">
            <option value="1" selected>控制器</option>
            <option value="2" >视图</option>
          </select></div>
        <div class="col-md-4"><span class="gray">控制器表示是在程序逻辑中的，视图，表示是在模板渲染过程中的！</span></div>
      </div>
      <div class="form-group">
        <label class="col-md-2 control-label">行为描述</label>
        <div class="col-md-6"><textarea name="remark" rows="4" cols="20" id="remark" class="form-control"></textarea></div>
        <div class="col-md-4"></div>
      </div>
      <div class="form-group">
        <label class="col-md-2 control-label">行为规则</label>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-2 paddingfixright">
              <input type="test" name="listorder[0]" placeholder="排序" class="form-control" value=""/>
            </div>
            <div class="col-md-10 paddingfixleft">
              <input type="test" name="rule[0]" placeholder="规则" class="form-control" value=""/>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <a href="" class="btn btn-primary link_add Js_ul_list_add" data-related="addItem">添加规则</a>
        </div>
      </div>
    </fieldset>
    <div class="form-actions">
      <div class="row">
        <div class="col-md-12 text-center">
          <button class="btn btn_primary mr10 J_ajax_submit_btn" type="submit">添加</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
<script type="text/javascript">
var Js_ul_list_add = $('a.Js_ul_list_add');
var new_key = 0;
if (Js_ul_list_add.length) {
    //添加
    Js_ul_list_add.click(function (e) {
        e.preventDefault();
        new_key++;
        var $this = $(this);
		//添加分类
		var _li_html = '<li>\
								<span style="width:40px;"><input type="test" name="listorder[' + new_key + ']" class="input" value="" style="width:35px;"></span>\
								<span style="width:500px;"><input type="test" name="rule[' + new_key + ']" class="input" value="" style="width:450px;"></span>\
							</li>';
        //"new_"字符加上唯一的key值，_li_html 由列具体页面定义
        var $li_html = $(_li_html.replace(/new_/g, 'new_' + new_key));
        $('#J_ul_list_' + $this.data('related')).append($li_html);
        $li_html.find('input.input').first().focus();
    });

    //删除
    $('ul.J_ul_list_public').on('click', 'a.J_ul_list_remove', function (e) {
        e.preventDefault();
        $(this).parents('li').remove();
    });
}
</script>
</body>
</html>