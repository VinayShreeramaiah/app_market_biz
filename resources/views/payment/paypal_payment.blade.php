<?php
/**
 * Created by PhpStorm.
 * User: Geethu
 * Date: 4/18/2017
 * Time: 11:14
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
<!--<form id="paypal_submit_form" method="POST" action="https://www.paypal.com/cgi-bin/webscr">-->
<div><label>Redirecting .....</label></div>
<form id="paypal_submit_form" method="POST" action="<?php echo env('PAYPAL_URL'); ?>/cgi-bin/webscr">
<input type="hidden" name="return"
    value="<?php echo env('APP_URL'); ?>/thank-you-for-purchasing/?reportId=<?php echo $reportId; ?>">
    <input type="hidden" name="notify_url"
           value="<?php echo env('APP_URL'); ?>/app/paypal-ipn?sales_id=<?php echo srGetSalesAttrValue('sales_id') ?>">
    <input type="hidden" name="business" value="<?php echo env('PAYPAL_EMAIL'); ?>">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="upload" value="1">
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="charset" value="utf-8">
    <input type="hidden" name="bn" value="TipsandTricks_SP">
    <input type="hidden" name="item_name" value="<?php echo srGetSalesAttrValue('report_title') ?>">
    <input type="hidden" name="amount" value="<?php echo srGetSalesAttrValue('amount') ?>">
    <input type="hidden" name="quantity" value="1">
    <input type="hidden" name="item_number" value="">
    <input type="hidden" name="shipping" value="0.00">
    <input type="hidden" name="custom">
    <input type="hidden" name="first_name" value="<?php echo srGetSalesAttrValue('first_name') ?>"/>
    <input type="hidden" name="last_name" value="<?php echo srGetSalesAttrValue('last_name') ?>"/>
    <input type="hidden" name="address1" value="<?php echo srGetSalesAttrValue('address') ?>"/>
    <input type="hidden" name="phone" value="<?php echo srGetSalesAttrValue('contact_number') ?>"/>
    <input type="hidden" name="address2" value=""/>
    <input type="hidden" name="city" value="<?php echo srGetSalesAttrValue('city') ?>"/>
    <input type="hidden" name="state" value="<?php echo srGetSalesAttrValue('state') ?>"/>
    <input type="hidden" name="zip" value=""/>
    <input type="hidden" name="country" value="<?php echo srGetSalesAttrValue('country') ?>"/>
    <input type="hidden" name="email" value="<?php echo srGetSalesAttrValue('email') ?>"/>
</form>
<script>
    document.getElementById('paypal_submit_form').submit();
</script>