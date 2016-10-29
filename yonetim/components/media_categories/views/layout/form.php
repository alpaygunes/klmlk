<script type="text/javascript">
//bölüm seçilince ajax ile kategorilerini listele
var sectionID;
$(document).ready(function(){
	$('#sectionID').change(function() {
			parent_ID 	='&parent_ID='+$('#parent_ID').val();
			sectionID = $(this).val();
	 		$.ajax({
		 	      type:'POST',
		 	      url:'index.php',
		 	      data:'no_template=1&component=media_categories&command=ajaxSectionGetCategories&sectionID='+sectionID+'&parent_ID='+parent_ID,
		 	      success:function(ajaxcevap){
		 	           $('#kategoridiv').html(ajaxcevap); 
		 	      }
	 		});
	});
	$('#sectionID').change();
});
</script>

<div class="page_header"><?php echo $this->portal->ASSIGNED['ovner_label'];?>  Kategori Yönetimi [Ekle/Güncelle]</div>
<form id="centerform" action="index.php" method="post" enctype="multipart/form-data" >
<table class ="table table-bordered">

	<tr>
		<td width="15%">Ait olduğu Bölüm</td>
		<td>
		 <?php 
		 	$obj				= new stdClass();
		 	$obj->id  			= null;
		 	$obj->name 			= "Bölüm seçin";
		 	array_unshift($this->portal->ASSIGNED['bolumler'],$obj);
      		echo CHTML::select('sectionID',
								$this->portal->ASSIGNED['bolumler'],
								$this->portal->ASSIGNED['data'][0]->sectionID,
								'class="form-control"');
      	?>
		</td>
	</tr>
		<tr>
		<td width="15%">Üst Kategori</td>
		<td>
		  <div id="kategoridiv" ></div>
		</td>
	</tr>
	<tr>
		<td width="15%">Kategori Adı</td>
		<td>
		 <?php 
      		//echo CHTML::input('text','name',$this->portal->ASSIGNED['data'][0]->name,'');
      	?>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $this->portal->ASSIGNED['data'][0]->name?>">
		</td>
	</tr>
	<tr>
	    <td width="15%"> Menü Simgesi</td>
	    <td><input type="file" name="menu_simgesi" id="menu_simgesi"></td>
	</tr>
</table>
<input type="hidden" 	name="ovner" 		id="ovner"		value="<?php echo $this->parent->request['ovner'];?>" />
<input type="hidden" 	name="command" 		id="command"	value="save" />
<input type="hidden" 	name="component" 	id="component" 	value="media_categories" />
<input type="hidden" 	name="id" 			id="id" 		value="<?php echo $this->parent->controller->id;?>" />
<input type="hidden" 	name="parent_ID" 	id="parent_ID"  	value="<?php echo $this->portal->ASSIGNED['data'][0]->parentID;?>" />

</form>

<script type="text/javascript">
     
 $().ready(function() {
	 $("#centerform").validate({ 
		 rules: {
		        name: {
		            required: true 
		        },
		        sectionID: {
		            required: true 
		        } 
		    }
	 });
 });
  
</script>