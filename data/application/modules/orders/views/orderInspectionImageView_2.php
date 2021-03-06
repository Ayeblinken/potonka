<!DOCTYPE html>
<html>
    <head>
        <meta charset=UTF-8">
        <title>Inspection Pic Image-Full View</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">

            
        </script>
        
        <link rel="stylesheet" href="bootstrap-3.2.0-dist/css/bootstrap.min.css" type="text/css"/>
        
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" type="text/css">
        
       
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

        <link rel="stylesheet" href="bootstrap-3.2.0-dist/js/bootstrap.min.js"/>
       
 
        
        
    </head>
    <body>
             <?php 
               $src = 'https://'.$_SERVER['SERVER_NAME'].'/inspection_pics/'.$shortOrderNum.'/'.$orderID.'/'.$imageName;
               $imageUploadPath = realpath(APPPATH . '../..');
               $imageServerPath = $imageUploadPath.'/'.'inspection_pics/'.$shortOrderNum.'/'.$orderID.'/'.$imageName;
       
               ?>
        <div class="container">
            <div class="row"><br/>
                <div id="backBtn" class="col-lg-offset-9 col-md-offset-9 col-sm-offset-9 col-lg-3 col-md-3 col-sm-3">
                     <button type="submit" onclick="location.href= <?php echo "'showInspectionPics/".$orderID.'\'' ;?>" class="btn ">Back</button> 
                     
                </div><br/>
            </div>
            <div class="row">
                <img style="max-width:100%; max-height:100%;" src="<?php echo $src; ?>" alt="">
            </div>
            
            <div  id="imgScale" class="row">
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
            <!--</div>-->
        </div>    

    </body>
</html>




