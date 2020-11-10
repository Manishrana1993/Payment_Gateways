
Stripe.setPublishableKey('sk_test_51HYuScCESEr6sBweRExOsyUTbeWubL3wS1IqblQ69GeHGeYBjEBWzuG9xgryyBQHG2EAeCe4cgCV926vdXnkAc5t007atUFfgr');
$(document).ready(function() {
    $("#paymentForm").submit(function(event) {
        $('#makePayment').attr("disabled", "disabled");
        Stripe.createToken({
            number: $('#cardNumber').val(),
            cvc: $('#cardCVC').val(),
            exp_month: $('#cardExpMonth').val(),
            exp_year: $('#cardExpYear').val()
        }, handleStripeResponse);
        return false;
    });
});
function handleStripeResponse(status, response) {
	console.log(JSON.stringify(response));
    if (response.error) {
        $('#makePayment').removeAttr("disabled");
        $(".paymentErrors").html(response.error.message);
    } else {
		var payForm = $("#paymentForm");
        var stripeToken = response['id'];
        payForm.append("<input type='hidden' name='stripeToken' value='" + stripeToken + "' />");
		payForm.get(0).submit();
    }
}