<php>
    $SEO['title'] = "个人设置 -- ".$Config['sitename'];
</php>
<template file='member/Publics/homeHeader.php'/>
<template file="Content/header_nav.php"/>

<div class="container userprofile">
  <div class="row indexblock">
    <div class="col-md-2">
      <template file="member/Publics/homeUserMenu.php"/>
    </div>
    <div class="col-md-10 sm-paddingfix">
      <ul class="nav nav-tabs">
        <li <if condition="$type eq 'profile'"> class="active"</if>>
        <a href="#tab_1" data-toggle="tab"><span>基本资料</span></a>
        </li>
        <li <if condition="$type eq 'avatar' "> class="active"</if>>
        <a href="#tab_2" data-toggle="tab"><span>修改头像</span></a>
        </li>
        <li <if condition="$type eq 'avatar' "> class="active"</if>>
        <a href="#tab_3" data-toggle="tab"><span>修改密码</span></a>
        </li>
      </ul>
      <div class="tab-content border">
        <!--修改基本资料-->
        <div class="tab-pane fade active in" id="tab_1">
          <form id="doprofile" action="{:url('User/doprofile')}" method="post">
            <div class="featured-box featured-box-secundary default info-content">
              <div class="box-content clearfix">
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-2 text-right" style="line-height: 42px;"><span>*</span>昵称：</div>
                    <div class="col-md-6 text-left"> <input id="rnickname" class="form-control" type="text" value="{$userinfo.nickname}" name="nickname"> </div>
                    <div class="col-md-4 text-left" id="mnickname"></div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-2 text-right" style="line-height: 42px;"><span>*</span>邮箱：</div>
                    <div class="col-md-6"><input id="remail" class="form-control" type="text" value="{$userinfo.email}" name="email"></div>
                    <div class="col-md-4 text-left" id="memail"></div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-2 text-right" style="line-height: 42px;">性　别：</div>
                    <div class="col-md-6">
                      <select id="rsex" class="form-control" name="sex">
                        <option <if condition="$userinfo['sex'] eq 0 ">selected="selected"</if> value="1">未 知</option>
                        <option <if condition="$userinfo['sex'] eq 1 ">selected="selected"</if> value="1">帅 哥</option>
                        <option <if condition="$userinfo['sex'] eq 2 ">selected="selected"</if> value="2">美 女</option>
                      </select>
                    </div>
                    <div class="col-md-4"></div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-2 text-right" style="line-height: 42px;">个人介绍：</div>
                    <div class="col-md-6">
                      <textarea name="about" cols="30" rows="7" id="rselfIntroduce" class="form-control">{$userinfo.about}</textarea>
                    </div>
                    <div class="col-md-4" id="mselfIntroduce" class="input_msg"></div>
                  </div>
                </div>
                <?php foreach ($forminfos['base'] as $k => $v) { ?>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-2 text-right" style="line-height: 42px;">
                          <?php echo $v['name'] ?>：
                        </div>
                        <div class="col-md-6">
                            <?php
                            $tmp = str_replace("class=\"input\"", "class=\"form-control\"", $v['form']);
                            echo $tmp;
                            ?>
                        </div>
                        <div class="col-md-4" id="memail"><?php
                            echo $v['tips'];
                            ?>
                        </div>
                      </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <button class="btn btn-default" id="seveProfile" type="button">保存修改</button>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div id="errMessage" style="display: none;" class="alert alert-danger" role="alert"></div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <!--修改头像-->
        <div class="tab-pane fade" id="tab_2">
          <div class="avatar_box clearfix"  id="crop-avatar">
              
                <!-- Current avatar -->
                <div style="text-align:center;color:red;margin:50px 0">提示：点击头像上传</div>
                <div class="avatar-view" title="点击更换头像">
                    <img src="{:url('api/Avatar/index',array('uid'=>$uid,'size'=>180))}" alt="Avatar"/>
                </div>

                <!-- Cropping modal -->
                <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form class="avatar-form" action="{:url('member/Index/uploadavatar','auth_data='.$auth_data)}" enctype="multipart/form-data" method="post">
                                <div class="modal-header">
                                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                                    <h4 class="modal-title" id="avatar-modal-label">更换头像</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="avatar-body" >

                                        <!-- Upload image and data -->
                                        <div class="avatar-upload">
                                            <input class="avatar-src" name="avatar_src" type="hidden"/>
                                            <input class="avatar-data" name="avatar_data" type="hidden"/>
                                            <label for="avatarInput">头像上传</label>
                                            <input class="avatar-input" id="avatarInput" name="avatar_file" type="file"/>
                                        </div>

                                        <!-- Crop and preview -->
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="avatar-wrapper"></div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="avatar-preview preview-lg"></div>
                                                <div class="avatar-preview preview-md"></div>
                                                <div class="avatar-preview preview-sm"></div>
                                            </div>
                                        </div>

                                        <div class="row avatar-btns">
                                            <div class="col-md-9">
                                                <div class="btn-group">
                                                    <button class="btn btn-primary" data-method="rotate" data-option="-90" type="button" title="Rotate -90 degrees">左旋</button>
                                                    <button class="btn btn-primary" data-method="rotate" data-option="-15" type="button">-15°</button>
                                                    <button class="btn btn-primary" data-method="rotate" data-option="-30" type="button">-30°</button>
                                                    <button class="btn btn-primary" data-method="rotate" data-option="-45" type="button">-45°</button>
                                                </div>
                                                <div class="btn-group">
                                                    <button class="btn btn-primary" data-method="rotate" data-option="90" type="button" title="Rotate 90 degrees">右旋</button>
                                                    <button class="btn btn-primary" data-method="rotate" data-option="15" type="button">15°</button>
                                                    <button class="btn btn-primary" data-method="rotate" data-option="30" type="button">30°</button>
                                                    <button class="btn btn-primary" data-method="rotate" data-option="45" type="button">45°</button>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-primary btn-block avatar-save" type="submit"> 提 交 </button>
                                            </div>
                                        </div>
                                        <div class="row alert-msg"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal -->

                <!-- Loading state -->
                <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                
          </div>
        </div>
        <!--修改密码-->
        <div class="tab-pane fade" id="tab_3">
          <div class="featured-box featured-box-secundary default info-content">
            <div class="box-content clearfix">
              <div class="container" style="margin-top:50px;"></div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-2 text-right">当前密码：</div>
                  <div class="col-md-4">
                    <input id="roldpassword" class="form-control input" type="password" maxlength="20" name="roldpassword"/>
                  </div>
                  <div class="col-md-6 text-left" id="moldpassword">请输入您当前使用的密码。</div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-2 text-right">设置新密码：</div>
                  <div class="col-md-4">
                    <input id="rpassword" class="form-control input" type="password" maxlength="20" name="rpassword"/>
                  </div>
                  <div class="col-md-6 text-left" id="mpassword">6到20个字符，请使用英文字母（区分大小写）、符号或数字。</div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-2 text-right">确认新密码：</div>
                  <div class="col-md-4">
                    <input id="rpassword2" class="form-control input" type="password" maxlength="20" name="rpassword"/>
                  </div>
                  <div class="col-md-6 text-left" id="mpassword2">再次输入您所设置的密码，以确认密码无误。</div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12 text-center">
                    <button class="btn btn-default" id="sevePassword" type="button">保存修改</button>
                  </div>
                </div>
              </div>
              <div class="container" style="padding-bottom:40px;">
                <div class="col-md-12">
                  <div id="errMessage" style="display: none;" class="alert alert-danger" role="alert"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="{$config_siteurl}statics/js/wind.js"></script>
<script type="text/javascript" src="{$config_siteurl}statics/js/common.js"></script>
<script type="text/javascript" src="{$model_extresdir}js/profile.js"></script> 
<script type="text/javascript" src="{$config_siteurl}statics/js/ajaxForm.js"></script>
<script type="text/javascript">
    profile.init();
    
    $(function(){
        //赋值更换的头像
        $("#avatar-modal").on('hidden.bs.modal', function (e) {
            var src = $(".avatar-view > img").attr("src");
            var avatar = $(".user_image").find("img").attr("src");
            if(avatar != src){
                $(".user_image").find("img").attr("src",src);
            }
        });
    });
</script>

<template file="member/Publics/homeFooter.php"/>

<template file="Content/footer.php"/>
