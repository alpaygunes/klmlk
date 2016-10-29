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


 		//bölüm seçilince  kategorilerini dokumanlari_getir
 		$(document).on('change', '#sectionID', function(){
 			$('#command').val('hepsiniListele');
 			$('#centerform').submit();
 		});

 		$(document).on('change', '#categoryID', function(){
 			$('#command').val('hepsiniListele');
 			$('#centerform').submit();
 		});

 

 		$('#listele').click(function() {
			$('#command').val('hepsiniListele');
			$('#centerform').submit();
 	 	});
 	 	
 	 	
 	});
</script>


<div class="page_header">Makale Yönetimi</div>
<form id="centerform" name="centerform" action="index.php" method="post">

<table class="table table-bordered table-hover">
	<tr>
		<td>Bölüm Seçin</td>
		<td>
		<div>
			<?php 
			 	$obj				= new stdClass();
			 	$obj->id  			= null;
			 	$obj->name 			= "Bölüm seçin";
			 	array_unshift($this->portal->ASSIGNED['bolumler'],$obj);
	      		echo CHTML::select('sectionID',
									$this->portal->ASSIGNED['bolumler'],
									$this->parent->request['sectionID'],
									'class="form-control"');
			?>
		</div>
</td>
		<td>Kategori seçin</td>
			<td>
			
			<div class ="selectdiv" id ="kategoridiv">
						
			</div>
			<?php  echo $this->portal->ASSIGNED['kategoriler'] ?>
			</td>
		<td>
		<div class ="selectdiv" >
			<?php //echo CHTML::input('button','dokumanlari_getir','Listele','');?>
            <input class="btn btn-primary" name="listele" id="listele" value="Listele">
		</div>
		</td>
	</tr>
</table>	
	
<table  class="table table-bordered table-hover">
<tr>
	<th class="td1" width="15">#</th>
	<th class="td2" width="25">Sıra</th>
	<th class="td3" >Başlık</th>
</tr>
<?php 
	$sayac =0;
	if(count($this->portal->ASSIGNED['values'])){
		foreach ($this->portal->ASSIGNED['values'] as $value){
			?>
				<tr> 
					<td class="td1"><?php echo CHTML::input('checkbox','id[]',$value->id ,'class="cb"');?></td>
					<td class="td2"><?php echo ($sayac+1)+($this->portal->pagination->current_page_num*$this->portal->pagination->item_per_page);?></td>
					<td class="td3"><?php echo $value->header;?></td>
				</tr>
			<?php
			$sayac++;
		}
	}
//--------------END FOR-------------------
?>
</table>

<input type="hidden" 	id ="command" 		name="command" 		value="" />
<input type="hidden" 	id ="component" 	name="component" 	value="articles" />
<input type="hidden" 	id ="pageNo" 		name="pageNo" 		value="0" />
<input type="hidden" 	id ="current_page_num" 	name="current_page_num" value="<?php echo $this->portal->pagination->current_page_num;?>" />
</form>