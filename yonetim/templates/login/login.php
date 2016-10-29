<?php defined('GIRIS_KARTI') or die('Restricted access');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?php echo 'templates/'.$this->template.'/css/ff.css';?>" type="text/css" />
<link rel="stylesheet" href="<?php echo configuration::$site_url.'/library/jvalidator/css/screen.css';?>" type="text/css" />
<title><?php echo $this->baslik;?></title>
<script type="text/javascript" src="<?php echo configuration::$site_url.'/library/jquery/jquery-2.0.2.js';?>"></script>
<script type="text/javascript" src="<?php echo configuration::$site_url.'/library/jscript.js';?>"></script>
<script type="text/javascript" src="<?php echo configuration::$site_url.'/library/jvalidator/jquery.validate.js';?>"></script>
<script type="text/javascript" src="<?php echo configuration::$site_url.'/library/jvalidator/localization/messages_tr.js';?>"></script>
</head>

<body>
<!-- --------------------------------MAIN CONTAINER -->
<div id="main_kontainer">
	<!-- TOP -->
	<?php if($this->pozitions['top']!='hide'){?>
	<div id="top">
    	
    </div>
    <?php }?>
    <!-- TOP END -->
    
    <!-- LEFT -->
    <?php if($this->pozitions['left']!='hide'){?>
	<div id="left">

    </div>
    <?php }?>
    <!-- LEFT END -->
    
    <!-- CENTER -->
    <?php if($this->pozitions['center']!='hide'){?>
	<div id="center">
		<?php     	
			echo $this->loadModul('login');
			echo $this->showMessages();
		?>    	
    </div>
    <?php }?>
    <!-- CENTER END -->
    
    <!-- RIGHT -->
     <?php if($this->pozitions['rigth']!='hide'){?>
	<div id="right">
  
    </div>
    <?php }?>
    <!-- RIGHT END -->
    
    <!-- BOTTON -->
     <?php if($this->pozitions['bottom']!='hide'){?>
	<div id="botton">
   
    </div>
    <?php }?>
    <!-- BOTTON END -->
</div>
<!-- --------------------------------MAIN CONTAINER END-->

</body>
</html>
