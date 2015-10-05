<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta charset="utf-8">
        <title><?php if(!empty($orderID)) echo $orderID." - "; ?> Sales Order</title>
        <base href="<?php echo base_url(); ?>" />
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="no-cache">
        <meta http-equiv="Expires" content="-1">
        <meta http-equiv="Cache-Control" content="no-cache">
        
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" type="text/css" rel="stylesheet">
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
        
<!--        <link rel="shortcut icon" href="/bootstrap/img/favicon.ico">
        <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">-->
        
        <link href="font-awesome/3.2.1/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<!--        <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" type="text/css" rel="stylesheet">-->
        
<!--        <link href="//www.fuelcdn.com/fuelux/2.4.1/css/fuelux.min.css" type="text/css" rel="stylesheet">-->
       
        <link href="fuelux/css/fuelux.min.css" type="text/css" rel="stylesheet">
        <link href="fuelux/css/fuelux-responsive.css" type="text/css" rel="stylesheet">
        
<!--        <link href="//fuelcdn.com/fuelux/2.3.1/css/fuelux.css" type="text/css" rel="stylesheet">
        <link href="//fuelcdn.com/fuelux/2.3/css/fuelux-responsive.css" type="text/css" rel="stylesheet">-->
<!--        <link href="http://www.bootply.com/bootply/themes/metroid/theme.css" type="text/css" rel="stylesheet">-->
        <link href="Bootply/themes/metroid/theme.css" type="text/css" rel="stylesheet">

        <!-- CSS code from Bootply.com editor -->
        
        <style type="text/css">
            html {
                   overflow-y: scroll;
            }

        </style>
    </head>
    
    <!-- HTML code from Bootply.com editor -->
    
    <body class="fuelux" >
        
        <div class="well wizard-example">
    <div id="MyWizard" class="wizard">
        <ul class="steps">
            <li data-target="#step1" class="active"><span class="badge badge-info">1</span>Order<span class="chevron"></span>
            </li>
            <li data-target="#step2"><span class="badge">2</span>Terms<span class="chevron"></span>
            </li>
            <li data-target="#step3"><span class="badge">3</span>Notes<span class="chevron"></span>
            </li>
             <li data-target="#step4"><span class="badge">4</span>Contacts<span class="chevron"></span>
            </li>
             <li data-target="#step5"><span class="badge">5</span>LineItems<span class="chevron"></span>
            </li>
             <li data-target="#step6"><span class="badge">6</span>Other Charges<span class="chevron"></span>
            </li>
             <li data-target="#step7"><span class="badge">7</span>Shipping<span class="chevron"></span>
            </li><!--
            <li data-target="#step4"><span class="badge">4</span>Step 4<span class="chevron"></span>
            </li>
            <li data-target="#step5"><span class="badge">5</span>Step 5<span class="chevron"></span>
            </li>-->
        </ul>
        <div class="actions">
            <button class="btn btn-xs btn-prev" disabled="disabled"> <i class="glyphicon glyphicon-arrow-left"></i>Prev</button>
           
<!--            <input type="button" class="btn btn-primary" id="btnWizardNext" value="Next">-->
<!--            <button id="btnWizardNext" class="btn btn-xs btn-next" data-last="Finish">Next<i class="glyphicon glyphicon-arrow-right"></i></button>-->
            <button class="btn btn-xs btn-next" data-last="Finish">Next<i class="glyphicon glyphicon-arrow-right"></i></button>
           
            
           
        </div>
    </div>
    <div class="step-content">
        <div class="step-pane active" id="step1">
            <?php if(!empty($orderID)) 
            {?>
                 <iframe name="orderRequestView" id="orderRequestView" src=<?php echo 'https://localhost/apps/orderRequest/'.$orderID ?> seamless width=100% height="1200"></iframe>
               
           <?php 
            }
            else 
            {?>
                <iframe name="orderRequestView" id="orderRequestView" src="https://localhost/apps/orderRequest/" seamless width=100% height="1200"></iframe>
            <?php
            }
            ?>
<!--           <iframe name="orderRequestView" id="orderRequestView" src="https://localhost/apps/orderRequest/" seamless width=100% height="1200"></iframe>-->
<!--           <iframe name="orderRequestView" id="orderRequestView" data-src="https://localhost/apps/orderRequest/" seamless width=100% height="1200" src="about:blank"></iframe>-->
        </div>
        <div class="step-pane" id="step2">
<!--             <iframe name="customerTermView" id="customerTermView" data-src=<?php //echo "https://localhost/apps/customerTerm/".$customerID."#sales";?> seamless width=100% height="1200" src="about:blank"></iframe>-->
<!--                 <iframe name="customerTermView" id="customerTermView" src="https://localhost/apps/customerTerm/3230#sales" seamless width=100% height="1200" src="about:blank"></iframe>-->
                 <iframe name="customerTermView" id="customerTermView" seamless width=100% height="1200" src="about:blank"></iframe>
<!--            <br>-->
        </div>
        <div class="step-pane" id="step3">
             <iframe name="notesView" id="notesView" seamless width=100% height="1200" src="about:blank"></iframe>
           
        </div>
         <div class="step-pane" id="step4">
             <iframe name="orderContactsView" id="orderContactsView" seamless width=100% height="1200" src="about:blank"></iframe>
           
        </div>
         <div class="step-pane" id="step5">
             <iframe name="templateListView" id="templateListView" seamless width=100% height="1200" src="about:blank"></iframe>
           
        </div>
        <div class="step-pane" id="step6">
           <iframe name="otherChargesView" id="otherChargesView" seamless width=100% height="1200" src="about:blank"></iframe>
        </div>
        <div class="step-pane" id="step7">
            <iframe name="orderShippingView" id="orderShippingView" seamless width=100% height="1200" src="about:blank"></iframe>
        </div>
    </div>
    <br>
<!--    <input type="button" class="btn btn-default" id="btnWizardPrev" value="Back">
    <input type="button" class="btn btn-primary" id="btnWizardNext" value="Next">-->
</div>
<!-- /well -->
<hr>
        
        <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type='text/javascript' src="//fuelcdn.com/fuelux/2.3.1/loader.min.js"></script>

        <script type='text/javascript' src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>



        
        <!-- JavaScript jQuery code from Bootply.com editor -->
        
        <script type='text/javascript'>
 var getCustomerIDUrl     = "orders/ordercontroller/getOrderFieldsFromOrderIDInJsonFormat";
 
 var finalFinishStepsUrl  = "orders/ordercontroller/stepsFinalFinish";
 //var result               = "";
 //document.body.style.overflow = 'hidden';
$(document).ready(function() {
//    var currentState = history.state;
//    alert(currentState.module);
//    
//    $(window).bind("popstate", function(evt) {
//        var state = evt.originalEvent.state;
//        if (state && state.module === "leave") {
//          //$.getScript(location.href);
//          window.location.reload(true);
//        }
//    });
    
//    var i= 0;
//    if(currentState == "back")
//    {
//        //i++;
//        alert("hi "+ i);
//        replaceState(null);
//        //window.location.href("salesOrderRequest/"+$("#iframeOrderID").val());
//        
//    }
    //alert("hi outside "+ i);
//   var iframe = $("#orderRequestView");
//       iframe.attr("src", iframe.data("src"));     
   if($("#orderStepsCompleteHidden").val())
   {
       $("ul.steps li").addClass( "complete");
       
       $("ul.steps li").find('span:first').map(function(){
           if(!$(this).hasClass( "badge-info"))
           {
               $(this).addClass( "badge-success");
           }   
       }).get();
   } 
   $('#MyWizard').on('change', function(e, data) {
      //alert("hi First");
      //console.log('change');
      
      if(data.step===1 && data.direction==='next') 
      {
          //alert("hi");
           $(".btn-next").attr("disabled", "disabled");
           var result = document.getElementById('orderRequestView').contentWindow.$("#createNewSalesOrder").triggerHandler("click");
           //alert(result);
           if(result == "false")
           {
               return e.preventDefault();
                
           }
           else
           {
                //result = document.getElementById('orderRequestView').contentWindow.$("#createNewSalesOrder").triggerHandler("click");
                //alert("IFRAME "+result);
                getCustomerID(result);
       
                //alert("hIDDEN VALUE INSIDE IFRAME "+$("#iframeCustomerID").val());
                //alert('https://localhost/apps/customerTerm/'+$("#iframeCustomerID").val()+'#sales');
                var iframe = $("#customerTermView");
                //alert($("#iframeCustomerID").val());
                iframe.attr("src",'https://localhost/apps/customerTerm/'+$("#iframeCustomerID").val()+'/'+$("#iframeOrderID").val()+'/'+'#sales');
                
                $('title').text($("#iframeOrderID").val());
//                iframe.attr("src",'https://localhost/apps/customerTerm/'+$("#iframeCustomerID").val()+'#sales').on('load', function() {
//                                $('body').scrollTop(0);
//                                $(this).show();
//                });
                //$("#step2").html('<iframe name="customerTermView" id="customerTermView" src=https://localhost/apps/customerTerm/'+$("#iframeCustomerID").val()+'#sales;seamless width=100% height="1200"></iframe>')
                setTimeout(function(){$(".btn-next").removeAttr("disabled")},5000)

           }    
        
      }
      if(data.step===2 && data.direction==='next') 
      {
            //alert("step2");
            var iframeTemplateListView = $("#notesView");
            //alert("step3"+$("#iframeOrderID").val());
            iframeTemplateListView.attr("src",'https://localhost/apps/orderNotes/'+$("#iframeOrderID").val()+'/customerOrderView');
            //alert("step2.2");
      
      }
      if(data.step===3 && data.direction==='next') 
      {
            //alert("step2");
            var iframeTemplateListView = $("#orderContactsView");
            //alert("step4"+$("#iframeOrderID").val());
            iframeTemplateListView.attr("src",'https://localhost/apps/orderContacts/'+$("#iframeOrderID").val());
            //alert("step2.2");
      
      }
      if(data.step===4 && data.direction==='next') 
      {
            //alert("step2");
            var iframeTemplateListView = $("#templateListView");
            //alert("step2.1"+$("#iframeOrderID").val());
            iframeTemplateListView.attr("src",'https://localhost/apps/loadTemplateList/'+$("#iframeOrderID").val());
            //alert("step2.2");
      
      }
      if(data.step===5 && data.direction==='next') 
      {
            //alert("step2");
            var iframeTemplateListView = $("#otherChargesView");
            //alert("step2.1"+$("#iframeOrderID").val());
            iframeTemplateListView.attr("src",'https://localhost/apps/otherCharges/'+$("#iframeOrderID").val());
            //alert("step2.2");
      
      }
      if(data.step===6 && data.direction==='next') 
      {
            //alert("step2");
            var iframeTemplateListView = $("#orderShippingView");
            //alert("step2.1"+$("#iframeOrderID").val());
            iframeTemplateListView.attr("src",'https://localhost/angularProject/orderShip/#/'+$("#iframeOrderID").val());
            //iframeTemplateListView.attr("src",'https://localhost/apps/orderShip/'+$("#iframeOrderID").val());
            //alert("step2.2");
      
      }
     
   });
   $('#MyWizard').on('changed', function(e, data) {
       //alert("hii+ Second changed");
       if($("#orderStepsCompleteHidden").val())
       {
           //alert("change");
           $("ul.steps li").addClass( "complete");
           
           $("ul.steps li").find('span:first').map(function(){
               if(!$(this).hasClass( "badge-info"))
               {
                   $(this).addClass( "badge-success");
               }    
              
           }).get();
           
       } 
//       for(var strName in e)
//       {
//           var strValue = e[strName] ;
//           alert("name : " + strName + " : value : " + strValue) ;
//  
//                   
//       }
    });
    $('#MyWizard').on('finished', function(e, data) {
       //alert("hii +Finished");
       // do an ajax call to update the order table
       if(!$("#orderStepsCompleteHidden").val() == "1")
       {
           //$("#orderStepsCompleteHidden").val("1");
           $.ajax({
                url: finalFinishStepsUrl,
                type: 'POST',
                //dataType: 'json',
                error: function(xhr,status,error){
                      alert("Please Contact IT (order Tbl Insert Error): "+ xhr.status+"-"+error);
                },
                async: false,
                data: {orderID :$("#iframeOrderID").val(),orderStepsComplete :1},
                success: function( response ){
                    if(response == "updateDone")
                    {

                    }
                    else
                    {
                        //alert("New OrderID: "+$("#newOrderIDHidden").val());
                        //$("#newOrderIDHidden").val(response);
                        //alert("response from ajax : "+response);

                    }     
                }
            });
           
       }    
       
       
       window.open("http://jasper.indyimaging.com:8080/jasperserver/flow.html?_flowId=viewReportFlow&standAlone=true&_flowId=viewReportFlow&ParentFolderUri=%2Freports%2FindyImagingReports%2FOrderRequest&reportUnit=%2Freports%2FindyImagingReports%2FOrderRequest%2FOrderRequest&j_username=joeuser&j_password=joeuser&OrderID="+$("#iframeOrderID").val()+"&output=pdf",'_self')
       //window.open ("http://jasper.indyimaging.com:8080/jasperserver/flow.html?_flowId=viewReportFlow&standAlone=true&_flowId=viewReportFlow&ParentFolderUri=%2Freports%2FindyImagingReports&reportUnit=%2Freports%2FindyImagingReports%2FpartialTicketMainReport&j_username=joeuser&j_password=joeuser&OrderItemID="+$("#orderItemIDHidden").val()+"&output=pdf",'_blank');
       if (window.history && window.history.pushState) 
       {
           window.history.pushState({module:"leave"}, null, './salesOrderRequest/'+$("#iframeOrderID").val());
           //window.location.reload(true);
       }    
       //console.log('finished');
    });
    $('#btnWizardPrev').on('click', function(e, data) {
       alert("hii previous+");
       //if(data.step===1 && data.direction==='next') 
      $('#MyWizard').wizard('previous');
    });
    $('#btnWizardNext').on('click', function(e, data) {
       alert("hii +next submit1");
       //alert(data);
       //alert(data.step);
       //frames['orderRequestView'].location.href='javascript:document.forms[0].submit()'
       //window.frames['orderRequestView'].document.forms['internalSalesOrderFrm'].valid();
       //window.frames['orderRequestView'].document.forms['internalSalesOrderFrm'].submit();
       //orderRequestView.document.buttons['internalSalesOrderFrm'].submit();
       //orderRequestView.document.buttons['#createNewSalesOrder'].trigger("click");
       //document.getElementById('orderRequestView').contentWindow.find("#createNewSalesOrder").trigger("click");
       result = document.getElementById('orderRequestView').contentWindow.$("#createNewSalesOrder").triggerHandler("click");
       //alert("IFRAME "+result);
       getCustomerID(result);
       
       alert("hIDDEN VALUE INSIDE IFRAME "+$("#iframeCustomerID").val());
       
       $("#step2").html('<iframe name="customerTermView" id="customerTermView" src="https://localhost/apps/customerTerm/"'+$("#iframeCustomerID").val()+'#sales;seamless width=100% height="1200"></iframe>')
       
       alert("ihii");  
       //$("#step2").addClass("complete");
       $('[data-target=#step2]').trigger("click");  
       //alert("ihii2");

    //   for(var strName in result)
    //   {
    //       var strValue = result[strName] ;
    //       alert("name : " + strName + " : value : " + strValue) ;
    //       for(var strN in strValue)
    //       {
    //           var strV = result[strN] ;
    //           alert("name : " + strN + " : value : " + strV) ;
    //
    //       }    
    //               
    //   }
       //orderRequestView.document.forms['internalSalesOrderFrm'].validateForm();

       //event.PreventDefault();
       //$('#MyWizard').wizard('next','foo');
    });
    $('#btnWizardStep').on('click', function() {
       alert("hii+ step");
       var item = $('#MyWizard').wizard('selectedItem');
       //console.log(item.step);
    });
    $('#MyWizard').on('stepclick', function(e, data) {
       //alert("hii +stepclick");
       //console.log('step' + data.step + ' clicked');
       if(data.step===1) 
       {
           //alert("src");
           var iframe = $("#orderRequestView");
           iframe.attr("src", iframe.data("src")); 
        // return e.preventDefault();
       }
       if(data.step===2)
       {
            var iframe = $("#customerTermView");
            //alert($("#iframeCustomerID").val());
            iframe.attr("src",'https://localhost/apps/customerTerm/'+$("#iframeCustomerID").val()+'#sales');
           
       }    
       if(data.step===3)
       {
           var iframeTemplateListView = $("#notesView");
           //alert("step3"+$("#iframeOrderID").val());
           iframeTemplateListView.attr("src",'https://localhost/apps/orderNotes/'+$("#iframeOrderID").val()+'/customerOrderView');
            //alert("step2.2");
           
           
       }    
       if(data.step===4)
       {
           //alert("step2");
            var iframeTemplateListView = $("#orderContactsView");
            //alert("step4"+$("#iframeOrderID").val());
            iframeTemplateListView.attr("src",'https://localhost/apps/orderContacts/'+$("#iframeOrderID").val());
            //alert("step2.2");
           
       }    
       if(data.step===5) 
       {
          //alert("src");
          var iframeTemplateListView = $("#templateListView");
          //alert("step2.1"+$("#iframeOrderID").val());
          iframeTemplateListView.attr("src",'https://localhost/apps/loadTemplateList/'+$("#iframeOrderID").val());
        // return e.preventDefault();
       }
       if(data.step===6) 
       {
            //alert("step2");
            var iframeTemplateListView = $("#otherChargesView");
            //alert("step2.1"+$("#iframeOrderID").val());
            iframeTemplateListView.attr("src",'https://localhost/apps/otherCharges/'+$("#iframeOrderID").val());
            //alert("step2.2");
           
       }
       if(data.step===7)
       {
           var iframeTemplateListView = $("#orderShippingView");
           //alert("step2.1"+$("#iframeOrderID").val());
           iframeTemplateListView.attr("src",'https://localhost/angularProject/orderShip/#/'+$("#iframeOrderID").val());
           //iframeTemplateListView.attr("src",'https://localhost/apps/orderShip/'+$("#iframeOrderID").val());
       }    
    });

    // optionally navigate back to 2nd step
    $('#btnStep2').on('click', function(e, data) {
        //alert("hi +step2");
      $('[data-target=#step2]').trigger("click");
    });


   function getCustomerID(){
       result = arguments[0];
       $.ajax({
             url: getCustomerIDUrl+'/'+result,
             type: 'POST',
              dataType: 'json',
              error: function(xhr,status,error){
                   alert("Please Contact IT (ArtLocation ajax Error): "+ xhr.status+"-"+error);
              },
              async:false,
              success: function( response ){
                    //alert(response.kp_OrderID); 
                    $("#iframeOrderID").val(response.kp_OrderID);
                    $("#iframeCustomerID").val(response.kf_CustomerID);
              }
         });
   } 
   
//   $(window).on("hashchange", function(e) {
//        e.preventDefault();
//   });
   
   
   
   
});
        
        </script>
        <input type="hidden" name="iframeOrderID"                       id="iframeOrderID"              value="<?php if(!empty($orderID)) echo $orderID; ?>">
        <input type="hidden" name="orderStepsCompleteHidden"            id="orderStepsCompleteHidden"   value="<?php if(!empty($orderStepsComplete)) echo $orderStepsComplete; ?>">
<!--        <input type="hidden" name="orderStepsCompleteCustomerIDHidden"  id="orderStepsCompleteCustomerIDHidden" value="<?php if(!empty($orderStepsCompleteCustomerIDHidden)) echo $orderStepsCompleteCustomerIDHidden; ?>">-->
        <input type="hidden" name="iframeCustomerID" id="iframeCustomerID" value="<?php if(!empty($customerID)) echo $customerID; ?>">
    </body>
</html>