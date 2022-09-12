<!doctype html>
<html>
<head>

</head>
<body>
<table>
    <tr>
        <th>Report</th>
        <td>{{ $sale->meta->reportId }} - {{ $report->meta->full_title }} {{ $sale->meta->is_prebooking ? ' (Pre-booking) ' : ''}}</td>
    </tr>

    <tr>
        <th>First name</th>
        <td>{{ $sale->meta->first_name }}</td>
    </tr>

    <tr>
        <th>Last name</th>
        <td>{{ $sale->meta->last_name }}</td>
    </tr>

    <tr>
        <th>Email</th>
        <td>{{ $sale->meta->email }}</td>
    </tr>

    <tr>
        <th>Phone</th>
        <td>{{ $sale->meta->phone }}</td>
    </tr>

    <tr>
        <th>Designation</th>
        <td>{{ $sale->meta->designation }}</td>
    </tr>

    <tr>
        <th>Company</th>
        <td>{{ $sale->meta->company }}</td>
    </tr>

    <tr>
        <th>Address</th>
        <td>{{ $sale->meta->address }}</td>
    </tr>

    <tr>
        <th>City</th>
        <td>{{ $sale->meta->city }}</td>
    </tr>

    <tr>
        <th>State</th>
        <td>{{ $sale->meta->state }}</td>
    </tr>

    <tr>
        <th>Country</th>
        <td>{{ $sale->meta->country }}</td>
    </tr>

    <tr>
        <th>Payment method</th>
        <td>{{ $sale->meta->payment_method }}</td>
    </tr>

    <tr>
        <th>License type</th>
        <td>{{ $sale->meta->license_type }}</td>
    </tr>

    <tr>
        <th>Financial status</th>
        <td>{{ $sale->meta->financial_status }}</td>
    </tr>

    <tr>
        <th>Amount</th>
        <td>{{ $sale->meta->amount }}</td>
    </tr>
    @if(isset($lead)) <!-- Todo check lead object -->
    <tr>
        <th>Ip Address</th>
        <td>{{ $lead->meta->ipAddress }} </td>
    </tr>
    @endif

</table>
</body>
</html>
