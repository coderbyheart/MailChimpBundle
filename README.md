# MailChimp Bundle

Thin abstraction layer for the MailChimp API 2.0.

All API endpoints are implemented … by not implementing them but taking advantage of the API's naming conventions.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a671e877-3754-44ae-a517-3f10ba606324/big.png)](https://insight.sensiolabs.com/projects/a671e877-3754-44ae-a517-3f10ba606324)

[![Build Status](https://travis-ci.org/coderbyheart/MailChimpBundle.svg)](https://travis-ci.org/coderbyheart/MailChimpBundle) [![Code Climate](https://codeclimate.com/github/coderbyheart/MailChimpBundle/badges/gpa.svg)](https://codeclimate.com/github/coderbyheart/MailChimpBundle) [![Test Coverage](https://codeclimate.com/github/coderbyheart/MailChimpBundle/badges/coverage.svg)](https://codeclimate.com/github/coderbyheart/MailChimpBundle)

## Usage

    $mailchimp = $container->get('mailchimp');
    $lists = $mailchimp->listsList();

## Documentation

The api endpoints are reached via a method call on the MailChimp API object instance.
The method name is composed by concatenating the endpoint url.
Parameters are provided via a hash as the methods argument.

| API endpoint              | Method                   | Arguments                  |
| ------------------------- | ------------------------ | -------------------------- |
| `lists/list`              | `listsList`              |                            |
| `lists/batch-unsubscribe` | `listsBatch_unsubscribe` | `array('batch' => $batch)` |
