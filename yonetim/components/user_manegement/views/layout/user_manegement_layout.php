<script type="text/javascript">
 	$(document).ready(function(){
 		$('#delete').toggle();
 		$('#edit').toggle();
 		$('input[type=checkbox]').change(function() {
 	 		var  deletgoster = false;
 			$('input[type=checkbox]').each(function () {
 	 			if(this.checked){
 	 				deletgoster= true;
 	 				return false;
 	 	 		}
 			});
 	 
 			$('#delete').toggle(deletgoster);
 			$('#edit').toggle(deletgoster);
 		
 		});
 	});
</script>

<div class="page_header">Kullanıcı Yönetimi</div>
<form id="centerform" action="index.php" method="post">

<!--  TABLO BAŞI  -->
<table class="table table-bordered">
	<tr class="trHeader"> 
		<th class="td1" width="15">#</th>
		<th class="td2" width="25">Sıra</th>
		<th class="td3" >Bakullanıcı Adı</th>
		<th class="td3" >Rol</th> 
	</tr>
<?php 
$sayac =0;
if (isset($this->portal->ASSIGNED['kullanicilar'])) {

	foreach ($this->portal->ASSIGNED['kullanicilar'] as $key=>$value){
		$sayac++;
		?>
			<tr> 
				<td class="td1" ><?php echo CHTML::input('checkbox','user_id[]',$value->user_id,'class="cb"');?></td>
				<td class="td2" ><?php echo $sayac;?></td>
				<td class="td3" ><?php echo $value->username;?></td>
				<td class="td3" ><?php echo $value->rol;?></td>
			</tr>
		
		<?php
	}//--------------END FOR-------------------
}
?>
</table>
<!--  TABLO SONU  -->


<input type="hidden" name="component"	id="component" 	value="user_manegement">
<input type="hidden" name="command"		id="command" 	value="">
</form>
