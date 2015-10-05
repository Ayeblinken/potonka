<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset=UTF-8">
        <title>Invoice Details</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            
             var requestCalled ="<?php echo $requestCalled?>";
             
             //alert("hyi1: "+requestCalled);
             var changeID            = "<?php echo $changeID?>";
             var orderIDView         = "<?php echo $orderID?>";
             //alert("hyi2: "+changeID);
            
        </script>
        
        <link href="media/css_bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        
        <link href="media/css_bootstrap/bootstrap-responsive.css" rel="stylesheet" type="text/css">
       
       
        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
     

    
        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>

        
        <script src="js/orderItemModuleUpSideFrm.js"></script>
        
         <!-- fileupload JS file -->
<!--       <script src="js/script.js"></script>-->
        <!--------------------------File upload scripts (JS) files END-------->
        
        
    </head>
    <body>
             <?php 
               $src = 'https://'.$_SERVER['SERVER_NAME'].'/images/Orders/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$imageName;
               $imageUploadPath = realpath(APPPATH . '../../../images/Orders');
               $imageServerPath = $imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$imageName;
               //$href = 'https://'.$_SERVER['SERVER_NAME'].'/apps/orderItemUpSideFrm/read/'.$orderItemID;
               ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div id="backBtn" class="offset9 span3">
                    <button type="submit" onclick="location.href= <?php echo "'orderItems/orderitemcontroller/orderItemUpSideFrm/read/".$orderItemID.'\'' ;?>" class="btn">Back</button> 
                </div>
                <div  id="imgScale" class="span9 offset1">
                    <?php
                    if($imgExtension == "pdf")
                    {?>
                        <?php
                        $fp = fopen($imageServerPath, "r") ;
                        //echo $imageUploadPath."<br/>";
                        //echo $imageServerPath;
                        header("Cache-Control: maxage=1");
                        header("Pragma: public");
                        header("Content-type: application/pdf");
                        header("Content-Disposition: inline; filename=".$imageName."");
                        header("Content-Description: PHP Generated Data");
                        header("Content-Transfer-Encoding: binary");
                        header('Content-Length:' . filesize($imageServerPath));
                        ob_clean();
                        flush();
                        while (!feof($fp)) {
                        $buff = fread($fp, 1024);
                        print $buff;
                        }
                        exit;
                        //header("Content-Disposition: attachment; filename=$imageName");

                        //readfile($imageServerPath);


                        //$file=fopen($src,"r") or exit("Unable to open file!");
                        //echo $src; ?>
                   <?php 
                    }
                    else
                    {?>
                         <img  src="<?php echo $src; ?>" alt="">
                    <?php
                    }?>
<!--                    <img style="height:800px; "  src="<?php //echo $src; ?>" alt="">-->
                   
                </div>
                
            </div>
        </div>    
       
        
<!--        <div id="bg">
            <img src="<?php //echo $src; ?>" alt="">
        </div>-->
<!--        <div class="navbar navbar-inverse navbar-fixed-top">
           <p>
              
               <button class="btn btn-large btn-primary" type="button">Back Button</button>
               <?php 
               //$src = 'https://'.$_SERVER['SERVER_NAME'].'/images/Orders/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/'.$orderItemID.'/'.$imageName;
               //$href = 'https://'.$_SERVER['SERVER_NAME'].'/apps/orderItemUpSideFrm/read/'.$orderItemID;
               ?>
               <button type="submit" onclick="location.href= <?php //echo "'orderItems/orderitemcontroller/orderItemUpSideFrm/read/".$orderItemID.'\'' ;?>" class="btn">Back</button><br/>
               <br/>
               <img id="bg" height="800"   src="<?php //echo $src; ?>" />
           </p>
        </div>-->
    </body>
</html>
