# MailChimp Bundle

Thin abstraction layer for the MailChimp API 2.0.

All API endpoints are implemented … by not implementing them but taking advantage of the API's naming conventions.

## Getting started

Download coderbyheart/MailChimpBundle using composer:

    php composer.phar require ~1.0.2

Enable the Bundle:

    <?php
    // app/AppKernel.php
    
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new FOS\UserBundle\FOSUserBundle(),
        );
    }

Configure the Bundle:

    // app/config/config.yml
    
    coderbyheart_mail_chimp:
        api_key:     123-us1  # the api key provided by mailchimp
        return_type: object   # return response data as 'object' or 'array'

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


## LICENSE

Copyright (c) 2013 Markus Tacker | coder::by(♥); // Freelance Señor Web Backend Dev | http://coderbyheart.de/

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of
the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
