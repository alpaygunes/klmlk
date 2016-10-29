<script type="text/javascript" src="<?php echo configuration::$site_url.'/yonetim/components/mole_panel/mole.js';?>"></script>

<div id="mole-conteiner" class="container-fluid">
	<table class="table table-bordered" style="height: 750px">
		<tr style="height: 64px">
			<td rowspan="2" width="175px" id="mole-left-colum" >
				1
			</td>
			<td id="mole-right">
				<!-- ----------------------- TAB MENÜLER --------------------- -->
				<ul class="nav nav-pills">
					<li><a  href="#1a" data-toggle="tab">Komut Çalıştır</a></li>
					<li><a  href="#2a" data-toggle="tab">Ayarlar</a></li>
				</ul>


				<div class="tab-content clearfix">
					<!-- ---------------------------    Komut Çalıştır    --------------------- -->
					<div class="tab-pane active" id="1a">
						Burada komutlar çalıştırılıp sonuçları gösterilecek
					</div>

					<!-- ---------------------------    Komut Çalıştır    --------------------- -->
					<div class="tab-pane" id="2a">
						Ayarlar burda olacak
					</div>
				</div>

				<!-- ----------------------- TAB MENÜLER - SON --------------------- -->
			</td>
		</tr>
	</table>
</div>
