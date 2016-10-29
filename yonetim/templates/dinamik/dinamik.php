<?php defined('GIRIS_KARTI') or die('Restricted access');?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->baslik;?></title>
    <link rel="stylesheet" href="<?php echo 'templates/'.$this->template.'/css/ff.css';?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo configuration::$site_url.'/library/jvalidator/css/screen.css';?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo configuration::$site_url.'/library/Pagination/pagination.css';?>" type="text/css" />

    <script type="text/javascript" src="<?php echo configuration::$site_url.'/library/jquery/jquery-2.0.2.js';?>"></script>
    <script type="text/javascript" src="<?php echo configuration::$site_url.'/library/jscript.js';?>"></script>
    <script type="text/javascript" src="<?php echo configuration::$site_url.'/library/jvalidator/jquery.validate.js';?>"></script>
    <script type="text/javascript" src="<?php echo configuration::$site_url.'/library/jvalidator/localization/messages_tr.js';?>"></script>
    <script type="text/javascript" src="<?php echo configuration::$site_url.'/library/TBjavascript.js';?>"></script>
    <script type="text/javascript" src="<?php echo configuration::$site_url.'/library/Pagination/pagination.js';?>"></script>


    <link href="<?php echo 'templates/'.$this->template.'/bootstrap/css/bootstrap.min.css';?>" rel="stylesheet"  type="text/css">
    <script   src="<?php echo 'templates/'.$this->template.'/bootstrap/js/bootstrap.min.js';?>"></script>

</head>

<body>



<!-- --------------------------------MAIN CONTAINER -->
<div id="main_kontainer">
 <table width="100%">
 <tr>
 	<td valign="top" style="width: 175px;">
        <div class="user-exit"><?php  echo $this->loadModul('login');?></div>
 	<!-- LEFT -->
    <?php if($this->pozitions['left']!='hide'){?>
	<div id="left">
		 <?php
			echo $this->loadModul('menu');
		?>
    </div>
    <?php }?>
    <!-- LEFT END -->
 	</td>
 	
 	<td valign="top">
 	    <!-- CENTER -->
    <?php if($this->pozitions['center']!='hide'){?>
	<div id="center col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php
			echo $this->showMessages();
			echo $this->component_output;
		?>
    </div>
    <?php }?>
    <!-- CENTER END -->
 	</td>
 	
 </tr>
 </table>
    
    

    
    
    
    <!-- BOTTON -->
     <?php if($this->pozitions['bottom']!='hide'){?>
	<div id="botton">
    	 botton
    </div>
    <?php }?>
    <!-- BOTTON END -->
</div>
<!-- --------------------------------MAIN CONTAINER END-->
</body>
</html>