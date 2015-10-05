<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Theme Template for Bootstrap</title>
        <base href="<?php echo base_url(); ?>" />
        
        <!-- Bootstrap core CSS -->
        <link href="bootstrap3/dist/css/bootstrap.css" rel="stylesheet">
        <!-- Bootstrap theme -->
        <link href="bootstrap3/dist/css/bootstrap-theme.min.css" rel="stylesheet">
        
        <!-- Date Range Picker CSS
        ================================================== -->
        <link href="daterangepicker/css/daterangepicker.css" rel="stylesheet">
        
        <!-- Date Range Picker core JavaScript
        ================================================== -->
        <script src="daterangepicker/js/daterangepicker.js"></script>
        <script src="daterangepicker/js/sugar.min.js"></script>
        
        
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="bootstrap3/dist/js/bootstrap.min.js"></script>
    </head> 
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                 <div class="navbar-header">
                     <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                     </button>
                     <a class="navbar-brand" href="#">Bootstrap theme</a> 
                 </div>
                 <div class="navbar-collapse collapse">
                     <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="#">Home</a>
                        </li>
                        <li>
                            <a href="#about">About</a>
                        </li>
                        <li>
                            <a href="#contact">Contact</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#">Action</a>
                                </li>
                                <li>
                                    <a href="#">Another action</a>
                                </li>
                                <li>
                                    <a href="#">Something else here</a>
                                </li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Nav header</li>
                                <li>
                                    <a href="#">Separated link</a></li>
                                <li>
                                    <a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                     </ul>
                 </div><!--/.nav-collapse -->
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div  id="mainFrm" class="span12">
                    <form class="form-horizontal">
                         <div class="input-prepend">
                              <span class="add-on"><i class="icon-calendar"></i></span><input type="text" name="range" id="range" />
                         </div>
                    </form>
                    <div id="placeholder">
                        <figure id="chart"></figure>
                    </div>
                </div>
            </div>
            
        </div>
     
        
       
    </body>
</html>
