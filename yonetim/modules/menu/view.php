<script type="text/javascript">
$(document).ready(function(){
	$(".moge").click(function(){
			trgt	=$(this).attr("component");
			$('#component').val(trgt);
			$('#mnfrm').submit();
	});
});
</script>
<form id="mnfrm" method="post" action="index.php">
<ul id 	 ="menu">
    <li class = "moge" component="user_manegement" ><span>Üye Yönetimi</span></li>
    <li class = "moge" component="sections" ><span>Bölüm Yöneticisi</span></li>
    <li class = "moge" component="categories" ><span>Kategori Yöneticisi</span></li>
    <li class = "moge" component="articles" ><span>Makale Yöneticisi</span></li>
    <li class = "moge" component="dokuman_yonetimi" ><span>Döküman Yöneticisi</span></li>
	<li class = "moge" component="mole_panel" ><span>Mule Panel</span></li>
</ul>
	<input type="hidden" name="component"	id="component" 	value="">
</form>