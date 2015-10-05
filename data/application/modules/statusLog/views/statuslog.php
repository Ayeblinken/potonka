<!DOCTYPE html>
<html ang="en">
    <head>
        <meta charset=UTF-8">
        <title>Status Log</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            
            var orderID = "<?php echo $orderID;?>";
           //alert(orderID);
        </script>
        
        <link href="media/css_bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        
        <link href="media/css_bootstrap/bootstrap-responsive.css" rel="stylesheet" type="text/css">
        
        
         
        
        
        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
<!--        <script src="js/jquery.maskedinput.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/additional-methods.min.js"></script>-->
        
        
        
       
        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>
        
        
        
        <script type="text/javascript" src="js/jquery-templ.js"></script>
        
        <script src="js/clearForm.js"></script>
        
        <script src="js/statusLogModule.js"></script>
        
        <style type="text/css">
            
         
            
             legend     {
                        font-size: 107.5%;
                }
            label.error {
                        display: inline-block;
			font-weight: bold;
			color: red;
			font-size: 87.5%;
                        padding: 2px 8px;
			margin-top: 2px;
			
		}
/*           label.naming {
                        display: inline-block;
			font-weight: bold;
			color: blue;
			font-size: 87.5%;
                        padding: 2px 8px;
			margin-top: 2px;
			
		}
             */
      
        </style>
        
        
    </head>
    <body>
        <span id="table-wrapper">
            <div class="container-fluid" style="margin-top: 10px">
                <section class="row-fluid">
                    <div class="span12">
                         <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="records" >
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Old Status</th>
                                    <th>New Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        
                         <script type="text/template" id="readTemplate">
                            <tr id="${ID}">
                                <td>${orderIDDashNum}</td>
                                <td>${date}</td>
                                <td>${name}</td>
                                <td>${oldStatus}</td>
                                <td>${newStatus}</td>
                                <td>${notes}</td>
                            </tr>
                        </script>
                        
                    </div>
                    
                </section>
                
            </div>
            
        </span>
        
    </body>
    
</html>
