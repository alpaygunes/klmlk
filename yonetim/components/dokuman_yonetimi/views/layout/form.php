<div class="dokuman_yonetimi">
    <div class="page_header">Belge Yönetimi [Ekle/Güncelle]</div>
    <form id="centerform" name="centerform"   method="post" enctype="multipart/form-data" ng-controller="YeniDokumanController">
        <table class ="table table-bordered">
        <tr>
            <td width="15%" class="baslik">Bölüm Seçin</td>
            <td>
                 <select
                     name="bolumler"
                     ng-change="bolumClick(bolumler_mdl)"
                     ng-model="bolumler_mdl"
                     ng-options="bolum.id as bolum.name for bolum in bolumler"
                     class="form-control" required >
                     <option></option>
                 </select>
            </td>
        </tr>
        <tr>
            <td width="15%" class="baslik">Kategori Seçin</td>
            <td class="td3">
                <select
                    ng-model="kategoriler_mdl"
                    ng-options="kategori.id as kategori.name for kategori in kategoriler"
                    class="form-control" required >
                    <option></option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="15%" class="baslik">Belgenin Adı</td>
            <td class="td3">
                <input required  type="text" ng-model="dokuman_bilgileri.belgenin_adi" name="belgenin_adi" value="{{dokuman_bilgileri.belgenin_adi}}"   class="form-control">
            </td>
        </tr>
        <tr>
            <td width="15%" class="baslik">Belgenin Açıklaması</td>
            <td class="td3">
                <input required type="text" ng-model="dokuman_bilgileri.belgenin_aciklamasi" value="{{dokuman_bilgileri.belgenin_aciklamasi}}"   class="form-control">
            </td>
        </tr>
        <tr>
            <td width="15%" class="baslik">Dosyanızı seçiniz</td>
            <td class="td3">
	            <span   class="label label-danger">{{format_uygunsuz}}</span>
	            <span   class="label label-info">{{dosya_gonderildi}}</span>
	            <span   class="label label-danger">{{boyut_fazla}}</span>
            <input type="file"  name="file" onchange="angular.element(this).scope().dosyaSecildiyse(this.files)" >
            <span class="labe label-warning"  ng-show="dosya_hata_goster">Lütfen dosya seçin</span>
	            <div class="upload_progres" ng-show="yukleme_anim_goster"></div>
            </td>
        </tr>
	        <tr>
		        <td width="15%" class="baslik">Sınıfı </td>
		        <td> <input class="form-control sinif" type="number" string-to-number name="input"   ng-model="dokuman_bilgileri.sinif"
		                    min="0" max="12" required>
		                    <span> İdari dökümanlar için "0"  seçiniz </span>
		        </td>
	        </tr>
    </table>

    <!-- ---------------------------------------------------- HEDEF KİTLE    -->
        <div class="hedef_kitle">
            <h4>Paylaşımın ilgili olduğu branşları işaretleğiniz</h4>
            <div class="branslar">
                <div class="brans_kutu" ng-repeat="brans in branslar">
                    <label>
                        <input type="checkbox"
                               ng-checked       = "ilisiklimi(brans.id)"
                               ng-click         = "ilisikleDizisineEkleCikar($event,brans.id)"
                                >
                        {{brans.brans_adi}}
                    </label>
                </div>
            </div>
        </div>

        <button class="btn btn-primary center-block" ng-click="formuGonder()">Kaydet</button>
    </form>

</div>


<script>
    <?php if(isset($this->request['id'][0])>0){?>
            window.id =<?php echo $this->request['id'][0];?>
        <?php }else{ ?>
            window.id =false;
        <?php }?>
</script>



