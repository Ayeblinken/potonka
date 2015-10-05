<?php foreach($result as $row):
    //echo $row->kp_InventoryLocationItemID ?> 
    <li> <a href="#editinvitemlocation" data-rel="popup" data-position-to="window" 
            data-inline="true" data-transition="pop" 
            id="<?php echo $row->kp_InventoryLocationItemID.",".$row->kf_InventoryItemID.",".(Float) $row->n_QntyOnHand; ?>">
             <h2> <?php echo $row->kf_InventoryItemID; ?> </h2>
              <P> <?php echo wordwrap($row->t_description, 30, '<br />');?> </p>
             <span class="ui-li-count"><?php echo (Float) $row->n_QntyOnHand; ?></span> 
        </a> 
    </li>
   
<?php endforeach;?> 
    
 