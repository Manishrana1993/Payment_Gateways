<?php
$createTime = gmdate("Y-m-d\TH:i:s\Z");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PayPal REST API Example</title>
</head>
<body>

    <form class="paypal" action="request.php" method="post" id="paypal_form">
        <input type="hidden" name="item_number" value="123456" / >
        <input type="submit" name="submit" value="Submit Payment"/>
    </form>

</body>
</html>
