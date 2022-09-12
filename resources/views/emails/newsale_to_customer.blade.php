<!doctype html>
<html>
<head>

</head>
<body>
<div>
    <div style="width:90%;background-color:#fff;border:1px solid #c3c3c3;padding:26px;font-family:sans-serif;font-weight:lighter">
        {{--<div>--}}
        {{--<img src="http://qyresearch.us/wp-content/uploads/2017/03/qyresearch-logo-1.png"--}}
        {{--style="margin-bottom:16px">--}}
        {{--</div>--}}
        <div>
            <span style="font-size:16px;color:#1b588d">Dear {{$sale->meta->first_name}},</span><br>
            @if(isset($payment_type) && $payment_type == 'WireTransfer')
                <p>
                <span style="color:#1b588d;font-size:16px">Thank you for initiating the payment for the report 
                    <strong>
                        <a href="http://qyresearch.us/report/{{ $report->post_name }}/"
                           target="_blank">
                            {{ $report->meta->full_title }}
                        </a>
                    </strong>
                    Report. Your form has been submitted successfully. Our representative will contact you shortly with Bank details for the further process.
                </span>
                    <br>
                </p>
            @elseif(isset($payment_type) && $payment_type == 'PayPal')
                <p>
                <span style="color:#1b588d;font-size:16px">Thank you for purchasing the report 
                    <strong>
                        <a href="http://qyresearch.us/report/{{ $report->post_name }}/"
                           target="_blank">
                            {{ $report->meta->full_title }}
                        </a>
                    </strong>
                    Report. Your payment is under review. Our representative will contact you soon for further process.
                </span>
                    <br>
                </p>
            @else
                <p>
                <span style="color:#1b588d;font-size:16px">Thank you for purchasing the report 
                    <strong>
                        <a href="http://qyresearch.us/report/{{ $report->post_name }}/"
                           target="_blank">
                            {{ $report->meta->full_title }}
                        </a>
                    </strong>
                    Report.
                </span>
                    <br>
                </p>
            @endif

            <span style="font-size:16px;color:#1b588d">Thank you.</span>
            <hr>
            <div style="font-size:16px;color:#1b127c">
                <span>Thanks & Regards,</span><br>
                Lawrence John | Manager – Business Development<br>
                Telephone No: <span style="color:#38b2e5"> +1(857)2390696 </span>
                <br>
                <br>
                Email: <span style="color:#3768cc">
                    <a href="mailto:lawrence@market.biz" target="_blank">
                        lawrence@market.biz
                    </a> | <a href="mailto:sales@market.biz" target="_blank">
                        sales@market.biz
                    </a> | Web: <a href="http://www.marketresearch.biz" target="_blank">www.marketresearch.biz</a>
                </span>
                <br><br>
                Product of <img src="<?php echo $themeBaseUrl;?>/images/prudour.png" alt="PRUDOUR" width="130"/>
                Network.
            </div>

        </div>
    </div>
<!--
<div>
Report : &nbsp;{{ $sale->meta->reportId }} - {{ $report->meta->full_title }} <br/>
First name : &nbsp;{{$sale->meta->first_name}}<br/>
Last name : &nbsp;{{$sale->meta->last_name}}<br/>
Email : &nbsp;{{$sale->meta->email}}<br/>
Phone : &nbsp;{{$sale->meta->phone}}<br/>
Designation : &nbsp;{{$sale->meta->designation}}<br/>
Company : &nbsp;{{$sale->meta->company}}<br/>
Address : &nbsp;{{$sale->meta->address}}<br/>
City : &nbsp;{{$sale->meta->city}}<br/>
State : &nbsp;{{$sale->meta->state}}<br/>
Country : &nbsp;{{$sale->meta->country}}<br/>
Payment method : &nbsp;{{$sale->meta->payment_method}}<br/>
License type : &nbsp;{{$sale->meta->license_type}}<br/>
Financial status : &nbsp;{{$sale->meta->financial_status}}<br/>
</div>
-->
</div>

</body>
</html>
