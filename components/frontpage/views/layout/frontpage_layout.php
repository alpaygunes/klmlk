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

echo '<form enctype="multipart/form-data" id="form-upload">';
echo $kart;
echo '</form>';
?>

<input type="button" id="gonder" class="btn btn-primary" value="Gönder">

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
				$('.sag-sutun').empty();
				$.each(json, function( index, value ) {
					console.log(value);
					$('.sag-sutun').append(value[1])
					$('.sag-sutun').append(value[0][0]+"-")
					$('.sag-sutun').append(value[0][1]+"<br>")
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

</script>

