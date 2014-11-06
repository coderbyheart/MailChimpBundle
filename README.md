# MailChimp Bundle

Thin abstraction layer for the MailChimp API 2.0.

All API endpoints are implemented … by not implementing them but taking advantage of the API's naming conventions.

## Usage

    $mailchimp = $container->get('mailchimp');
    $lists = $mailchimp->listsList();

## Documentation

The api endpoints are reached via a method call on the MailChimp API object instance.
The method name is composed by concatenating the endpoint url.
Parameters are provided via a hash as the methods argument.

<table>
<thead>
<tr>
<th>API endpoint</th>
<th>Method</th>
<th>Arguments</th>
</tr>
</thead>
<tbody>
<tr>
<td>`lists/list`</td>
<td>`listsList`</td>
<td></td>
</tr>
<tr>
<td>`lists/batch-unsubscribe`</td>
<td>`listsBatch_unsubscribe`</td>
<td>`array('batch' => $batch)`</td>
</tr>
</tbody>
</table>
