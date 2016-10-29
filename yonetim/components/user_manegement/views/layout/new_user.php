<div class="page_header">Kullanıcı Yönetimi [Yeni Kullanıcı]</div>
<form id="centerform" action="index.php" method="post" enctype="multipart/form-data">
<table class="table table-bordered" >
  <tr>
    <td class="baslik" width="15%">Eposta</td>
    <td width="50%"  class="td3">
    	<?php

            $attr	=" class=\"form-control\"";
    		$uname=null;
    		if(isset($this->portal->ASSIGNED['user'])){
				$attr	.=" readonly disabled ";
				$uname	= $this->portal->ASSIGNED['user']->username;
			}
      		echo CHTML::input('text','username',$uname,$attr);
      	?>
    </td>
<!--    <td rowspan="7" width="150" align="center"  >
    <img alt="" width="350" src="
    <?php /*
    if(isset($this->portal->ASSIGNED['user'])){
		echo configuration::$site_url.DS.$this->portal->ASSIGNED['user']->resim_yolu;
	}else{
		echo configuration::$site_url.DS.configuration::$user_image_dir.DS.'user.png';
	}
    */?>">
    </td>-->
  </tr>
  
  <tr>
    <td class="baslik" width="15%">Adı Soyadı</td>
    <td class="td3">
    	<?php 
    	if(isset($this->portal->ASSIGNED['user'])){
      		echo CHTML::input('text','adi_soyadi',$this->portal->ASSIGNED['user']->adi_soyadi,'class="form-control"');
      	}else{
			echo CHTML::input('text','adi_soyadi','','class="form-control"');
		}
      	?>
    </td>
  </tr>
  
<!--  <tr>
    <td class="baslik" width="15%">Resim</td>
    <td   class="td3">
    	<input type="file" name="resim" accept="image/*" ><span>En fazla 50 KB olmalı.</span>
    </td>
  </tr>-->
  
  <tr>
    <td class="baslik">Şifre</td>
    <td class="td3">
    	<?php 
      		echo CHTML::input('password','password_confirm','','class="form-control"');
      	?>
    </td>
  </tr>
  
  <tr>
    <td class="baslik">Tekrar Şifre</td>
    <td class="td3">
    	<?php 
      		echo CHTML::input('password','password','','class="form-control"');
      	?>
    </td>
  </tr>
  
  <tr>
    <td class="baslik">Rol</td>
    <td class="td3">
    	<?php 
    		$arr		= array();
    		$ar 		= new  stdClass();
    		
    		$ar->id 	= "uye";
    		$ar->name 	= "Üye";
    		$arr[]	= clone($ar);
    		$ar->id 	= "editor";
    		$ar->name 	= "Editör";
    		$arr[]	= clone($ar);
    		$ar->id 	= "yonetici";
    		$ar->name 	= "Yönetici";
    		$arr[]	= clone($ar);
    		
    		if(isset($this->portal->ASSIGNED['user'])){
	      		echo CHTML::select('rol',$arr,$this->portal->ASSIGNED['user']->rol,'class="form-control"');
			}else{
				echo CHTML::select('rol',$arr,'','class="form-control"');
			}
      	?>
    </td>
  </tr>
  <tr>
    <td class="baslik" >&nbsp;</td>
    <td><?php echo CHTML::input('button','ekle','Kaydet','onclick="$.submitForm(\'centerform\')" class="btn btn-primary"');?></td>
  </tr>
</table>
<input type="hidden" 	name="command" 		id="command"	value="insert" />
<input type="hidden" 	name="component" 	id="component"	value="user_manegement" />

<?php
if(isset($this->portal->ASSIGNED['user'])){
?>
	<input type="hidden" 	name="user_id" 	id="user_id"	value="<?php echo $this->portal->ASSIGNED['user']->user_id;?>"/>
<?php 
}
?>



</form>

<script type="text/javascript">
     
 $().ready(function() {
	 $("#centerform").validate({ 
		 
		 rules: {
		        username: {
		            required: true, 
		            email: true
		        },
		        adi_soyadi: {
		            required: true
		        },
		        password: {
		            required: true,
		            minlength: 6,
		            equalTo: "#password_confirm"
		        },
		        password_confirm: {
		            required: true,
		            minlength: 6 
		        }
		    }
			 
	 });
 });
  
</script>