<div class="page_header">Bölüm Yönetimi [Ekle/Güncelle]</div>
<form id="centerform" action="index.php" method="post">
<table class ="table table-bordered">
	<tr>
		<td width="15%" class="baslik">Bölüm Adı</td>
		<td class="td3">
		 <?php 
      		//echo CHTML::input('text','name',$this->portal->ASSIGNED['data'][0]->name,'');
      	?>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo $this->portal->ASSIGNED['data'][0]->name;?>">
		</td>
	</tr>
	<tr>
	    <td class="td3">&nbsp;</td>
	    <td><?php //echo CHTML::input('button','kaydet','Kaydet','onclick="$.submitForm(\'centerform\')"');?></td>
	</tr>
</table>
<input type="hidden" 	name="command" 		id="command"	value="save" />
<input type="hidden" 	name="component" 	id="component" 	value="sections" />
<input type="hidden" 	name="id" 	id="id"  value="<?php echo $this->parent->controller->id;?>" />
</form>

<script type="text/javascript">
     
 $().ready(function() {
	 $("#centerform").validate({ 
		 rules: {
		        name: {
		            required: true 
		        } 
		    }
	 });
 });
  
</script>