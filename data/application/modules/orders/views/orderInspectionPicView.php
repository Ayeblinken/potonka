<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inspection Pics</title>
        <base href="<?php echo base_url(); ?>" />
        <script type="text/javascript">
            var orderID     = "<?php echo $orderID;?>";
        </script>
        
        <link rel="stylesheet" href="bootstrap-3.2.0-dist/css/bootstrap.min.css" type="text/css"/>
        
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" type="text/css">
        
        <!-- Photoswipe Gallery css) -->
        <link href="photoswipe/photoswipe-gallery.css" type="text/css" rel="stylesheet" />
       
        
        <link href="photoswipe/photoswipe.css" type="text/css" rel="stylesheet" />
        
        <!--photoswipe Image Gallery files -->
        <script type="text/javascript" src="photoswipe/lib/klass.min.js"></script>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

        <link rel="stylesheet" href="bootstrap-3.2.0-dist/js/bootstrap.min.js"/>
       
        <!--photoswipe Image Gallery files -->
        <script type="text/javascript" src="photoswipe/code.photoswipe.jquery-3.0.5.min.js"></script>
        
        <script type="text/javascript" src="js/orderInspectionImg.js"></script>
       
    </head>
    <body>
        <div class="container">
            <div class="row">
                <ul id="GalleryImages" class="gallery">

                </ul>  
            </div>
        </div>
        
        <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/1.3.3/less.min.js" type="text/javascript"></script>
    </body>
   
</html>

