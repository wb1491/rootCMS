<div class="banner">
  <div id="carousel-example-generic" class="carousel slide container padding0" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <position action="position" num='5'  posid="1">
          <volist name="data" id="vo" key="k">
             <li data-target="#carousel-example-generic" data-slide-to="{$k}"  <if condition="$k eq 1">class="active"</if>></li>
          </volist>
      </position>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <position action="position" num='5'  posid="1">
          <volist name="data" id="vo" key="k">
      <div class="item <if condition=' $k eq 1'>active</if>">
        <a href="{$vo.data.url}" target="_blank">
          <img class="left" src="{$vo.data.imgurl}" alt="">
          <h3 class="lefttit tm70 Mfont Mbreak">{$vo.data.title}</h3>
        </a>
      </div>
           </volist>
      </position>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">上一页</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">下一页</span>
    </a>
  </div>
</div>