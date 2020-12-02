This is an image service, designed to ingest images and prepare them for use on the site.

## Installation

You can install this package via composer using:

```bash
composer require michaelberry/mbimage
```

The package will automatically register itself.

To publish the config file and view file run:

```bash
php artisan vendor:publish --provider="MichaelBerry\MBImage\MBImageServiceProvider"
```

This will publish a file `mbimage.php` in your config directory with the following contents:
```php
return [
    'type' => 'S3', // if you want the image to be uploaded to Amazon S3, leave blank if you want it to be in your local public folder
    'path' => 'mbuploads' // path directory
];
```