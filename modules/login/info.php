<script lang="javacript">
$(document).ready(function(){
	$("#logout").click (function(){
		$("#logout").val('logout');
		$("#formlgn").submit();
	});

    $("#adi_soyadi").click (function(){
        window.location.href = "?component=ogretmen_agi";
    });

});
</script>
<form id="formlgn" name="formlgn" method="post" action="index.php">
    <ul class="ziyaretci_menu">
        <li class="adi_soyadi" id="adi_soyadi"><?php
	        echo $this->portal->profil[0]->adi_soyadi ;
            if(!$this->portal->profil[0]->adi_soyadi){
                echo $_SESSION['adi_soyadi'];
            }
	        ?></li>
        <li class="ayirac">&nbsp;</li>
        <li id="logout">Çıkış</li>
    </ul>
    <?php
        //echo CHTML::input('button','logout','Çıkış','');
        echo CHTML::input('hidden','logout','logout','');
    ?>
</form>