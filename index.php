<?php
//require_once('paypal/config.php');


?>
<!DOCTYPE html>
<html>
<body>
  <p>
  <form class="stripe" action="stripe/index.php" method="post" id="stripe">
    <input type="hidden" name="item" value="12" / >
    <input type="submit" name="submit1" value="Pay via Stripe"/>
</form>
</p>
<p>
<form class="paypal" action="paypal/index.php" method="post" id="paypal_form">
    <input type="hidden" name="item_number" value="123456" / >
    <input type="submit" name="submit" value="Pay via Paypal"/>
</form>
</p>
</body>
</html>
