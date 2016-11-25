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
					<td class="sonuc">
						<table class="table">
							<tr>
								<td>Soldan Sağa</td>
								<td>Yukardan Aşağı</td>
							</tr>
							<tr>
								<td class="normal"></td>
								<td class="sola_donu"></td>
							</tr>
						</table>
					</td>
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
				$('.sonuc .normal').empty();
				$('.sonuc .sola_donuk').empty();
				ekrana_bas(json[0],".sonuc .normal")
				ekrana_bas(json[1],".sonuc .sola_donu")
				function ekrana_bas(json,konum){
					$.each(json, function( index, value ) {
						$.each(value['kalip'], function( index, deger ) {
							if(deger==''){
								value['kalip'][index]="*"
							}
						});
						$('.sonuc').append("---------------------------<br>Regex kalıp : " + value['regex']+"<br>")
						var satir = value['konum']['satir'];
						if(value['kelimeler']!=null){
							$.each(value['kelimeler'], function( index, deger ) {
								if(deger['HEAD_MULT']!=undefined){
									if(konum==".sonuc .normal"){
										$(konum).append("<br> Kelimeler : " + deger['HEAD_MULT']+"<br>")
										$(konum).append(" -Sütun : " + deger['global_sutun_no']+"/")
										$(konum).append("  Satır : " + satir+"<br>")
									}else{
										$(konum).append("<br> Kelimeler : " + deger['HEAD_MULT']+"<br>")
										$(konum).append(" -Satır : " + deger['global_sutun_no']+"/")
										$(konum).append(" -Sütun : " + (20-satir) +"/")
									}
								}
							});
						}
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

</script>

