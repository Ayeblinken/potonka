<!DOCTYPE html>
<html>
    <head>
        <meta charset=UTF-8">
        <title>OrderRedo Image-Full View</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            
             var requestCalled = "<?php echo $requestCalled?>";
             
             var orderIDView   = "<?php echo $orderID?>";

            
        </script>
        
        <link href="media/css_bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        
        <link href="media/css_bootstrap/bootstrap-responsive.css" rel="stylesheet" type="text/css">
       
       
        <script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
     

    
        <script type="text/javascript" charset="utf-8" src="media/js_bootstrap/bootstrap.min.js"></script>

        
        <script src="js/orderItemModuleUpSideFrm.js"></script>
 
        
        
    </head>
    <body>
             <?php 
               $src = 'https://'.$_SERVER['SERVER_NAME'].'/images/Orders/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/Redo/'.$orderRedoID.'/'.$imageName;
               $imageUploadPath = realpath(APPPATH . '../../../images/Orders');
               $imageServerPath = $imageUploadPath.'/'.$yearOrder.'/'.$monthOrder.'/'.$orderID.'/Redo'.$orderRedoID.'/'.$imageName;
       
               ?>
        <div class="container-fluid">
            <div class="row-fluid"><br/>
                <div id="backBtn" class="offset9 span3">
                    <button type="submit" onclick="location.href= <?php if($requestCalled == 'redo'){echo "'redo/".$orderRedoID.'\'' ;}else if($requestCalled == 'redoReadOnly'){ echo "'redoReadOnly/".$orderID."/".$orderRedoID.'\'' ; }?>" class="btn">Back</button> 
                </div><br/>
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
                       ?>
                   <?php 
                    }
                    else
                    {?>
                         <img  src="<?php echo $src; ?>" alt="">
                    <?php
                    }?>
                </div>
            </div>
        </div>    

    </body>
</html>


