<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta charset="utf-8">
        <title>Bootply.com - Bootstrap3 Wizard w/ FuelUX</title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" type="text/css" rel="stylesheet">
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="shortcut icon" href="/bootstrap/img/favicon.ico">
        <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" type="text/css" rel="stylesheet">



<link href="//fuelcdn.com/fuelux/2.3.1/css/fuelux.css" type="text/css" rel="stylesheet"><link href="//fuelcdn.com/fuelux/2.3/css/fuelux-responsive.css" type="text/css" rel="stylesheet">
<link href="http://www.bootply.com/bootply/themes/metroid/theme.css" type="text/css" rel="stylesheet">



        <!-- CSS code from Bootply.com editor -->
        
        <style type="text/css">
            
        </style>
    </head>
    
    <!-- HTML code from Bootply.com editor -->
    
    <body class="fuelux" >
        
        <div class="well wizard-example">
    <div id="MyWizard" class="wizard">
        <ul class="steps">
            <li data-target="#step1" class="active"><span class="badge badge-info">1</span>Step 1<span class="chevron"></span>
            </li>
            <li data-target="#step2"><span class="badge">2</span>Step 2<span class="chevron"></span>
            </li>
            <li data-target="#step3"><span class="badge">3</span>Step 3<span class="chevron"></span>
            </li>
            <li data-target="#step4"><span class="badge">4</span>Step 4<span class="chevron"></span>
            </li>
            <li data-target="#step5"><span class="badge">5</span>Step 5<span class="chevron"></span>
            </li>
        </ul>
        <div class="actions">
            <button class="btn btn-xs btn-prev" disabled="disabled"><i class="glyphicon glyphicon-arrow-left"></i>Prev</button>
            <button class="btn btn-xs btn-next" data-last="Finish">Next<i class="glyphicon glyphicon-arrow-right"></i></button>
        </div>
    </div>
    <div class="step-content">
        <div class="step-pane active" id="step1">
            <iframe name="orderRequestView" id="orderRequestView" src="https://localhost/apps/orderRequest/" seamless width=100% height="1200"></iframe>
            <h2><i class="glyphicon glyphicon-magic"> </i> &nbsp; Welcome to the Bootstrap Wizard Example.</h2>This is the first step in this wizard example...
            <br>
            <img class="img-polaroid img-responsive" src="//placehold.it/200x150">
            <br>Click 'Next' to continue.</div>
        <div class="step-pane" id="step2">
            <h2>Step 2</h2>Now you are at the 2nd step of this wizard example.
            <br>
        </div>
        <div class="step-pane" id="step3">
             <iframe name="orderRequestView" id="orderRequestView" data-src="https://localhost/apps/orderRequest/" seamless width=100% height="1200" src="about:blank"></iframe>
<!--            <h2>Okay</h2>Now you are at the 3rd step of this wizard example.
            <br>-->
        </div>
        <div class="step-pane" id="step4">
            <h2>Almost Done.</h2>Now you are at the 4th step of this wizard example. Click 'Next' to finish
            up.</div>
        <div class="step-pane" id="step5">
            <h2>Done!</h2>The wizard is complete. Pretty exciting stuff, eh?. <a href="#" id="btnStep2">Go back to step 2.</a>
        </div>
    </div>
    <br>
    <input type="button" class="btn btn-default" id="btnWizardPrev" value="Back">
    <input type="button" class="btn btn-primary" id="btnWizardNext" value="Next">
     <input type="button" class="btn btn-primary" id="btnWizardStep" value="Nexttt">
</div>
<!-- /well -->
<hr>
        
        <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type='text/javascript' src="//fuelcdn.com/fuelux/2.3.1/loader.min.js"></script>

        <script type='text/javascript' src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>



        
        <!-- JavaScript jQuery code from Bootply.com editor -->
        
        <script type='text/javascript'>
        
        $(document).ready(function() {
        
            $('#MyWizard').on('change', function(e, data) {
  console.log('change');
  alert(data);
  alert(data.step);
  if(data.step===3 && data.direction==='next') {
      alert("data 3 and the direction is next")
    // return e.preventDefault();
  }
});
$('#MyWizard').on('changed', function(e, data) {
  //alert("data20 "+data.step);
  alert("data21 "+data);
 
  console.log('changed');
});
$('#MyWizard').on('finished', function(e, data) {
  console.log('finished');
});
$('#btnWizardPrev').on('click', function() {
  $('#MyWizard').wizard('previous');
});
$('#btnWizardNext').on('click', function() {
  //$('#MyWizard').wizard('next','foo');
  alert("next step");
  $('#MyWizard').wizard('next');
});
$('#btnWizardStep').on('click', function() {
  alert("something btnWizardStep");
  var item = $('#MyWizard').wizard('selectedItem');
  alert("item: "+item);
  alert(item.step);
  console.log(item.step);
});
$('#MyWizard').on('stepclick', function(e, data) {
  alert("stepclick");
  alert("step click dataStep: "+data.step);
  console.log('step' + data.step + ' clicked');
  if(data.step===1) {
    // return e.preventDefault();
  }
});

// optionally navigate back to 2nd step
$('#btnStep2').on('click', function(e, data) {
  $('[data-target=#step2]').trigger("click");
});


        
        });
        
        </script>
        
    </body>
</html>