<template file='content/header_file.php'/>
<template file="content/header_nav.php"/>

<template file='content/page_navigate.php'/>

<div class="row indexblock tjnr tpllm-h">
  <h4 class="index-tit"><div class="Mfont">推荐内容</div></h4>
  <div class="col-md-4 col-xs-12 mdpaddingl  hidden-xs hidden-sm">
    <content action="lists" catid="$catid" num="1" thumb="1" where='posid {gt} 0'  order="listorder DESC,id DESC">
      <volist name="data" id="vo">
        <a class="photobig" href="{$vo.url}"><img src="{$vo.thumb}" alt=""></a>
        <h3 class="righttit tm70 Mfont Mbreak" >{$vo.title}</h3>
      </volist>
    </content>
  </div>
  <div class="col-md-8 col-xs-12 Mfont">
    <ul class="artlist border fixlist1" >
      <content action="lists" catid="$catid" num="30" where='posid {gt} 0'  order="listorder DESC,id DESC">
        <volist name="data" id="vo">
          <li><a href="{$vo.url}">{$vo.title}</a></li>
        </volist>
      </content>
    </ul>
  </div>
</div>

<div class="row indexblock">
  <h4 class="index-tit">
    <div class="Mfont">{:getCategory($catid,"catname")}</div>
  </h4>
  <div class="col-md-12 col-xs-12 fixlist3 Mfont">
    <if condition='getCategory($catid,"modelid") eq 1'>
      <ul class="artlist border tpllm-w tpllm-h" >
        <content action="lists" catid="$catid" numb="44" order="listorder DESC,id DESC">
          <volist name="data" id="vo">
            <li><a href="{$vo.url}">{$vo.title}</a></li>
          </volist>
        </content>
      </ul>
      <else/>
      <div class="row photolist border tpllm-w tpllm-h">
        <content action="lists" catid="$catid" num="18" thumb="1" order="listorder DESC,id DESC">
          <volist name="data" id="vo">
            <div class="col-xs-3 col-md-2">
              <a href="{$vo.url}" class="border" title="{$vo.title}" >
                <img src="{$vo.thumb}" alt="{$vo.title}" >
                <span class="Mfont Mbreak tm70">{$vo.title}</span>
              </a>
            </div>
          </volist>
        </content>
      </div>
    </if>
  </div>

  <div class="col-md-12 col-xs-12 pages Mfont">
    {$pages}
  </div>

</div>

<template file="content/footer.php"/>