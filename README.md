# Smartpush PHP connector

Php library to connect with Smartpush API services.

## Installation

```shell
composer require getmo/smartpush-php-connector
```

## Platforms
Use one of the these platforms when you see ```platform``` on the docs above.
```php
$platforms = ['iOS', 'ANDROID', 'WINDOWS', 'CHROME', 'SAFARI', 'FIREFOX'];
```

## Usage
Import the Push class into your project.

```php
use Smartpush\Push;
```

#### Class: Push
---
**Description**: Create a new push instance.\
**Example**:
```php
$push = new Push('devid');
```

### Method: addNotification()
---
**Description**: Add a notification to the Push payload.\
**Example**:
```php
$push->addNotification(string $appid, string $platform, array $params);
# or
$push->addNotification(string $appid, string $platform, object $params);
```
> Note: The type of the thirth parameter, **params**, must be an ```array``` or an ```object``` (If you pass in an array the lib turn it into an object). The content schema variates according the platform. Consult the REST API docs to obtain the correct schema for the platform that you want to target.


### Method: addTag()
---
**Description**: Add a Tag to filter the Push Notification.\
**Example**:
```php
$push->addTag(string $key, string $value);
# or
$push->addTag(string $key, string $operator, string $value);
```
> Note: If you suppress the ```$operator```parameter the lib will guess you want to ```Equal``` (=) this entry. The complete list of operator you find in the REST API docs.

Documentation in progress... See the examples folder!
