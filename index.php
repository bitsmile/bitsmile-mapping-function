<?php
/*
 * Created on Jan 9, 2016
 *
 * sakr
 */
require_once('includes/app.php');
$response=array();
if($_SERVER['REQUEST_METHOD']=='POST'){ 
	$response=createDonation($_POST['inputAmount'],$_POST['inputName'],$_POST['inputEmail']);
	// $response['success']=1;
	// $response['pubKey']='BNjihWGGrEZ4T1d4vRU7NiinGdaQr5R6iW';
	// $response['amount']='2';
	// $response['txid']='505ef86e512c392802e2179332635c4e415d8bf65e982a662ff9c50227875ad7';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?=BITSMILE_TITLE?></title>

    <!-- Bootstrap -->
    <link href="includes/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="includes/app.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="includes/bootstrap/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="includes/bootstrap/js/bootstrap.min.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href=""><?=BITSMILE_TITLE_DEMO?></a>
        </div>       
      </div>
    </nav>
 	<div class="container theme-showcase" role="main">
        <img class="center-block" src="images/logo.png">
        <h3 class="center-block text-center"><?=TEXT_DEMO_INTRO1?></h3>    
        <div class="row">
  	  <div class="col-sm-4"></div>
      <div class="col-sm-4">
      <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title"><?=TEXT_FORM_TITLE?></h3>
            </div>
            <div class="panel-body">
              <p class="center-block text-center"><?=TEXT_DEMO_INTRO2?></p>
            <form method="post" action="" id="donateFrm">
  				<div class="form-group">
    			<label class="sr-only" for="inputAmount"><?=TEXT_AMOUNT?></label>
    			<div class="input-group">
      			<div class="input-group-addon">¥</div>
      			<input type="number" class="form-control" id="inputAmount" name="inputAmount" min="1" max="10000" placeholder="<?=TEXT_AMOUNT?>" required>
      			<div class="input-group-addon">.00</div>
    			</div>
  				</div>
  				<div class="form-group">
    			<label class="sr-only" for="inputName"><?=TEXT_NAME?>(<?=TEXT_NAME?>)</label>
    			<input type="text" class="form-control" id="inputName" name="inputName" placeholder="<?=TEXT_NAME.' ('.TEXT_OPTIONAL.')'?>">
  				</div>
  				<div class="form-group">
    			<label class="sr-only" for="inputEmail"><?=TEXT_EMAIL?>(<?=TEXT_OPTIONAL?>)</label>
    			<input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="<?=TEXT_EMAIL.' ('.TEXT_OPTIONAL.')'?>">
  				</div>
  				<div class="form-group center-block text-center">
  				<label class="radio-inline">
 				<input type="radio" name="paymentOption" id="inlineRadio1" value="bitcoin.png" required><img src="images/bitcoin.png">
				</label>
				<label class="radio-inline">
 				<input type="radio" name="paymentOption" id="inlineRadio2" value="paypal.png"><img src="images/paypal.png">
				</label>
				</div>
				<div class="form-group center-block text-center">
				<label class="radio-inline">
  				<input  type="radio" name="paymentOption" id="inlineRadio3" value="boa.png"><img src="images/boa.png">
				</label>
				<label class="radio-inline">
  				<input type="radio" name="paymentOption" id="inlineRadio4" value="citibank.png"><img src="images/citibank.png">
				</label>
				</div>
  				<button type="button" class="btn btn-lg btn-success center-block" onclick="if($('#donateFrm')[0].checkValidity()){;$('#donationAmt').html($('#inputAmount').val()+' ¥');$('#imgPayment').attr('src','images/'+$('input[name=\'paymentOption\']:checked').val());$('#donateModal').modal('show');}else{$('#inputModal').modal('show');}"><?=TEXT_SUBMIT?></button>
  				<div class="modal fade" tabindex="-1" role="dialog" id="donateModal">
 					<div class="modal-dialog">
   						 <div class="modal-content">
      						<div class="modal-header modal-header-donate">
       					 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       					 <h4 class="modal-title"><?=TEXT_DONATE_CONFIRM?></h4>
      					</div>
      					<div class="modal-body">
        				<p><?=TEXT_DONATE_CONFIRM_DESC?></p>
        				<div class="row" class="smileInfo">
        				<div class="col-xs-4"><h4><span><?=TEXT_PAYMENT_METHOD?></span></h4></div>
        				<div class="col-xs-8"><img id="imgPayment" src="images/bitcoin.png"></div>
     					</div>
     					<div class="row" class="smileInfo">
        				<div class="col-xs-4"><h4><span><?=TEXT_DONATION_AMOUNT?></span></h4></div>
       					<div class="col-xs-8"><h4><span class="label label-info" id="donationAmt"></span></h4></div>
      					</div>
      					</div>
     					<div class="modal-footer">
        				<button type="button" class="btn btn-default" data-dismiss="modal"><?=TEXT_CLOSE?></button>
        				<button type="submit" class="btn btn-success"><?=TEXT_SUBMIT?></button>
      					</div>
    					</div><!-- /.modal-content -->
  				    </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<div class="modal fade" id="inputModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?=TEXT_ERROR?></h4>
        </div>
        <div class="modal-body">
          <p><?=TEXT_ERROR_INPUT?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
			</form>
            </div>
          </div>
      </div> 
      </div>
      <div class="col-sm-4"></div>        
     </div> 
     <?php
$last=getLast();
?>
     <div class="row">
  	  <div class="col-sm-4"></div>
      <div class="col-sm-4">
      <div class="panel panel-success">
      		<div class="panel-heading">
              <h3 class="panel-title"><?=TEXT_LATEST_TITLE?></h3>
            </div>
            <div class="panel-body">
            <div class="list-group">
          	<?php
foreach($last as $k){
	?>
            <a target="_blank" href="<?=EXPLORER_URL.'/address/'.$k['pubKey']?>" class="list-group-item">
            <div class="row">
        	<div class="col-sm-10"><h6><span><?=$k['pubKey']?></span></h6></div>
       		<div class="col-sm-2"><h6><span class="label label-info"><?=(int)$k['amount'].' ¥'?></span></h6></div>
      		</div>            
            </a>
            <?php
}
?>
          </div>
      </div>   
      </div>    
      <div class="col-sm-4"></div>
    </div>
<div class="modal fade" tabindex="-1" role="dialog" id="successModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header modal-header-success">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?=TEXT_SUCCESS?></h4>
      </div>
      <div class="modal-body">
        <p><?=TEXT_SUCCESS_DESC?></p>
        <p><?=TEXT_NOTE1?></p>
        <p><?=TEXT_NOTE2?></p>
      <div class="row" class="smileInfo">
        <div class="col-xs-4"><h4><span><?=TEXT_SMILE_ADDRESS?></span></h4></div>
        <div class="col-xs-8"><h4><a target="_blank" href="<?=EXPLORER_URL.'/address/'?><?=isset($response['pubKey'])?$response['pubKey']:'';?>"><span class="label label-success"><?=isset($response['pubKey'])?$response['pubKey']:'';?></span></a></h4></div>
      </div>
      <div class="row" class="smileInfo">
        <div class="col-xs-4"><h4><span><?=TEXT_DONATION_AMOUNT?></span></h4></div>
        <div class="col-xs-8"><h4><span class="label label-info"><?=isset($response['amount'])?$response['amount']:'';?><?=' '.TEXT_TICKER?></span></h4></div>
      </div>
        <p class="center-block text-center smileInfo"><?=TEXT_TXID?>&nbsp<a target="_blank" href="<?=EXPLORER_URL.'/tx/'?><?=isset($response['txid'])?$response['txid']:'';?>"><?=isset($response['txid'])?$response['txid']:'';?></a></p>        
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=TEXT_CLOSE?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="errorModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header modal-header-error">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?=TEXT_ERROR?></h4>
      </div>
      <div class="modal-body">
        <p><?=TEXT_ERROR_DESC?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=TEXT_CLOSE?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

   
    <?php
if($_SERVER['REQUEST_METHOD']=='POST'){ 
	if($response['success']==1){
		echo "<script>$('#successModal').modal('show');</script>";	
	}else{
		echo "<script>$('#errorModal').modal('show');</script>";	
	}
}
?>
    
  </body>
</html>