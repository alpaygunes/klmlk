
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

 		//bölüm seçilince ajax ile kategorilerini dokumanlari_getir
 		$('#sectionID').change(function() {
 	 			var sectionID = $(this).val();
	  	 		$.ajax({
		   	 	      type:'POST',
		   	 	      url:'index.php',
		   	 	      data:'no_template=1&component=categories&command=ajaxGetCategories&sectionID='+sectionID,
		   	 	      success:function(ajaxcevap){
		   	 	           $('#kategoridiv').html(ajaxcevap); 
		   	 	      }
	   	 		});
 		});
 		//sayfa yüklendikten sonra kategori kutusu dolsun
 		function kategorileriIlkYukleme(parentID){
 			var sectionID = $( "#sectionID option:selected").val();
 			if(sectionID){
 				sectionID="&sectionID="+sectionID;
 			}
  	 		$.ajax({
	   	 	      type:'POST',
	   	 	      url:'index.php',
	   	 	      data:'no_template=1&component=categories&command=ajaxGetAllCategories&'+parentID+sectionID,
	   	 	      success:function(ajaxcevap){
	   	 	           //$('#kategoridiv').html(ajaxcevap); 
	   	 	      }
   	 		});
  		}
  		<?php 	
  		if(isset($this->parent->request['parentID'])){
				$id = $this->parent->request['parentID'];
  					echo "kategorileriIlkYukleme('parentID=$id')";
  				}else{
					echo "kategorileriIlkYukleme('')";
				}
  		
  		?>
 		

 		$('#listele').click(function() {
			$('#command').val('hepsiniListele');
			$('#centerform').submit();
 	 	});
 	});
</script>

<div class="page_header">Kategori Yönetimi</div>
<form id="centerform" action="index.php" method="post">

<table class="table table-bordered">
	<tr >
		<td>Bölüm Seçin</td>
		<td>
		<div   id ="sectiondiv">

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
		<td> </td>
		<td></td>
		<td>
			<?php //echo CHTML::input('button','dokumanlari_getir','Listele','');?>
            <input type="button" name="listele" id="listele" value="Listele" class="btn btn-primary">
		</td>
	</tr>
</table>










<table class="table table-bordered table-hover">
<tr class="trHeader">
	<th class="td1" width="15">#</th>
	<th class="td2" width="25">Sıra</th>
	<th class="td3" >Kategori Adı</th>
	<th class="td3">Bölüm Adı</th>
	<th class="td3">Üst Kategori</th>
</tr>


<?php 

	$sayac =0;
foreach ($this->portal->ASSIGNED['values'] as $value){
	?>
		<tr> 
			<td class="td1"><?php echo CHTML::input('checkbox','id[]',$value->id ,'class="cb"');?></td>
			<td class="td2"><?php echo ($sayac+1)+($this->portal->pagination->current_page_num*$this->portal->pagination->item_per_page);?></td>
			<td class="td3"><?php echo $value->name;?></td>
			<td class="td3"><?php echo $this->parent->controller->getSectionName($value->sectionID);?></td>
			<td class="td3"><?php echo $this->parent->controller->getParentName($value->parentID);?></td>
		</tr>
	<?php
	$sayac++;
}
//--------------END FOR-------------------
?>
</table>
<input type="hidden" 	id ="command" name="command" 		value="" />
<input type="hidden" 	id ="component" name="component" 	value="categories" />
<input type="hidden" 	id ="pageNo" 		name="pageNo" 		value="0" />
<input type="hidden" 	id ="current_page_num" 	name="current_page_num" value="<?php echo $this->portal->pagination->current_page_num;?>" />

</form>