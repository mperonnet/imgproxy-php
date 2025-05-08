# ImgProxy PHP

A comprehensive PHP library for building URLs for [ImgProxy](https://imgproxy.net), supporting all features including pro options.

This library is based on and extends the work of the [onliner/imgproxy-php](https://github.com/onliner/imgproxy-php) project, adding support for all ImgProxy features including Pro functionality.

[![Version][version-badge]][version-link]
[![Total Downloads][downloads-badge]][downloads-link]
[![Php][php-badge]][php-link]
[![License][license-badge]](LICENSE)
[![Build Status][build-badge]][build-link]

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```bash
composer require mperonnet/imgproxy-php
```

## Features

- Full support for all ImgProxy options (including Pro features)
- URL signing and encryption
- Base64 and plain URL encoding
- Support for info endpoint
- Support for presets
- Support for chained pipelines (Pro)
- Type-safe option builders
- Immutable, fluent interface

## Basic Usage

```php
use Mperonnet\ImgProxy\UrlBuilder;
use Mperonnet\ImgProxy\Options\Width;
use Mperonnet\ImgProxy\Options\Height;
use Mperonnet\ImgProxy\Options\Quality;
use Mperonnet\ImgProxy\Options\Dpr;

$key = getenv('IMGPROXY_KEY');
$salt = getenv('IMGPROXY_SALT');

$src = 'http://example.com/image.jpg';

$builder = UrlBuilder::signed($key, $salt);
$builder = $builder->with(
    new Dpr(2),
    new Quality(90),
    new Width(300),
    new Height(400)
);
    
$url = $builder->url($src);  // Base64-encoded URL
$url = $builder->usePlain()->url($src); // Plain URL
$url = $builder->url($src, 'png'); // Change image format
```

## URL Formats

### Base64-encoded URL (default)

```php
$url = $builder->useBase64()->url($src);
// Example: /9SaGqJILqstFsWthdP/dpr:2/q:90/w:300/h:400/aHR0cDovL2V4YW1wL2ltYWdlLmpwZw
```

### Plain URL

```php
$url = $builder->usePlain()->url($src);
// Example: /9SaGqJILqstFsWthdP/dpr:2/q:90/w:300/h:400/plain/http://example.com/image.jpg
```

### Encrypted URL (Pro)

```php
$encryptionKey = getenv('IMGPROXY_ENCRYPTION_KEY');
$url = $builder->useEncryption($encryptionKey)->url($src);
// Example: /9SaGqJILqstFsWthdP/dpr:2/q:90/w:300/h:400/enc/a1b2c3d4e5f6g7h8i9j0...
```

## Advanced Features

### Gravity

```php
use Mperonnet\ImgProxy\Options\Gravity;
use Mperonnet\ImgProxy\Support\GravityType;

// Basic gravity
$builder->with(new Gravity(GravityType::CENTER));

// With offset
$builder->with(new Gravity(GravityType::NORTH_WEST, 10, 20));

// Smart gravity
$builder->with(Gravity::smart());

// Focus point gravity
$builder->with(Gravity::focusPoint(0.7, 0.3));

// Object detection gravity (Pro)
$builder->with(Gravity::object(['face', 'cat']));

// Weighted object detection gravity (Pro)
$builder->with(Gravity::objectWeighted(['face' => 2, 'cat' => 1]));
```

### Watermark

```php
use Mperonnet\ImgProxy\Options\Watermark;
use Mperonnet\ImgProxy\Options\WatermarkText;
use Mperonnet\ImgProxy\Options\WatermarkUrl;

// Basic watermark
$builder->with(Watermark::center(0.5, 10, 10, 0.3));

// Replicated watermark
$builder->with(Watermark::replicate(0.5));

// Custom watermark URL (Pro)
$builder->with(new WatermarkUrl('http://example.com/watermark.png'));

// Text watermark (Pro)
$builder->with(new WatermarkText('© Example Corporation'));
```

### Info Endpoint (Pro)

```php
use Mperonnet\ImgProxy\Options\InfoOptions;

// Basic info
$infoUrl = $builder->info()->with(InfoOptions::basic())->url($src);

// Complete info
$infoUrl = $builder->info()->with(InfoOptions::complete())->url($src);

// Custom info
$infoUrl = $builder->info()->with(
    new InfoOptions(
        true,  // size
        true,  // format
        true,  // dimensions
        false, // exif
        false, // iptc
        false, // xmp
        true,  // video_meta
        true   // detect_objects
    )
)->url($src);
```

### Chained Pipelines (Pro)

```php
use Mperonnet\ImgProxy\Options\Resize;
use Mperonnet\ImgProxy\Options\Blur;
use Mperonnet\ImgProxy\Options\Watermark;

// First resize, then blur and add watermark
$url = $builder
    ->with(new Resize('fit', 300, 300))
    ->pipeline(
        new Blur(10),
        Watermark::southEast(0.7)
    )
    ->url($src);
```

## Additional Processing Options

### Resizing and Dimensions

```php
use Mperonnet\ImgProxy\Options\Resize;
use Mperonnet\ImgProxy\Options\Size;
use Mperonnet\ImgProxy\Options\Width;
use Mperonnet\ImgProxy\Options\Height;
use Mperonnet\ImgProxy\Options\MinWidth;
use Mperonnet\ImgProxy\Options\MinHeight;
use Mperonnet\ImgProxy\Options\Zoom;
use Mperonnet\ImgProxy\Options\Dpr;

$builder->with(
    new Resize('fill', 300, 400, true, false),
    new Size(300, 400, true, false),
    new Width(300),
    new Height(400),
    new MinWidth(200),
    new MinHeight(200),
    new Zoom(1.5),
    new Dpr(2)
);
```

### Image Adjustments

```php
use Mperonnet\ImgProxy\Options\Adjust;
use Mperonnet\ImgProxy\Options\Brightness;
use Mperonnet\ImgProxy\Options\Contrast;
use Mperonnet\ImgProxy\Options\Saturation;

$builder->with(
    new Adjust(10, 1.1, 0.9),
    new Brightness(10),
    new Contrast(1.1),
    new Saturation(0.9)
);
```

### Color Effects

```php
use Mperonnet\ImgProxy\Options\Background;
use Mperonnet\ImgProxy\Options\Blur;
use Mperonnet\ImgProxy\Options\Sharpen;
use Mperonnet\ImgProxy\Options\Monochrome;
use Mperonnet\ImgProxy\Options\Duotone;
use Mperonnet\ImgProxy\Options\Colorize;
use Mperonnet\ImgProxy\Options\Gradient;
use Mperonnet\ImgProxy\Support\Color;

$builder->with(
    new Background(new Color(255, 255, 255)),
    new Blur(5),
    new Sharpen(0.7),
    new Monochrome(0.8, 'b3b3b3'),
    new Duotone(0.8, '000000', 'ffffff'),
    new Colorize(0.5, '3498db', true),
    new Gradient(0.7, 'ff0000', 45, 0.2, 0.8)
);
```

### Format and Quality

```php
use Mperonnet\ImgProxy\Options\Format;
use Mperonnet\ImgProxy\Options\Quality;
use Mperonnet\ImgProxy\Options\FormatQuality;
use Mperonnet\ImgProxy\Options\Autoquality;
use Mperonnet\ImgProxy\Options\MaxBytes;

$builder->with(
    new Format('webp'),
    new Quality(85),
    new FormatQuality([
        'jpeg' => 85,
        'webp' => 80,
        'avif' => 60
    ]),
    Autoquality::dssim(0.02, 70, 85, 0.001),
    new MaxBytes(100000)
);
```

### Object Detection (Pro)

```php
use Mperonnet\ImgProxy\Options\BlurDetections;
use Mperonnet\ImgProxy\Options\DrawDetections;

$builder->with(
    new BlurDetections(5, ['face']),
    new DrawDetections(true, ['face', 'cat'])
);
```

## Credits

This library is an extension of the [onliner/imgproxy-php](https://github.com/onliner/imgproxy-php) project with added support for ImgProxy Pro features and a more comprehensive API. We're grateful to the original authors for providing the foundation that made this extension possible.

## License

Released under the [MIT license](LICENSE).


[version-badge]:    https://img.shields.io/packagist/v/mperonnet/imgproxy-php.svg
[version-link]:     https://packagist.org/packages/mperonnet/imgproxy-php
[downloads-link]:   https://packagist.org/packages/mperonnet/imgproxy-php
[downloads-badge]:  https://poser.pugx.org/mperonnet/imgproxy-php/downloads.svg
[php-badge]:        https://img.shields.io/badge/php-8.0+-brightgreen.svg
[php-link]:         https://www.php.net/
[license-badge]:    https://img.shields.io/badge/license-MIT-brightgreen.svg
[build-link]:       https://github.com/mperonnet/imgproxy-php/actions?workflow=test
[build-badge]:      https://github.com/mperonnet/imgproxy-php/workflows/test/badge.svg
