<!doctype html>
<html>
<head>

</head>
<body>
<div>
    <div style="width:90%;background-color:#fff;border:1px solid #c3c3c3;padding:26px;font-weight:lighter">
    
        <div>
            <img src="http://qyresearch.us/wp-content/uploads/2017/03/qyresearch-logo-1.png"
                 style="margin-bottom:16px"></div>
        <br>
        <div>
            <span style="font-size:16px;color:#1b588d">Dear {{ $lead->meta->customername }},</span><br>
            <p>
            <span style="color:#1b588d;font-size:16px">
            Thank you for contacting us.
            </span> <br><br>
                <span style="color:#1b588d;font-size:16px">We got your sample request/inquiry for <strong>
                        <a href="{{ $lead->report_url }}"
                           target="_blank">
                            {{ $lead->report_url }}
                        </a>
                    </strong>. Our representative will contact you soon.
                </span>
                <br>
            </p>
            <span style="font-size:16px;color:#1b588d">Thank you.</span>
            <hr>
            <div style="font-size:16px;color:#1b127c">
                <span>Warm regards,</span><br>
                James Johnson | Director and Sales Head<br>
                Telephone No: <span style="color:#38b2e5"> +1(857)2390696 </span> <span
                        style="color:#eb22a3"> FREE</span><br>
                QYResearch.us <br>
                Email: <span style="color:#3768cc">
                    <a href="mailto:inquiry@qyresearch.us" target="_blank">
                        inquiry@qyresearch.us
                    </a> | Web: <a href="http://www.qyresearch.us" target="_blank">www.qyreserach.us</a>
                </span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
