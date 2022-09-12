<?php
/**
 * Created by PhpStorm.
 * User: Geethu
 * Date: 4/21/2017
 * Time: 10:23
 */

global $payment;
$reportId = $_SESSION['buyNowData']['reportId'];
$report = $_SESSION['buyNowData']['report'];
$payment = [];
print_r('<pre>');
if (isset($_SESSION['payment'])) {
	$payment = $_SESSION['payment'];
}
//print_r($payment['sale']);die();
//print_r($payment['sale']);die();
function srGetSalesAttrValue($attr)
{
	global $payment;
	if ($attr == 'sales_id' || $attr == 'report_title' || $attr == 'amount') {
		return isset($payment[$attr]) ? $payment[$attr] : '';
	}

	return isset($payment['sale'][$attr]) ? $payment['sale'][$attr] : '';
}

?>
<div><label>Redirecting .....</label></div>
<form id="checkout_paymentForm" action='https://www.2checkout.com/checkout/purchase' method='post'>
        <input type='hidden' name='sid' value='102943171'/>
        <input type='hidden' name='mode' value='2CO'/>
        <input type='hidden' name='li_0_type' value='product'/>
        <input type='hidden' name='li_0_name' value="<?php echo srGetSalesAttrValue('report_title') ?>">
        <input type='hidden' name='li_0_price' value="<?php echo srGetSalesAttrValue('amount') ?>">
        <input type='hidden' name='card_holder_name' value=''/>
        <input type='hidden' name='street_address' value='<?php echo srGetSalesAttrValue('address') ?>'/>
        <input type='hidden' name='street_address2' value=''/>
        <input type='hidden' name='city' value="<?php echo srGetSalesAttrValue('city') ?>"/>
        <input type='hidden' name='state' value="<?php echo srGetSalesAttrValue('state') ?>"/>
        <input type='hidden' name='zip'  value=''/>
        <input type='hidden' name='country' value="<?php echo srGetSalesAttrValue('country') ?>"/>
        <input type='hidden' name='email' value="<?php echo srGetSalesAttrValue('email') ?>"/>
        <input type='hidden' name='phone' value="<?php echo srGetSalesAttrValue('contact_number') ?>"/>
        <input type='hidden' name='currency_code'  value='USD'/>
        <input type='hidden' name='first_name' value="<?php echo srGetSalesAttrValue('first_name') ?>"/>
        <input type='hidden' name='last_name' value="<?php echo srGetSalesAttrValue('last_name') ?>"/>
        <input type='hidden' name='card_holder_name' value=''/>
        <input type="hidden" name="custom">
    </form>
<script>
//    document.getElementById('checkout_paymentForm').submit();
</script>