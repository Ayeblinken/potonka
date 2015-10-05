<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        
    </body>
</html>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Grid Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

  </head>

  <body>
    <div class="container">

      <div class="page-header">
        <?php if(isset($noOrderID)) echo '<p  style="font-family:arial;color:red;font-size:20px;">'.$noOrderID.'</p><br/>'; ?>
        <?php if(isset($orderIDNotValid)) echo '<p  style="font-family:arial;color:red;font-size:20px;">'.$orderIDNotValid.'</p><br/>'; ?>
        <?php if(isset($reasonDeleteFailed)) echo '<p  style="font-family:arial;color:red;font-size:20px;">'.$reasonDeleteFailed.'</p><br/>'; ?>
<!--          <ul>
              <?php if(isset($orderItemOrderItemComponentResult['noOrderItems'])) echo '<li><p  style="font-family:arial;color:red;font-size:20px;">'.$orderItemOrderItemComponentResult['noOrderItems'].'</p></li>'; ?>
              <?php if(isset($orderShipResult['noOrderShip'])) echo '<li><p  style="font-family:arial;color:red;font-size:20px;">'.$orderShipResult['noOrderShip'].'</p></li>'; ?>
              <?php if(isset($otherChargeResult['noOtherCharge'])) echo '<li><p  style="font-family:arial;color:red;font-size:20px;">'.$otherChargeResult['noOtherCharge'].'</p></li>'; ?>
              <?php if(isset($orderContactResult['noOrderContact'])) echo '<li><p  style="font-family:arial;color:red;font-size:20px;">'.$orderContactResult['noOrderContact'].'</p></li>'; ?> 
          </ul>-->
<!--          
          <?php if(isset($orderItemOrderItemComponentResult['orderItemOrderItemComponentDeleteInfo'])) echo '<p  style="font-family:arial;color:green;font-size:20px;">'.$orderItemOrderItemComponentResult['orderItemOrderItemComponentDeleteInfo'].'</p><br/>'; ?> 
          <?php if(isset($orderShipResult['orderShipDeleteInfo'])) echo '<p  style="font-family:arial;color:green;font-size:20px;">'.$orderShipResult['orderShipDeleteInfo'].'</p><br/>'; ?> 
          <?php if(isset($otherChargeResult['orderChargeDeleteInfo'])) echo '<p  style="font-family:arial;color:green;font-size:20px;">'.$otherChargeResult['orderChargeDeleteInfo'].'</p><br/>'; ?> 
          <?php if(isset($orderContactResult['orderContactsDeleteInfo'])) echo '<p  style="font-family:arial;color:green;font-size:20px;">'.$orderContactResult['orderContactsDeleteInfo'].'</p><br/>'; ?> 
          -->
          <?php if(isset($orderDeleteResult['orderDeleteInfo'])) echo '<p  style="font-family:arial;color:green;font-size:20px;">'.$orderDeleteResult['orderDeleteInfo'].'</p><br/>'; ?> 
      </div>
       
        <p>For Additional Info please email <a target='_blank' href='mailto:dev@indyimaging?Subject=Help Deleting <?php if($orderIDEmail) echo $orderIDEmail;?> Job #'>dev@indyimaging.com</a></p>


    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>


