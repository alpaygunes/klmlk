<?php
$satir_sayisi = 20;
$sutun_sayisi = 20;
$kart =  "<table class='table table-bordered kart' >\n";
	for($a=0;$a<$satir_sayisi;$a++){
		$kart .= "<tr id=\"satir".$a."\">\n";
		for($b=0;$b<$sutun_sayisi;$b++){
			$kart.= "<td><input type='text' class='txt_harf' id='txt_$a$b' name='kart[$a][]' maxlength='1' value=''></td>";
		}
		$kart .= "\n</tr>\n";
	}
$kart .= "</table>\n";
?>
<form enctype="multipart/form-data" id="form-upload">
<table class='table table-bordered'>
	<tr>
		<td>
				<?php
				echo $kart;
				?>
		</td>
		<td class='sag-sutun'>
			<table class="table">
				<tr>
					<td><input type="button" id="gonder" class="btn btn-primary" value="Gönder"></td>
				</tr>
				<tr>
					<td><input type="text" id="eldeki_harfler" name="eldeki_harfler" class="form-control text-left"></td>
				</tr>
				<tr>
					<td class="sonuc"></td>
				</tr>
			</table>

		</td>
	</tr>
</table>

</form>



<script>
	$('#gonder').on('click', function() {
		component 			= "frontpage";
		command				= "ajaxGetKelimeOnerileri";
		var data = $('#form-upload').serialize();
		var jsonString = JSON.stringify(data);
		$.ajax({
			url: 'index.php?no_template=1&component='+component+'&command='+command,
			dataType: 'json',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {
				//alert("Before")
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				console.log(json)
				$('.sonuc').empty();
				$.each(json[0], function( index, value ) {
					//$('.sonuc').append(value+"--------"+index+"<br>")
					$.each(value['kalip'], function( index, deger ) {
						if(deger==''){
							value['kalip'][index]="*"
						}
					});
					//$('.sonuc').append("Normal Kalıp : "+value['kalip']+"<br>")
					$('.sonuc').append("---------------------------<br>Regex kalıp : " + value['regex']+"<br>")
					var satir = value['konum']['satir'];
					if(value['kelimeler']!=null){
						$.each(value['kelimeler'], function( index, deger ) {
							if(deger['HEAD_MULT']!=undefined){
								$('.sonuc').append("<br> Kelimeler : " + deger['HEAD_MULT']+"<br>")
								$('.sonuc').append(" -Sütun : " + deger['global_sutun_no']+"/")
								$('.sonuc').append("  Satır : " + satir+"<br>")
							}
						});
					}

					//$('.sonuc').append(""+value['konum'][0]+"-")
					//$('.sonuc').append(value['konum'][1]+"<br>")
					//$('.sonuc').append("<br><br>")
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

</script>

