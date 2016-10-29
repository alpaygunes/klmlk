$(document).ready(function(){
	$(".TButton").on("click",function(){
		if($(this).attr("id")=="delete"){
			if (!confirm("Seçili öğeleri silmek istediğinizden eminmisiniz")){
			    return false;
			}
		}
		cmd									= $(this).attr("command");
		cancelSubmit						= $(this).attr("cancelSubmit");
		$("#command").val(cmd);
		$("#centerform").validate().cancelSubmit = cancelSubmit;
		$("#centerform").submit();
	});
});