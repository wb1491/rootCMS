<template file='content/header_file.php'/>
<template file="content/header_nav.php"/>

<template file="content/banner.php"/>

<div class="container" style="padding:0; overflow:hidden">
  <div class="row indexblock">
    <div class="col-md-4 col-xs-12 padding0 hidden-xs hidden-sm" >
      <div id="carousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" role="listbox">
          <position action="position" num='5'  thumb='1' posid="2">
          <volist name="data" id="vo" key='k'>
          <div class="item <if condition='$k eq 1'>active</if>">
            <a href="{$vo.url}" target="_blank">
              <img  src="{$vo.thumb}" alt="">
              <h3 class="lefttit tm70 Mfont Mbreak">{$vo.data.title}</h3>
            </a>
          </div>
          </volist>
          </position>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel1" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">上一页</span>
        </a>
        <a class="right carousel-control" href="#carousel1" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">下一页</span>
        </a>
      </div>
    </div>

    <div class="col-md-6 col-xs-12 padding0 overhide">
      <div class="Mfont" id="tab1">
        <ul class="tab_menu">
          <li class="current"><a href="">{:getPosition(3,'name')}</a></li>
          <li><a href="{:getCategory(10,'url')}">{:getCategory(10,'catname')}</a></li>
          <li><a href="{:getCategory(11,'url')}">{:getCategory(11,'catname')}</a></li>
        </ul>
        <div class="tab_box">
          <div>
            <ul class="artlist border ttxw">
              <position action="position" posid="3" num='21'>
              <volist name="data" id="vo" key='k'>
              <li><a href="{$vo.url}">{$vo.title}</a></li>
              </volist>
              </position>
            </ul>
          </div>
          <div class="hide">
            <ul class="artlist border ttxw">
              <content action="lists" catid="10" num="21" order="id DESC">
              <volist name="data" id="vo">
              <li><a href="{$vo.url}">{$vo.title}</a></li>
              </volist>
              </content>
            </ul>
          </div>
          <div class="hide">
            <ul class="artlist border ttxw">
              <content action="lists" catid="11" num="21" order="id DESC">
              <volist name="data" id="vo">
              <li><a href="{$vo.url}">{$vo.title}</a></li>
              </volist>
              </content>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-2 col-xs-12">
      <div class="row">
        <position action="position" posid="7" num='1'>
        <volist name="data" id="vo">
        <div class="col-md-12 col-xs-6 Mfont indexzb">
          <a <if condition=" time() egt $vo[startime] && $vo[endtime] gt time()  "> class='active'  href="{$vo.url}" <else/> class='overtime'</if>  >
            {$vo.title}<br>{$vo.description|str_cut=###,32}<br>
            <br>{$vo.startime|date="Y-m-d H:i",###}<br>
            <br>{$vo.endtime|date="Y-m-d H:i",###}</a>
        </div>
        </volist>
        </position>
        <position action="position" posid="8" num='1'>
        <volist name="data" id="vo">
        <div class="col-md-12 col-xs-6 Mfont indexzb">
          <a <if condition=" time() egt $vo['startime'] && $vo['endtime'] gt time()  "> class='active'  href="{$vo.url}" <else/> class='overtime'</if>  >
            {$vo.title}<br>{$vo.description|str_cut=###,32}<br>
               <br>{$vo.startime|date="Y-m-d H:i",###}<br>
              <br>{$vo.endtime|date="Y-m-d H:i",###}</a>
        </div>
        </volist>
        </position>
      </div>
    </div>
  </div>
  
  
  <content action="category" where="type {eq} 0"  order="listorder ASC,catid ASC" >
  <volist name="data" id="vo">
  <if condition="$vo['modelid'] eq 1">
    
    <div class="row indexblock">
      <h4 class="index-tit">
        <div class="Mfont">{$vo.catname}</div>
        <a class="Mfont" href="{$vo.url}">...</a>
      </h4>
      <div class="col-md-12 col-xs-12 Mfont">
        <ul class="artlist border fixlist2 cwzlm" >
          <content action="lists" num='45' catid="$vo['catid']" order="listorder DESC,id DESC">
          <volist name="data" id="vo" >
          <li><a href="{$vo.url}">{$vo.title}</a></li>
          </volist>
          </content>
        </ul>
      </div>
    </div>
  
  <elseif condition="$vo['modelid'] eq 3"/>
    
    <div class="row indexblock tpllm-h">
      <h4 class="index-tit">
        <div class="Mfont">{$vo.catname}</div>
        <a class="Mfont" href="{$vo.url}">...</a>
      </h4>
      <content action='lists' num="9" thumb="1" catid="$vo['catid']" order="listorder DESC,id DESC">
      <div class="col-md-4 col-xs-12 mdpaddingl  hidden-xs hidden-sm">
        <volist name="data" id="vo" key="k">
        <if condition="$k eq 1">    
        <a class="photobig" href="{$vo.url}">
          <img src="{$vo.thumb}" alt="{$vo.title}"/>
        </a>
        <h3 class="righttit tm70 Mfont Mbreak" >{$vo.title}</h3>
        </if>
        </volist>
      </div>
      <div class="col-md-8 col-xs-12 xspaddingl">
        <div class="row photolist border tpllm-h">
          <volist name="data" id="vo" key="k">
          <if condition="$k gt 1">
          <div class="col-xs-3 col-md-3">
            <a href="{$vo.url}" class="border" title="{$vo.title}" >
              <img src="{$vo.thumb}" alt="{$vo.title}" />
              <span class="Mfont Mbreak tm70">{$vo.title|str_cut=###,9}</span>
            </a>
          </div>
          </if>
          </volist>
        </div>
      </div>
      </content>
    </div>
    
  <else/>
    
    <div class="row indexblock">
      <h4 class="index-tit">
        <div class="Mfont">{$vo.catname}</div> 
        <a class="Mfont" href="{$vo.url}">...</a>
      </h4>
      <div class="col-md-4 col-xs-12 mdpaddingl  hidden-xs hidden-sm">
        <content action="lists" catid="$vo['catid']" thumb="1" num="1" where="posid {gt} 0" order="listorder DESC,id DESC">
        <volist name="data" id="vo">
        <a class="photobig" href="{$vo.url}"><img src="{$vo.thumb}" alt="{$vo.title}"></a>
        <h3 class="righttit tm70 Mfont Mbreak" >{$vo.title}</h3>
        </volist>
        </content>
      </div>
      <div class="col-md-8 col-xs-12 Mfont">
        <ul class="artlist border fixlist1 info_h" >
        <content action="lists" catid="$vo['catid']" thumb="1" num="8" order="listorder DESC,id DESC">
        <volist name="data" id="vo">
          <li><a href="{$vo.url}">{$vo.title|str_cut=###,10}</a></li>
        </volist>
        </content>
        </ul>
      </div>
    </div>
  </if>
  </volist>
  </content>
    
    
  <div class="row indexblock">
    <div class="col-xs-12 padding0">
      <ul class="links">
        <get sql="select * from cms_links where visible=1 order by updated DESC">
        <volist name='data' id="vo">
        <li>
          <a href="{$vo.url}"<if condition="$vo['target']">target='{$vo.target}'</if> title="{$vo.name}">
            <if condition="!empty($vo['image'])">
            <img src="{$vo.image}">
            <else/>
            {$vo.name}
            </if>
          </a>
        </li>
        </volist>
        </get>
      </ul>
    </div>
  </div>
   
</div>

<template file='content/footer.php'/>