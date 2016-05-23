Hermitage
=========

How often do you have to store images, uploaded by users? 
Rather, very often. 
Give these images mobile applications are not so simple, because there are many devices with different parameters. 
What to do? Help comes from Hermitage.

Hermitage is a micro-service based on Slim that provides storage, 
delivery and modification of your images for the desired clients and devices. Hermitage can:
* Take and give the image through the simple REST API
* Use as a repository local file system, or Amazon S3. If this is not enough, you can easily write your own adapter
* Give the image in one of a few preset formats. Add your own - a matter of seconds!

And all this is out of the box. Amazing! In addition, hermitage is very easy and simple to use. 
This document will be enough to understand. So, let's begin!


# Installation

Initially, you need to install composer lib and after that to add a config file, 
set environment and create index file for your web-server. 
You need to do it manually, or you may use pre-setted skeleton 
([Hermitage Skeleton](https://github.com/LiveTyping/hermitage-skeleton)) and skip this section.

Run the [Composer](https://getcomposer.org) command to install:

```bash
composer require livetyping/hermitage ~0.1
```

### Config file

You may put your config in `config/main.php` or so.
 
```php
return [
    'root-dir' => dirname(__DIR__),
    'storage-dir' => dirname(__DIR__) . '/storage',

    // your versions
    'images.versions' => [
        /**
         * '{version-name}' => [
         *     'type' => '{manipulator-name}',
         *     // manipulator options
         * ],
         */
        'mini' => [
            'type' => 'resize',
            'height' => 200,
            'width' => 200,
        ],
        'small' => [
            'type' => 'resize',
            'height' => 400,
            'width' => 400,
        ],
        'thumb' => [
            'type' => 'fit',
            'height' => 100,
            'width' => 100,
        ],
    ],

    // parameters for optimization an original image
    'images.optimization-params' => ['maxHeight' => 800, 'maxWidth' => 800, 'interlace' => true],
    'images.manipulator-map' => [
        'resize' => \livetyping\hermitage\foundation\images\processor\manipulators\Resize::class,
        'fit' => \livetyping\hermitage\foundation\images\processor\manipulators\Fit::class,
    ],
    'images.manager-config' => ['driver' => 'gd'],

    // slim framework settings
    'settings.httpVersion' => '1.1',
    'settings.responseChunkSize' => 4096,
    'settings.displayErrorDetails' => false,
];
```

### Environment variables

Copy the `.env.example` file to the local `.env` and configure it:

```bash
cp vendor/livetyping/hermitage/.env.example .env
```

The local `.env` file looks like this:

```
AUTH_SECRET=changeme

###
# Adapter
##
STORAGE_ADAPTER=local

# AWS S3
#STORAGE_ADAPTER=s3
#STORAGE_S3_REGION=
#STORAGE_S3_BUCKET=
#STORAGE_S3_KEY=
#STORAGE_S3_SECRET=
```

***NOTE:*** Set `AUTH_SECRET` to some random string.

### Index file

You may put it in `public/index.php` or so.

```php
require __DIR__ . '/../vendor/autoload.php';

$sources = new \livetyping\hermitage\app\Sources([
    // path to your config
    __DIR__ . '/../config/main.php',
]);

// load environment variables from the `.env` file if it exists
livetyping\hermitage\bootstrap\load_dotenv(dirname(__DIR__));
livetyping\hermitage\bootstrap\app($sources)->run();
```

# REST API

Hermitage provides simple API to upload, download and delete your images.

### Signing write requests

To be able to write to Hermitage the user agent will have to specify two request headers: 
`X-Authenticate-Signature` and `X-Authenticate-Timestamp`.

`X-Authenticate-Signature` is, like the access token, an HMAC (also using SHA-256 and the secret key).

The data for the hash is generated using the following elements:

* HTTP method (POST or DELETE)
* The URI
* UTC timestamp (integer only)

These elements are concatenated in the above order with `|` as a delimiter character, 
and a hash is generated using the secret key. 
The following snippet shows how this can be accomplished in PHP when deleting an image:

```php
$timestamp = (new DateTime('now', new DateTimeZone('UTC')))->getTimestamp();
$filename  = '<filename>';
$secret    = '<secret value>';
$method    = 'DELETE';

// The URI
$uri = "http://hermitage/{$filename}";

// Data for the hash
$data = implode('|', [$method, $uri, $timestamp]);

// Generate the signature
$signature = hash_hmac('sha256', $data, $secret);

// Request the uri
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_HTTPHEADER => [
        'X-Authenticate-Timestamp' => $timestamp,
        'X-Authenticate-Signature' => $signature,
    ],
]);
curl_exec($ch);
curl_close($ch);
```

### Upload 

```bash
curl -XPOST http://hermitage --data-binary @<image.jpg> -H "Content-Type: image/jpeg" -H "X-Authenticate-Timestamp: <timestamp>" -H "X-Authenticate-Signature: <signature>"
```

results in:

```json
{
    "filename": "generated/path/to/file.jpg"
}
```

### Delete

Deleting images from Hermitage is accomplished by requesting the image URIs using `HTTP DELETE`.

```bash
curl -XDELETE http://hermitage/<filename> -H "X-Authenticate-Timestamp: <timestamp>" -H "X-Authenticate-Signature: <signature>"
```

### Get

Getting an original (optimized) version of the image:

```bash
curl http://hermitage/<filename>
```

Getting another version of the image:

```bash
curl http://hermitage/<filename>:<version>
```

where `<version>` is the version name of the image from config file (like "small", "thumb", etc.)

# License

Hermitage is licensed under the MIT license.

See the [LICENSE](LICENSE) file for more information.
