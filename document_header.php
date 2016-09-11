<html lang="en-us">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1" >
	<meta charset="utf-8" />

        <link rel="apple-touch-con" href="" />

	<title>mTrack Admin Panel</title>

        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">

	<!-- The Columnal Grid and mobile stylesheet -->
        <link rel="stylesheet" href="css/columnal/columnal.css" type="text/css" media="screen" />

	<!-- Fixes for IE -->
        
	<!--[if lt IE 9]>
            <link rel="stylesheet" href="css/columnal/ie.css" type="text/css" media="screen" />
            <link rel="stylesheet" href="css/ie8.css" type="text/css" media="screen" />
            <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
            <script type="text/javascript" src="js/flot/excanvas.min.js"></script>
	<![endif]-->        
	
	<!-- Now that all the grids are loaded, we can move on to the actual styles. --> 
        <link rel="stylesheet" href="js/jqueryui/jqueryui.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/global.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/config.css" type="text/css" media="screen" />
        
        <!-- Use CDN on production server -->
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>-->
        <script src="js/jquery-1.6.4.min.js"></script> 
        <script src="js/jqueryui/jquery-ui-1.8.16.custom.min.js"></script>
        
        <!-- Adds HTML5 Placeholder attributes to those lesser browsers (i.e. IE) -->
        <script type="text/javascript" src="js/jquery.placeholder.1.2.min.shrink.js"></script>
        
        <!-- Menu -->
        <link rel="stylesheet" href="js/superfish/superfish.css" type="text/css" media="screen" />
        <script src="js/superfish/superfish.js"></script>
        
        <!-- Adds charts -->
        <script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
        <script type="text/javascript" src="js/flot/jquery.flot.pie.min.js"></script>
        <script type="text/javascript" src="js/flot/jquery.flot.stack.min.js"></script>
		<script type="text/javascript" src="js/flot/jquery.flot.selection.js"></script>        
        
         <!-- Form Validation Engine -->
        <script src="js/formvalidator/jquery.validationEngine.js"></script>
        <script src="js/formvalidator/jquery.validationEngine-en.js"></script>
        <link rel="stylesheet" href="js/formvalidator/validationEngine.jquery.css" type="text/css" media="screen" />
        
        <!-- Sortable, searchable DataTable -->
        <script src="js/jquery.dataTables.min.js"></script>
        
        <!-- Custom Tooltips -->
        <script src="js/twipsy.js"></script>
        
        <!-- WYSIWYG Editor -->
        <script src="js/cleditor/jquery.cleditor.min.js"></script>
        <link rel="stylesheet" href="js/cleditor/jquery.cleditor.css" type="text/css" media="screen" />
        
        <!-- Form Validation Engine -->
        <script src="js/formvalidator/jquery.validationEngine.js"></script>
        <script src="js/formvalidator/jquery.validationEngine-en.js"></script>
        <link rel="stylesheet" href="js/formvalidator/validationEngine.jquery.css" type="text/css" media="screen" />
        
        <!-- Fullsized calendars -->
        <link rel="stylesheet" href="js/fullcalendar/fullcalendar.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="js/fullcalendar/fullcalendar.print.css" type="text/css" media="print" />
        <script src="js/fullcalendar/fullcalendar.min.js"></script>
        <script src="js/fullcalendar/gcal.js"></script>
        
        <!-- Colorbox is a lightbox alternative-->
        <script src="js/colorbox/jquery.colorbox-min.js"></script>
        <link rel="stylesheet" href="js/colorbox/colorbox.css" type="text/css" media="screen" />

        <!-- Colorpicker -->
        <script src="js/colorpicker/colorpicker.js"></script>
         <script src="js/muse.js"></script>
        <link rel="stylesheet" href="js/colorpicker/colorpicker.css" type="text/css" media="screen" />
        
        <!-- Uploadify -->
        <script type="text/javascript" src="js/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
        <script type="text/javascript" src="js/uploadify/swfobject.js"></script>
        <link rel="stylesheet" href="js/uploadify/uploadify.css" type="text/css" media="screen" />
        
         <!-- All the js used in the demo -->
         <script src="js/demo.js"></script>
         
          <!-- All customise js used in the application -->
         <script src="js/main.js"></script>

</head>
<body>
	<form action="" method="post" name="mainForm" id="mainForm" enctype="multipart/form-data" />
	<input type="hidden" name="page" id="page" value="" />
	<input type="hidden" name="function" id="function" value="" />
    <input type="hidden" name="edit_id" id="edit_id" value="<?php if(isset($_POST['edit_id'])) echo $_POST['edit_id']; ?>" />