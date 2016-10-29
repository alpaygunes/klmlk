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

 		$(document).on('change', '#sectionID', function(){
 			var sectionID = $(this).val();
  	 		$.ajax({
	   	 	      type:'POST',
	   	 	      url:'index.php',
			        data:'no_template=1&component=dokuman_yonetimi&command=ajaxGetCategories&sectionID='+sectionID,
	   	 	      success:function(ajaxcevap){
	   	 	           $('#kategoridiv').html(ajaxcevap);
	   	 	      }
   	 		});
 		});

 	 	$('#yenibolum').click(function(){
			window.location.href="?component=media_sections&ovner=dokuman_yonetimi";
 	 	});
 	 	$('#yenikategori').click(function(){
 	 		window.location.href="?component=media_categories&ovner=dokuman_yonetimi";
 	 	});
 		$('#listele').click(function() {
			$('#command').val('hepsiniListele');
			$('#centerform').submit();
 	 	});
 	});
</script>

<div class="page_header">
		<table class="table table-bordered">
			<tr>
				<td>Döküman Yönetimi</td>
				<td  id="yenibolum"><div class="btn btn-primary" >Yeni Bölüm</div></td>
				<td  id="yenikategori"><div class="btn btn-primary"  >Yeni Kategori</div></td>
			</tr>
		</table>
</div>
<form id="centerform" action="index.php" method="post">

<table  class="table table-bordered">
	<tr>
		<td>Bölüm Seçin</td>
		<td>
		<div class ="selectdiv" id ="sectiondiv">
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
		<td> Kategori seçin </td>
			<td>
			
			<div class ="selectdiv" id ="kategoridiv">
				<?php  echo $this->portal->ASSIGNED['kategoriler'] ?>
			</div>
			
			</td>
		<td>
		<div class ="selectdiv" >
			<?php echo CHTML::input('button','listele','Listele','class="btn btn-primary"');?>
		</div>
		</td>
	</tr>
</table>	


<table class="table table-bordered">
<tr >
	<th class="td1" width="15">#</th>
	<th class="td2" width="25">Sıra</th>
	<th class="td3" >Belge adı</th>
	<th class="td3" >Belge açıklaması</th>
	
</tr>


<?php 
if(count($this->portal->ASSIGNED['values']))
{
		$sayac =0;
	foreach ($this->portal->ASSIGNED['values'] as $value)
	{
		?>
			<tr> 
				<td class="td1"><?php echo CHTML::input('checkbox','id[]',$value->id ,'class="cb"');?></td>
				<td class="td2"><?php echo ($sayac+1)+($this->portal->pagination->current_page_num*$this->portal->pagination->item_per_page);?></td>
				<td class="td3"><?php echo $value->belgenin_adi;?></td>
				<td class="td3"><?php echo $value->belgenin_aciklamasi;?></td>
			</tr>
		<?php
		$sayac++;
	}//--------------END FOR-------------------
}
?>
</table>
<input type="hidden" 	id ="ovner"				name="ovner" 			value="<?php echo $this->parent->request['ovner'];?>" />
<input type="hidden" 	id ="command" 			name="command" 			value="" />
<input type="hidden" 	id ="component" 		name="component" 		value="dokuman_yonetimi" />
<input type="hidden" 	id ="pageNo" 			name="pageNo" 			value="0" />
<input type="hidden" 	id ="current_page_num" 	name="current_page_num" value="<?php echo $this->portal->pagination->current_page_num;?>" />

</form>