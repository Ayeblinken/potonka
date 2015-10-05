<!DOCTYPE html>
<html lang="en">
    <head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta name="description" content="">
         <meta name="author" content="">
         <title>Img Resize Summary</title>
         <base href="<?php echo base_url(); ?>" />
         
         <script type="text/javascript">
            var year     = "<?php echo $year;?>";
            alert(year);
         </script>
     
         <!-- Bootstrap core CSS -->
         <link href="bootstrap3/dist/css/bootstrap.css" rel="stylesheet">
     
         <!-- Bootstrap theme -->
         <link href="bootstrap3/dist/css/bootstrap-theme.min.css" rel="stylesheet">
     
         <!-- Bootstrap core JavaScript
         ================================================== -->
         <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
         
         <script src="js/jquery.validate.min.js"></script>
         <script src="js/additional-methods.min.js"></script>
         
         <script src="bootstrap3/dist/js/bootstrap.min.js"></script>
         
         <script src="js/clearForm.js"></script>
          
         <script src="js/orderItemImgResizeSummary.js"></script>
          <style type="text/css">
/*           .form-horizontal .form-group {
                margin-bottom: 5px;
    if help block is in use 8 Tightens up the space inbetween fields
            }
           .error {
                color:red;
           }*/
/*           .modal-footer {
                 background-color: #f5f5f5;
           }*/
/*            .modal-footer {
  padding: 14px 15px 15px;
  margin-bottom: 0;
  text-align: right;
  background-color: #f5f5f5;
  border-top: 1px solid #ddd;
  -webkit-border-radius: 0 0 6px 6px;
     -moz-border-radius: 0 0 6px 6px;
          border-radius: 0 0 6px 6px;
  *zoom: 1;
  -webkit-box-shadow: inset 0 1px 0 #ffffff;
     -moz-box-shadow: inset 0 1px 0 #ffffff;
          box-shadow: inset 0 1px 0 #ffffff;
}*/
/*            .valid {
                color:green;
            }*/
           </style>
    </head>
    <body>
        <div class="container">
           
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <p id="ResizeThumbNailprepressBar" class="hide">
                                    <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Converting...</strong></span>
                    </p>
<!--                        <div class="progress-bar"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                            <span class="sr-only">45% Complete</span>
                        </div>-->
                  
                </div>    
            </div>
<!--            <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                             <button id="closeMyModalTopBtn" type="button" class="close" aria-hidden="true">&times;</button>
                             <h3 class="modal-title" id="myModalLabel">Please wait</h3>
                        </div>
                        <div class="modal-body">
                            <p id="message">
                                <img src="images/ajaxLoad/ajax-loader.gif" alt="Ajax Loading Animation" />&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Submitting Data...</strong></span>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button id="closeMyModalBtn" type="button" class="btn btn-default hide">Close</button>
                            <button id="closeMyModalBtn" type="button" class="btn btn-default hide" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
    </body>
</html>

