Laravel Postmark Inbound Parser
=============
[![Build Status](https://travis-ci.org/camelCaseD/postmark-inbound-laravel.png)](https://travis-ci.org/camelCaseD/postmark-inbound-laravel) [![Coverage Status](https://coveralls.io/repos/camelCaseD/postmark-inbound-laravel/badge.png?branch=master)](https://coveralls.io/r/camelCaseD/postmark-inbound-laravel?branch=master)

This is an Inbound Parser for Postmark meant to be used with Laravel.

How To Use
=============

Config
-------------
Add the following service provider to app/config/app.php:

```php
'Camelcased\Postmark\PostmarkServiceProvider',
```

You can also add the following alias:

```php
'PostmarkEmail'   => 'Camelcased\Postmark\PostmarkEmail',
```

Basics
-------------
Then anywhere within your app you can use the following:

```php
PostmarkEmail::from();
PostmarkEmail::to();
PostmarkEmail::body(); // Auto-detects if message contains html or text only.
PostmarkEmail::bodyIsText();
PostmarkEmail::bodyIsHtml();
PostmarkEmail::subject();
PostmarkEmail::replyTo();
PostmarkEmail::cc(); // Returns array if more than one. Ex: array('someone@somewhere.com', 'hi@awesome.com'). Returns string if only one.
PostamrkEmail::bcc(); // Returns same as cc;
```

Attachments
-------------
Attachments are slightly more complicated:

```php
PostmarkEmail::hasAttachments() // Returns true or false
PostmarkEmail::attachments(); // Returns array of attachments
```
Looping through multiple attachments:

```php
if (PostmarkEmail::hasAttachments())
{
  $attachments = PostmarkEmail::attachments();

  foreach ($attachments as $attachment) {
    $attachment->Name();
    $attachment->Content(); // Returns base64 encoded string
    $attachment->DecodedContent(); // Returns decoded value
    $attachment->Type(); // Or use $attachment->MIME()
  }
}
```

License
=============
[MIT license](http://opensource.org/licenses/MIT)
