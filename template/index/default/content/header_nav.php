<php>
    $root_cat_id = getCategoryRootId($catid,1);
    if($root_cat_id == 0){
        $root_cat_id = $catid;
    }
</php>
<div class="nav-obj">
  <div class="container">
    <nav class="navbar">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand visible-xs" href="{$config_siteurl}">Mongolia Buh China</a>
      </div>
      <div class="nav-logo hidden-xs">
        <a href="{$config_siteurl}"><img src="{$config_siteurl}statics/mgtv/images/logo.png" width="280" height="124"></a>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
        <ul class="nav navbar-nav Mfont">
          
          <li <if condition=" empty($catid) && CONTROLLER_NAME neq 'GuestBook' ">class="active" </if>>
              <a href="{$config_siteurl}" > </a>
          </li>
          <content action="category" catid="0"  order="listorder ASC" >
          <volist name="data" id="vo">
          <li <if condition=" $root_cat_id eq $vo['catid'] ">class="active"</if>><a href="{$vo.url}">{$vo.catname}</a>
          </volist>
          </content>
          
           <?php
           $usinfo = service('Passport')->getInfo();
           if(!empty($usinfo)){ ?>
          <li><a class="mvtn" href="{:url('member/User/profile')}">  </a></li>
          <li><a class="mvtn"  href="{:url('member/Index/logout')}">  </a></li>
            <?php
            }else{
            ?>
          <li><a class="mvtn" href="{:url('member/Index/login')}" ></a></li>
          <li><a class="mvtn" href="{:url('member/Index/register')}" ></a></li>
          <?php
            }
            ?>
        </ul>
      </div>
    </nav>
  </div>
</div>