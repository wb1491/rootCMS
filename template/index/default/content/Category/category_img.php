<template file="content/header_file.php"/>
<template file="content/header_nav.php"/>

<template file='content/page_navigate.php'/>

   
 <div class="row indexblock">
       <h4 class="index-tit"><div class="Mfont">图片类内容推荐</div></h4>
       <content action="lists" catid="$catid" num="1" thumb="1" where='posid {gt} 0' order="listorder DESC,id DESC">
       <div class="col-md-4 col-xs-12 mdpaddingl  hidden-xs hidden-sm">
       <volist name="data" id="vo">
           <a class="photobig" href="{$vo.thumb}"><img src="{$vo.thumb}" alt="{$vo.title}"></a>
           <h3 class="righttit tm70 Mfont Mbreak" >{$vo.title}</h3>
       </volist>
       </div>
       </content>
       <div class="col-md-8 col-xs-12 xspaddingl">
           <div class="row photolist border tpllm-h">
             <content action="lists" catid="$catid" numb="8" thumb="1" where='posid {gt} 0'  order="listorder DESC,id DESC">
               <volist name="data" id="vo">
                 <div class="col-xs-3 col-md-3">
                    <a href="{$vo.url}" class="border" title="{$vo.title}" >
                       <img src="{$vo.thumb}" alt="{$vo.title}" >
                       <span class="Mfont Mbreak tm70">{$vo.title}</span>
                    </a>
                 </div>
               </volist>
             </content>
            </div>
       </div>
   </div>
   
   <content action="category" catid="$catid"  order="listorder ASC,catid DESC" >
   <volist name="data" id="vo">
   <div class="row indexblock">
       <h4 class="index-tit"><div class="Mfont">{$vo.catname}</div> <a class="Mfont" href="{$vo.url}">...</a></h4>
       <div class="col-md-12 col-xs-12 fixlist3">
           <div class="row photolist border tpllm-h">
             <content action="lists" catid="$vo['catid']" numb="18" thumb="1" order="listorder DESC,id DESC">
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
       </div>
   </div>
   </volist>
   </content>

</div>

<template file="content/footer.php"/>