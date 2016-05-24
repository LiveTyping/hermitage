Hermitage
=========

How often do you have to store images that were uploaded by users? 
Probably, very often. 
Putting these images into mobile applications is not so simple, because there are many devices with different parameters. 
A solution comes with Hermitage.

Hermitage is a micro-service based on Slim. It provides storage, 
delivery and modification of your images for clients and devices you want. Hermitage can:
* Take and put the image through the simple REST API
* Use local file system or Amazon S3 as a repository. And you can easily write your own adapter if needed.
* Put  the image in one of preset formats. You can add your own - it's a matter of seconds!

And all of it is out of the box. Amazing! In addition, Hermitage is very simple and easy to use. 
The information bellow will cover the details. So, let's begin!


# Installation

At first, you will need to install a composer lib and after that - to add a config file, 
then create an index file for your web-server and set environment variables. 
You can do it either by hand, or by using pre-setted skeleton 
([Hermitage Skeleton](https://github.com/LiveTyping/hermitage-skeleton)) and skip this section.

Run the [Composer](https://getcomposer.org) command to install:

```bash
composer require livetyping/hermitage ~0.1
```

### Config file

You can put your config in `config/main.php` or so.
 
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

Copy the `.env.example` file to a local `.env` and configure it:

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

You can put it in `public/index.php` or so.

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

Hermitage provides a simple API so you could upload, download and delete your images.

### Signing write requests

To write to Hermitage a user agent will have to be specified by two request headers: 
`X-Authenticate-Signature` and `X-Authenticate-Timestamp`.

`X-Authenticate-Signature` is, like the access token, an HMAC (also using SHA-256 and the secret key).

The data for the hash is generated with the following elements:

* HTTP method (POST or DELETE)
* The URI
* UTC timestamp (integer only)

These elements are concatenated in the previous order with `|` as a delimiter character, 
and the hash is generated with the secret key. 
The following snippet shows how it can be accomplished with PHP when deleting the image:

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

Deleting images from Hermitage can be done by requesting image's URIs with `HTTP DELETE`.

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
