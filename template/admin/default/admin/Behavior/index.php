<Admintemplate file="Common/Nav"/>
<div class="widget-body tab-content padding-10">
  <h2>功能说明</h2>
  <div class="alert alert-info">
    <ul>
      <li><font color="#FF0000">行为是系统中非常重要的一项功能，如果行为设置错误会导致系统崩溃或者不稳定的情况。</font></li>
      <li>行为标签都是程序开发中，内置在程序业务逻辑流程中！</li>
      <li>行为的增加，会<font color="#FF0000">严重影响</font>程序性能，请合理使用！</li>
    </ul>
    <p><b>行为来源：</b></p>
    <p>&nbsp;&nbsp;<font color="#FF0000">行为都是在程序开发中，在程序逻辑处理中添加的一个行为定义，由站长/开发人员自行扩展。此处的行为管理，并不能添加没有在程序中定义的行为标签！了解有那些行为定义，请进入官网查看！</font></p>
  </div>
  <h2>搜索</h2>
  <div class="alert alert-info mb10">
    <form action="{$config_siteurl}index.php" method="get">
      <input type="hidden" value="Admin" name="g">
      <input type="hidden" value="Behavior" name="m">
      <input type="hidden" value="index" name="a">
      <span class="mr20"> 行为标识：
        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="{$keyword}" placeholder="请输入标识关键字...">
        <button class="btn">搜索</button>
        </span>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered dataTable no-footer">
      <colgroup>
        <col width="50">
        <col width="200">
        <col width="200">
        <col>
        <col width="60">
        <col width="60">
        <col width="120">
      </colgroup>
      <thead>
        <tr>
          <td align="center">编号</td>
          <td>行为标识</td>
          <td>行为名称</td>
          <td align="center">规则说明</td>
          <td align="center">类型</td>
          <td align="center">状态</td>
          <td align="center">操作</td>
        </tr>
      </thead>
      <volist name="data" id="vo">
        <tr>
          <td align="center">{$vo.id}</td>
          <td>{$vo.name}</td>
          <td>{$vo.title}</td>
          <td>{$vo.remark}</td>
          <td align="center"><if condition="$vo['type'] eq 1">控制器<elseif condition="$vo['type'] eq 2"/>视图</if></td>
        <td align="center"><if condition="$vo['status']">正常<else /><font color="#FF0000">禁用</font></if></td>
        <td align="center"><a href="{:url('Behavior/edit',array('id'=>$vo['id']))}">编辑</a> | <if condition="$vo['status']"><a href="{:url('Behavior/status',array('id'=>$vo['id']))}">禁用</a><else /><font color="#FF0000"><a href="{:url('Behavior/status',array('id'=>$vo['id']))}">启用</a></font></if> | <a href="{:url('Behavior/delete',array('id'=>$vo['id']))}" class="J_ajax_del">删除</a></td>
        </tr>
      </volist>
    </table>
    <div class="dt-toolbar-footer">
       {$Page}
    </div>
  </div>
</div>