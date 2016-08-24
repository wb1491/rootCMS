<template file='content/header_file.php'/>
<template file="content/header_nav.php"/>

<template file='content/page_navigate.php'/>

<content action="category" catid="$catid"  order="listorder ASC,catid ASC" >
  <volist name="data" id="vo">
    <div class="row indexblock">
      <h4 class="index-tit"><div class="Mfont">{$vo.catname}</div> <a class="Mfont" href="{$vo.url}">...</a></h4>
      <div class="col-md-12 col-xs-12 fixlist3 Mfont">
        <if condition='$vo[modelid] eq 1'>
            <ul class="artlist border tpllm-h tpllm-w" >
              <content action="lists" catid="$vo['catid']" num="44" order="listorder DESC,id DESC">
                <volist name="data" id="vo">
                  <li><a href="{$vo.url}">{$vo.title}</a></li>
                </volist>
              </content>
            </ul>
        <else/>
            <div class="row photolist border">
             <content action="lists" catid="$vo['catid']" num="18" thumb="1" order="listorder DESC,id DESC">
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
    </div>
  </volist>
</content>
<template file="content/footer.php"/>