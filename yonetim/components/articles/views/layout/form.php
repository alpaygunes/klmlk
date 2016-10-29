<script type="text/javascript">
	tinymce.init({
   		selector: "#body",
   		theme: "modern",
   	    width: '90%',
   	    height: 300,
   	    plugins: [
   	         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
   	         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
   	         "save table contextmenu directionality emoticons template paste textcolor jbimages"
   	   ],
   	   content_css: "css/content.css",
   	   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons | fontsizeselect", 
   	   style_formats: [
   	        {title: 'Bold text', inline: 'b'},
   	        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
   	        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
   	        {title: 'Example 1', inline: 'span', classes: 'example1'},
   	        {title: 'Example 2', inline: 'span', classes: 'example2'},
   	        {title: 'Table styles'},
   	        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
   	    ],
      	fontsize_formats: "8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 18pt 20pt 24pt 30pt 40pt",
   	   	    
     	relative_urls: false,
      	language : "tr_TR",
       	entity_encoding : "raw"
 		});

var sectionID;
$(document).ready(function(){
	$('#sectionID').change(function() {
			category_ID 	='&category_ID='+$('#category_ID').val();
			sectionID 		= $(this).val();
	 		$.ajax({
		 	      type:'POST',
		 	      url:'index.php',
		 	      data:'no_template=1&component=articles&command=ajaxSectionGetCategories&sectionID='+sectionID+'&category_ID='+category_ID,
		 	      success:function(ajaxcevap){
		 	           $('#kategoridiv').html(ajaxcevap);
			 	      	<?php
		 	      			//eğer edite articlenin kategorisi seçili olsun
		 	      			if($this->portal->request['command']=='edit'){
		 	      				echo "kategoriyiSeciliYap(".$this->portal->ASSIGNED['artdata'][0]->categoryID.")";
		 	      			}
	 	      			?>
		 	      }
	 		});
	});

	
	
	$('#sectionID').change();
	function kategoriyiSeciliYap(categoryID){
		$('#categoryID').val(categoryID);
	}

	$('#resim_ekle').click(function(){
		$.ajax({
	 	      type:'POST',
	 	      url :'index.php',
	 	      data:'no_template=1&component=articles&command=ajaxFileManager&dir=HOME',
	 	      success:function(ajaxcevap){
		 	    $('#plugin').show();
	 	        $('#plugin').html(ajaxcevap);
		 	       $('.fmdelete').hide();
	 	      }
		});
	});
	$('#plugin').hide();
	
	$('#manset_olsun_btn').change(function(){
			$('#manset_olsun').val(0);
 			if($(this).is(":checked")){
				$('#manset_olsun').val(1);
			}
	});
});

</script>
<div class="page_header">Makale Yönetimi [Ekle/Güncelle]</div>
<form id="centerform" action="index.php" method="post">
<table class="table table-bordered">
	<tr>
		<td width="15%" class="baslik">Bölüm</td>
		<td class="td3">
		 <?php 
		 	$obj				= new stdClass();
		 	$obj->id  			= null;
		 	$obj->name 			= "Bölüm seçin";
		 	array_unshift($this->portal->ASSIGNED['bolumler'],$obj);
      		echo CHTML::select('sectionID',
								$this->portal->ASSIGNED['bolumler'],
								$this->portal->ASSIGNED['articleSectionID'],
								'class="form-control"');
      	?>
		</td>
	</tr>
	<tr>
		<td width="15%" class="baslik">Kategori</td>
		<td class="td3">
		 <div id="kategoridiv"></div>
		</td>
	</tr>
	<tr>
		<td width="15%" class="baslik" >Makale Başlığı</td>
		<td class="td3">
			<input type="text" name="header" class="form-control" id="header" value="<?php echo  $this->portal->ASSIGNED['artdata'][0]->header;?>">
			<span>
				<?php 
					$checked	= null;	
					if ($this->portal->ASSIGNED['artdata'][0]->manset_olsun=="1") {
						$checked = "checked";
					}
					echo CHTML::input('checkbox','manset_olsun_btn','',$checked)
				 ?>
				Manşet olsun
			</span>
		</td>
	</tr>
	<tr>
		<td colspan="2" > 
			<?php //echo CHTML::input('button','resim_ekle','Resim Ekle','');?>
		 		<textarea id="body" name="body"  ><?php echo $this->portal->ASSIGNED['artdata'][0]->body;?></textarea>
		 </td>
	</tr>
</table>
<input type="hidden" 	name="command" 		id="command"		value="cevabiKaydet" />
<input type="hidden" 	name="component" 	id="component" 		value="articles" />
<input type="hidden" 	name="id" 			id="id"  			value="<?php echo $this->portal->ASSIGNED['artdata'][0]->id;?>" />
<input type="hidden" 	name="manset_olsun" 		id="manset_olsun"		value="<?php echo $this->portal->ASSIGNED['artdata'][0]->manset_olsun;?>" />
</form>

<script type="text/javascript">
     
 $().ready(function() {
	 $("#centerform").validate({ 
		 rules: {
			 	sectionID: {
		            required: true 
		        },
		        categoryID: {
		            required: true 
		        },
		        header: {
		            required: true 
		        },
		        body: {
		            required: true 
		        }
		    }
	 });

	 
 });
  
</script>