<?php
$satir_sayisi = 15;
$sutun_sayisi = 15;
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
		<td width="50%">
				<?php
				echo $kart;
				?>
		</td>
		<td width="50%">
				<table class="table">
					<tr>
						<td><input type="button" id="gonder" class="btn btn-primary" value="Gönder"></td>
					</tr>
					<tr>
						<td><input type="text" id="eldeki_harfler" name="eldeki_harfler" class="form-control text-left"></td>
					</tr>
					<tr>
						<td class="sonuc">
							<div style="height: 500px;overflow: scroll;width: 100%">
								<table class="table table-bordered">
									<tr>
										<td>Soldan Sağa</td>
										<td>Yukardan Aşağı</td>
									</tr>
									<tr>
										<td class="normal"></td>
										<td class="sola_donuk"></td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
				</table>


		</td>
	</tr>
</table>

</form>



<script>
	$('#gonder').on('click', function() {
		$('.sonuc .normal').empty();
		$('.sonuc .sola_donuk').empty;
		$('.sonuc .normal').html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
		$('.sonuc .sola_donuk').html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
		component 			= "frontpage";
		command				= "ajaxGetKelimeOnerileri";
		var data = $('#form-upload').serialize();
		//var jsonString = JSON.stringify(data);
		$.ajax({
			url: 'index.php?no_template=1&component='+component+'&command='+command,
			dataType: 'json',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function() {

			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				console.log(json)
				$('.sonuc .normal').empty();
				$('.sonuc .sola_donuk').empty;
				ekrana_bas(json[0],".sonuc .normal")
				ekrana_bas(json[1],".sonuc .sola_donuk")
				function ekrana_bas(json,konum){
					$.each(json, function( index, value ) {
						$.each(value['kalip'], function( index, deger ) {
							if(deger==''){
								value['kalip'][index]="*"
							}
						});
						//$('.sonuc').append("---------------------------<br>Regex kalıp : " + value['regex']+"<br>")
						var satir = value['konum']['satir'];
						if(value['kelimeler']!=null){
							$.each(value['kelimeler'], function( index, deger ) {
								if(deger['HEAD_MULT']!=undefined){
									harf_sayisi = deger['HEAD_MULT'].length*deger['HEAD_MULT'].length;
									if(harf_sayisi<6){
										harf_sayisi = 6
									}
									if(konum==".sonuc .normal"){
										$(konum).append("<br><span style=\"font-size:"+harf_sayisi+"px\" class=\"belirtec\" satir=\""+(satir)+"\" sutun=\""+deger['global_sutun_no']+"\">" + deger['HEAD_MULT'] + "</span>\t\t")
									}else{
										$(konum).append("<br><span style=\"font-size:"+harf_sayisi+"px\" class=\"belirtec\" satir=\""+deger['global_sutun_no']+"\" sutun=\""+(15-satir-1)+"\">" + deger['HEAD_MULT'] + "</span>\t\t")
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

	$('.sonuc').on("mouseover",'.belirtec',function () {
		satir= $(this).attr('satir');
		sutun= $(this).attr('sutun');
		hedef_id = '#txt_'+satir+''+sutun;
		$(hedef_id).css('background','#ccc');
	})
	$('.sonuc').on("mouseout",'.belirtec',function () {
		satir= $(this).attr('satir');
		sutun= $(this).attr('sutun');
		hedef_id = '#txt_'+satir+''+sutun;
		$(hedef_id).css('background','#fff');
	})

</script>

