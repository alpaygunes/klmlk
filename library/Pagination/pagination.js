$('document').ready(function(){
	$('.pagination div').click(function(){
		var pageNo = $(this).attr('pageNo');
		$('#pageNo').val(pageNo);
		if($('#paginator_command').length){
			if($('#paginator_command').val()==''){
				$('#command').val($('#paginator_command').val());
			}else{
				$('#command').val('hepsiniListele');
			}
		}else{
			$('#command').val('hepsiniListele');
		}
		$('#centerform').submit();
	});
});