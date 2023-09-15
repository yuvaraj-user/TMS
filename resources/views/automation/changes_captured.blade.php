<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <p>Hi</p>
    <p>Bring to your attention that changes have been made to the project. In order to proceed with the project smoothly and ensure transparency, it is crucial that we receive a detailed explanation for this change. We kindly request you provide us with a reason behind this modification along with any supporting documents or references that can validate the decision.</p>
    <h4>before Changed</h4>
    <table class="table">
        <thead style="background-color:blue;color:white;" >
            <tr>
                <th>Query No</th>
                <th>Work Order No</th>
                <th>Client Name</th>
                <th>Project Name</th>
                <th>Work Start Date</th>
                <th>Work End Date</th>
                <th>Estimation Hours</th>
                <th>Order Receivable</th>
                <th>Collected Till</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid;text-align:center;">{{ $original_data['query_no'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $original_data['work_order_no'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $original_data['client_name'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $original_data['project_name'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ date('d-m-Y',strtotime($original_data['work_start_date'])) }}</td>
                <td style="border: 1px solid;text-align:center;">{{ date('d-m-Y',strtotime($original_data['work_end_date'])) }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $original_data['estimation_hours'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $original_data['order_value'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $original_data['collected_till'] }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $original_data['work_status'] }}</td>
            </tr>
        </tbody>
</table>

    <h4>After Changed</h4>
    <table class="table">
        <thead style="background-color:blue;color:white;" >
            <tr>
                <th>Query No</th>
                <th>Work Order No</th>
                <th>Client Name</th>
                <th>Project Name</th>
                <th>Work Start Date</th>
                <th>Work End Date</th>
                <th>Estimation Hours</th>
                <th>Order Receivable</th>
                <th>Collected Till</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid;text-align:center;">{{ $changed_data->query_no }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $changed_data->work_order_no }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $changed_data->client_name->name }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $changed_data->project_name }}</td>
                <td style="border: 1px solid;text-align:center;">{{ date('d-m-Y',strtotime($changed_data->work_start_date)) }}</td>
                <td style="border: 1px solid;text-align:center;">{{ date('d-m-Y',strtotime($changed_data->work_end_date)) }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $changed_data->estimation_hours }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $changed_data->order_value }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $changed_data->collected_till }}</td>
                <td style="border: 1px solid;text-align:center;">{{ $changed_data->work_status }}</td>
            </tr>
        </tbody>
</table>
<p>We kindly ask for your immediate response to this email.</p>
</body>
</html>