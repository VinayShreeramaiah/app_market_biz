<!doctype html>
<html>
<head>

</head>
<body>
<table>
    <tr>
        <th>Report Title</th>
        <td>{{ $report->meta->full_title }}</td>
    </tr>
    <tr>
        <th>Name</th>
        <td>{{ $lead->meta->customername }} </td>
    </tr>

    <tr>
        <th>Email</th>
        <td>{{ $lead->meta->email }} </td>
    </tr>

    <tr>
        <th>Country</th>
        <td>{{ $lead->meta->country }} </td>
    </tr>

    <tr>
        <th>Phone</th>
        <td>{{ $lead->meta->phone }} </td>
    </tr>


    <tr>
        <th>Company</th>
        <td>{{ $lead->meta->company }} </td>
    </tr>


    <tr>
        <th>Designation</th>
        <td>{{ $lead->meta->designation }} </td>
    </tr>

    <tr>
        <th>Message</th>
        <td>{{ $lead->meta->message }} </td>
    </tr>
    {{----}}
    {{--<tr>--}}
    {{--<th>Report ID</th>--}}
    {{--<td>{{ $lead->meta->reportid }}</td>--}}
    {{--</tr>--}}

    {{--<tr>--}}
    {{--<th>Inquiry Type</th>--}}
    {{--<td>{{ $lead->meta->formtype }}</td>--}}
    {{--</tr>--}}


    <tr>
        <th>Ip Address</th>
        <td>{{ $lead->meta->ipAddress }} </td>
    </tr>
</table>
</body>
</html>
