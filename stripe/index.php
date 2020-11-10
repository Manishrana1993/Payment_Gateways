<?php require_once('./config.php');
$createTime = gmdate("Y-m-d\TH:i:s\Z");
?>

<form action="StripeResponse.php" method="post">
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $stripe['publishable_key']; ?>"
          data-description="Stripe payment"
          data-amount="5000"
          data-locale="auto"></script>
</form>
