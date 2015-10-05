<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> 
        <title>summernote</title>
         <base href="<?php echo base_url(); ?>" />
        <!-- include jquery -->
         <script src="//code.jquery.com/jquery-1.9.1.min.js"></script> 

        <!-- include libraries BS2 -->
<!--        <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">-->
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="media/css_bootstrap/bootstrap-responsive.css" type="text/css">
<!--        <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script> -->
        <script src="media/js_bootstrap/bootstrap.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" rel="stylesheet">
        
       <link rel="stylesheet" href="media/summernote.css" type="text/css"/>
        
        <!-- include summernote -->
        <link rel="stylesheet/less" type="text/css" href="media/summernote.less" /> 
        <script type="text/javascript" src="media/js_summernote/summernote_2.js"></script>

        <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote({height: 300, focus: true,
                 toolbar: [
                      ['style', ['style']],
                      ['font', ['bold', 'italic', 'underline', 'clear']],
                      ['fontsize', ['fontsize']],
                      ['color', ['color']],
                      ['para', ['ul', 'ol', 'paragraph']],
                      ['height', ['height']],
          //['table', ['table']],
          //['insert', ['link', 'picture']],
          //['fullscreen', ['fullscreen']],
          //['help', ['help']]
                      ]
              });
        });
        </script>
    </head>
    <body>
        <div class="container">
            <div class="summernote"></div>
        </div>
        <script type="text/javascript">
  less = {
    env: "production", // or "production"
    async: false,       // load imports async
    fileAsync: false,   // load imports async when in a page under
    // a file protocol
    poll: 1000,         // when in watch mode, time in ms between polls
    functions: {},      // user functions, keyed by name
    dumpLineNumbers: "comments", // or "mediaQuery" or "all"
    relativeUrls: false,// whether to adjust url's to be relative
    // if false, url's are already relative to the
    // entry less file
    rootpath: ":/a.com/"// a path to add on to the start of every url
    //resource
  };
</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/1.3.3/less.min.js" type="text/javascript"></script>
    </body>
    
</html>
       
