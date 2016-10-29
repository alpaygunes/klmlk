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

<div class="page_header">Bölüm Yönetimi</div>
<form id="centerform" action="index.php" method="post">
<table class="table table-bordered table-hover" >
<tr>
	<th  width="15">#</th>
	<th  width="25">Sıra</th>
	<th>Bümlüm adı</th>
</tr>


<?php 
if(count($this->portal->ASSIGNED['values'])){
		$sayac =0;
	foreach ($this->portal->ASSIGNED['values'] as $value){
		//foreach ($value as $vl){
		?>
			<tr> 
				<td class="td1"><?php echo CHTML::input('checkbox','id[]',$value->id ,'class="cb"');?></td>
				<td class="td2"><?php echo ($sayac+1)+($this->portal->pagination->current_page_num*$this->portal->pagination->item_per_page);?></td>
				<td class="td3"><?php echo $value->name;?></td>
			</tr>
		<?php
		$sayac++;
		//}
	}//--------------END FOR-------------------
}
?>
</table>
<input type="hidden" 	id ="command" 			name="command" 			value="" />
<input type="hidden" 	id ="component" 		name="component" 		value="sections" />
<input type="hidden" 	id ="pageNo" 			name="pageNo" 			value="0" />
<input type="hidden" 	id ="current_page_num" 	name="current_page_num" value="<?php echo $this->portal->pagination->current_page_num;?>" />

</form>