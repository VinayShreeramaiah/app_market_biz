<!doctype html>
<html>
<head>

</head>
<body>
<div>
    <div style="width:90%;background-color:#fff;border:1px solid #c3c3c3;padding:26px;font-family:sans-serif;font-weight:lighter">

        <div>
            <span style="font-size:16px;color:#1b588d">Dear Admin,</span><br>

            <span>{{$sale->meta->first_name}} {{$sale->meta->last_name}}  has initiated the payment Details are as below</span>
            <hr>
            <div style="font-size:16px;color:#1b127c">
                <table>
                    <tr>
                        <th>Report Title</th>
                        <td>{{ $report->meta->full_title}}</td>
                    </tr>
                    <tr>
                        <th>Paid price</th>
                        <td>{{ $sale->meta->amount }}</td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td>{{ $sale->meta->payment_method }} </td>
                    </tr>
                    <tr>
                        <th>Customer Name</th>
                        <td>{{ $sale->meta->message }} </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $sale->meta->email }} </td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $sale->meta->address }}, {{$sale->meta->city}}, {{$sale->meta->state}}</td>
                    </tr>
                    <tr>
                        <th>Country Name</th>
                        <td>{{ $sale->meta->country }} </td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $sale->meta->phone }} </td>
                    </tr>
                    <tr>
                        <th>Company Name</th>
                        <td>{{ $sale->meta->company }} </td>
                    </tr>
                    <tr>
                        <th>Item Ordered</th>
                        <td>{{ $sale->meta->license_type }} </td>
                    </tr>
                    <tr>
                        <th>Publisher</th>
                        <td>{{ $report->meta->publisher }} </td>
                    </tr>
                    <tr>
                        <th>Ip Address</th>
                        <td>{{ $sale->meta->ipAddress }} </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>
