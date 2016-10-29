<script lang="javacript">
$(document).ready(function(){
	$("#btnlogin").click (function(){
		$("#login").val('login');
		$("#formlgn").submit();
	});
	$("#hatirla").click (function(){
		if($("#hatirla").val()=='hatirla'){
			$("#hatirla").val(null);
		}else{
			$("#hatirla").val('hatirla');
		}
	});
});
</script>
<form id="formlgn" name="formlgn" method="post" action="index.php">
<div  id="login_div">
<table >
  <tr>
    <td>Kullanıcı Adınız</td>
  </tr>
  <tr>
    <td><?php echo CHTML::input('text','username','','');?></td>
  </tr>
  <tr>
    <td>Şifresi</td>
  </tr>
  <tr>
    <td><?php echo CHTML::input('password','password','','');?></td>
  </tr>
  <tr>
    <td><?php echo CHTML::input('button','btnlogin','Giriş','');?></td>
  </tr>

</table>
  <?php echo CHTML::input('hidden','modul','login','');?>
  <?php echo CHTML::input('hidden','login','','');?>
</div>
</form>
 <script type="text/javascript">
     
 $().ready(function() {
	 $("#formlgn").validate({ 
		 
		 rules: {
		        username: {
		            required: true,
		            minlength: 6,
		            email: true
		        },
		        password: {
		            required: true,
		            minlength: 6
		        }
		    }
			 
	 });
 });
  
</script>