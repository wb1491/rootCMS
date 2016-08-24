<?php 
$getMenu = isset($Custom)? $Custom : model('admin/Menu')->getMenu();
if($getMenu) {
  if(!empty($menuReturn)){
	  echo '<div class="return"><a href="'.$menuReturn['url'].'">'.$menuReturn['name'].'</a></div>';
  }
  ?>
  <ul class="nav nav-tabs bordered">
    <?php
	foreach($getMenu as $r){
		$app = $r['app'];
		$controller = $r['controller'];
		$action = $r['action'];
        ?>
        <li <?php echo $action==ACTION_NAME ?'class="active"':""; ?>>
          <a href="<?php echo  url("".$app."/".$controller."/".$action."",$r['parameter']);?>"><?php echo $r['name'];?></a>
        </li>
        <?php
	}
	?>
  </ul>
<?php } ?>