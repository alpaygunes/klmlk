<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->baslik;?></title>
    <link rel="stylesheet" href="<?php echo 'templates/'.$this->template.'/css/ff.css';?>" type="text/css" />
    <script type="text/javascript" src="<?php echo configuration::$site_url.'/library/jquery/jquery-2.0.2.js';?>"></script>
    <script type="text/javascript" src="<?php echo 'templates/'.$this->template.'/js.js';?>"></script>
    <link href="<?php echo 'templates/'.$this->template.'/bootstrap/css/bootstrap.min.css';?>" rel="stylesheet"  type="text/css">
    <script   src="<?php echo 'templates/'.$this->template.'/bootstrap/js/bootstrap.min.js';?>"></script>
</head>

<body>


<!-- ---------------------------------- main container ---------------------------------------- -->
<div class="main-container">
    <!-- -------------------------------------------------------------------------------BORTAL- -->
    <div class="portal">

        <!-- -------------------------------------------------------------------------------ORTA SUTUN- -->

            <div class="component-border">
                <?php
                echo $this->showMessages();
                echo $this->component_output;
                ?>
            </div>



        <!-- -------------------------------------------------------------------------------BOTTOM- -->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bottom">
            www.alpaygunes.com
        </div>


    </div>
</div>


</body>
</html>