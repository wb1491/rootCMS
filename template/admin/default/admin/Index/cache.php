<admintemplate file="admin/content_head.php"/>
<style>
@media (min-width: 768px){
    .bs-example {
        margin-left: 0;
        margin-right: 0;
        background-color: #fff;
        border-width: 1px;
        border-color: #ddd;
        border-radius: 4px 4px 0 0;
        box-shadow: none;
    }
}
.bs-example {
    position: relative;
    padding: 15px 15px 15px;
    margin: 0 0px 15px;
    background-color: #fafafa;
    border-color: #e5e5e5 #eee #eee;
    border-style: solid;
    border-width: 1px;
}
.bs-example .btn{
    margin-top: 20%;
}
</style>
<div class="row">
  <div class="col-sm-12">
    <div class="bs-example bs-example-type">
      <h1 class="text-primary" style="margin: 20px 0;">缓存更新</h1>
      <table class="table eg-1">
        <tbody>
          <tr>
            <td>
              <h3>更新站点数据缓存</h3>
              <small>修改过站点设置，或者栏目管理，模块安装等时可以进行缓存更新</small>
            </td>
            <td><a class="btn btn-primary" href="{:url('admin/Index/upcache','type=site')}">提交</a></td>
          </tr>
          <tr>
            <td>
              <h3>更新站点模板缓存</h3>
              <small>当修改模板时，模板没及时生效可以对模板缓存进行更新</small>
            </td>
            <td><a class="btn btn-primary" href="{:url('admin/Index/upcache','type=template')}">提交</a></td>
          </tr>
          <tr>
            <td>
              <h3>清除网站运行日志</h3>
              <small>网站运行过程中会记录各种错误日志，以文件的方式保存</small>
            </td>
            <td><a class="btn btn-primary" href="{:url('admin/Index/upcache','type=logs')}">提交</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<admintemplate file="admin/content_footer.php"/>