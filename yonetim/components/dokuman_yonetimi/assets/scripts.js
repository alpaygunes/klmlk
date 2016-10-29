
var yeniDokApp = angular.module('yeniDokumanUygulamam',[]);
yeniDokApp.controller('YeniDokumanController', function ($scope,$http,$window,$filter){

    $scope.dosya_secildi     = false;
    if($window.id){
        $scope.id               = $window.id;
        $scope.dosya_secildi    = true;
    }


//----------------------------- MAIN FUNCTION -----------------------//
    function main()
    {
        //eğer id si olan bir istek ise düzenleme için döküman bilgilerini sunucudan alalım
        if($scope.id)
        {
            dokumanBilgileriniYukle();
            icerikBransIlisikleriniYukle();
        }
        bolumleriYukle();
        kategorileriYukle();
        branslariYukle();
    }


//----------------------------------------------------------------------------- DÖKÜMANIN BİLGİLERİ
    function dokumanBilgileriniYukle()
    {
        $scope.adres ='?component=dokuman_yonetimi&command=dokumanKaydiniVer&id='+$scope.id;
        $http.get($scope.adres).success(function(data)
        {

                $scope.dokuman_bilgileri = data[0];
                console.log($scope.dokuman_bilgileri);
                $scope.bolumler_mdl = $scope.dokuman_bilgileri.bolum_id;
                //döküman bilgileri yüklendikten sonra düzenlenen datanın bölümüne ait kategorileri yüklenir. ----KATEGORİLER
                kategorileriYukle($scope.dokuman_bilgileri.bolum_id);

        });
    }








//----------------------------------------------------------------------------- BÖLÜMLER
    function bolumleriYukle()
    {
        $scope.adres ='?component=dokuman_yonetimi&command=bolumleriVer';
        $http.get($scope.adres).success(function(data)
        {
            $scope.bolumler    = data;
        });
    }


//----------------------------------------------------------------------------- KATEGORİLER
    function kategorileriYukle(bolumID)
    {
        $scope.adres ='?component=dokuman_yonetimi&command=kategorileriVer&sectionID='+bolumID;
        $http.get($scope.adres).success(function(data)
        {
            $scope.kategoriler = data;
            if($scope.id){
                $scope.kategoriler_mdl =$scope.dokuman_bilgileri.categoryID;
            }
        });
    }



//----------------------------------------------------------------------------- GÖREVLERİ LİSTELEYELİM
    function branslariYukle()
    {
        $scope.adres ='?component=genel_yardimci&command=branslariVer';
        $http.get($scope.adres).success(function(data)
        {
            $scope.branslar         = data;
        });
    }

//----------------------------------------------------------------------------- DÖKÜMANIN BRANS İLİŞİKLERİNİ YÜKLE
    $scope.icerik_brans_ilisikligi = Array();
    function icerikBransIlisikleriniYukle()
    {
        $scope.adres ='?component=genel_yardimci&command=icerikBransIlisikleriniVer&icerik_id='+$scope.id;
        $http.get($scope.adres).success(function(data)
        {
            $scope.icerik_brans_ilisikligi  = data;
        });
    }

    //  bölüm seçilince kategoriler yüklensin
    $scope.bolumClick = function(bolum_id)
    {
        kategorileriYukle(bolum_id);
    };

    // değiştirilen ilişik diziye yansıtılır
    $scope.ilisikleDizisineEkleCikar = function ($event, id) {
        var checkbox = $event.target;
        if (checkbox.checked) {
            $scope.icerik_brans_ilisikligi.push({brans_id: id});
        } else {
            //var single_object = $filter('filter')($scope.icerik_brans_ilisikligi, { brans_id: id});
            for (var i = $scope.icerik_brans_ilisikligi.length - 1; i >= 0; i--) {
                if ($scope.icerik_brans_ilisikligi[i].brans_id == id) {
                    $scope.icerik_brans_ilisikligi.splice(i, 1);
                }
            }
        }
    };


    // check box ilişiklimi
    $scope.ilisiklimi = function(brans_id)
    {
        //single_object = $filter('filter')(foo.results, function (d) {return d.id === 2;})[0];
        var single_object = $filter('filter')($scope.icerik_brans_ilisikligi, { brans_id: brans_id},true);
        if(secili_kategori!=undefined)
        {
            if(secili_kategori.length)
            {
                return true;
            }
        }
    };



    $scope.dosyaSecildiyse= function(files)
    {
        $scope.dosya_hata_goster    = false;
        $scope.dosya_secildi        = true;
        $scope.dosya_gonderildi     = '';
        $scope.format_uygunsuz      = '';

        var fd = new FormData();
        //Take the first selected file
        fd.append("file", files[0]);
        if($scope.id)
        {
            fd.append("id", $scope.dokuman_bilgileri.id);
        }
        var uploadUrl = '?component=dokuman_yonetimi&command=dosyayiKaydet';
        $scope.yukleme_anim_goster = true;
        $http.post(uploadUrl, fd, {
            withCredentials: true,
            headers: {'Content-Type': undefined},
            transformRequest: angular.identity
        }).success(function (cevap) {
            console.log(cevap);
            if (cevap.basarili == 1)
            {
                $scope.dosya_gonderildi         = "Dosya gönderildi";
                $scope.dosya_hata_goster        = false;
                $scope.format_uygunsuz          = '';
            }
            else
            {
                $scope.boyut_fazla = cevap.format_uygunsuz;
                $scope.format_uygunsuz = cevap.format_uygunsuz;
            }
            $scope.yukleme_anim_goster = false;

        }).error(function (cevap) {
            $scope.yukleme_anim_goster = false;
        });
    };


    $scope.formuGonder  = function () {
        if($scope.centerform.$valid)
        {
            if ($scope.dosya_secildi)
            {
                //kontrol edelim en az bir branşla ileşil kurulmuşmu
                if($scope.icerik_brans_ilisikligi.length)
                {
                    $scope.dokuman_bilgileri.categoryID     = $scope.kategoriler_mdl;
                    $http(
                        {
                            method  : 'POST',
                            url     : '?component=dokuman_yonetimi&command=kaydet',
                            headers : {'Content-Type': 'Content-type: application/json'},
                            params  : {'dokuman_bilgileri': $scope.dokuman_bilgileri,icerik_brans_ilisikligi:angular.toJson($scope.icerik_brans_ilisikligi)}
                        })
                        .success(function (data)
                        {
                            console.log(data);
                            if(!data.basarili)
                            {
                                alert('Bilgiler kaydedilirken sorun yaşandı');
                            }else{
                                $scope.dokuman_bilgileri.id = data.id;
                                $scope.id                   = data.id;
                            }
                            window.location.href='?component=dokuman_yonetimi';
                        });
                }
                else
                {
                    alert("Dökümanı en az bir branşla ilişiklendirmelisiniz");
                }
            }else{
                $scope.dosya_hata_goster                       = true;
            }
        }
    };






////////////////////////////////////////////////// BAŞLA ////////////////////////////////////////////
                                                   main();
////////////////////////////////////////////////// BAŞLA ////////////////////////////////////////////

});


yeniDokApp.directive('stringToNumber', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function(value) {
                return '' + value;
            });
            ngModel.$formatters.push(function(value) {
                return parseFloat(value, 10);
            });
        }
    };
});



angular.bootstrap(document,['yeniDokumanUygulamam']);