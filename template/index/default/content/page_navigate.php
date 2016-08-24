<div class="container" style="overflow:hidden; padding:0">
 <div class="row indexblock">
  <ul class="col-md-12 col-xs-12 scemenu Mfont" {$root_cat_id}>
    <h2><a href="{:getCategory($root_cat_id,'url')}">{:getCategory($root_cat_id,'catname')}</a></h2>
       <content action="category" catid="$root_cat_id"  order="listorder ASC" >
       <volist name='data' id="vo">
         <li><a <if condition="$catid eq $vo['catid']"> class="active"</if> href="{$vo.url}">{$vo.catname}</a></li>
       </volist>
       </content>
  </ul>
 </div>