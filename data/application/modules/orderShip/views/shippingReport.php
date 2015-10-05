<!DOCTYPE html>
<html ang="en">
    <head>
        <meta charset=UTF-8">
        <title>Shipping</title>
        <base href="<?php echo base_url(); ?>" />
        
        <link href="media/css_bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        
        <link href="media/css_bootstrap/bootstrap-responsive.css" rel="stylesheet" type="text/css">
        
        
        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
       
        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-templ.js"></script>
        
        <script src="js/shippingReport.js"></script>
        

</head>
<body>
    <div class="container" style="margin-top: 10px">
        <table  class="table table-striped table-bordered"  id="records" >
                    <thead>
                        <tr>
                            <th>Shipping ID</th>
                            <th>Receipeint </th>
                            <th>Bill to 3rd Party</th>
                            <th>Note</th>
                            <th>Bar Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
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
                            <td>${ShippingID}</td>
                            <td>${Receipeint}</td>
                            <td>${Blind}</td>
                            <td>${notes}</td>
                            <td>${barcodeID}</td>
                        </tr>
                </script>
       
        
            
    </div>  
    
 
   
       
        <div id="footer">
       </div><!-- end page -->
</body>
</html>
  
    
  
    
    




