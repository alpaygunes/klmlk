<script lang="javacript">
$(document).ready(function(){
	$("#logout").click (function(){
		$("#logout").val('logout');
		$("#formlgn").submit();
	});
});
</script>
<form id="formlgn" name="formlgn" method="post" action="index.php">
<div id="login_info_div">
<?php
echo "". $_SESSION['username'];
?>
    <input name="logout" id="logout" value="" type="button" class="close cikis-btn">
    <input name="logout" id="logout" value="logout" type="hidden">
</div>
</form>