# Smartpush PHP connector

Php library to connect with Smartpush API services.

## Installation

```shell
composer require getmo/smartpush-php-connector
```

## Platforms
Use one of the these platforms when you see ```$platform``` on the docs above.
```php
$platforms = ['IOS', 'ANDROID', 'WINDOWS', 'CHROME', 'SAFARI', 'FIREFOX'];
```

## Usage
Import the Push class into your project.

```php
use Smartpush\Push;
```

#### Class: Push
---
**Description**: Create a new push instance. **Example**:
```php
$push = new Push(string $devid, string $when = 'now', string $alias = '');
```
> The second and thirth parameters are optional. The second ```$when``` parameter can handle this entry models: **'now'**, **'0000-00-00 00:00:00'**, **'00/00/000 00:00:00'**, or a valida UNIX timestamp. The thirth ```$alias``` paramenter sets a custom name for this Push Notification, so you can track it later on control panel.


#### Method: setEnvironment(string $environment)
---
**Description**: (Optional) Set the environment you want to use to sent this Push Notification. **Example**:
```php
$push->setEnvironment(string $envinronment = '1'); # This method return $this, so you can chain it.
```
> This method is optional. If you dont set the environment, the lib will guess you choose ```production```. If you want to use ```sandbox```, use this method with $environment = '0'.


#### Method: addNotification()
---
**Description**: Add a notification to the Push payload. **Example**:
```php
$push->addNotification(string $appid, string $platform, array $params);
# or
$push->addNotification(string $appid, string $platform, object $params);
...
return $this; # so you can chain methods
```
> The thirth parameter, **$params**, must be an ```array``` or an ```object``` (If you pass in an array the lib will turn it into an object). The content schema variates according the platform. Consult the REST API docs to obtain the correct schema for the platform that you want to target.


#### Method: getNotifications()
---
**Description**: Return an array of Notifications that you have previously configured to send. **Example**:
```php
$notifications = $push->getNotifications();
foreach ($notifications as $notification) {
    echo $notification->appid;
    echo $notification->platform;
    var_dump($notification->params);
}
```
> This method can be used to inspect the data inside every notification before sending it.


#### Method: addTag()
---
**Description**: Add a Tag to filter the Push Notification. **Example**:
```php
$push->addTag(string $key, string $value);
# or
$push->addTag(string $key, string $operator, string $value);
...
return $this; # so you can chain methods
```
> The thirth parameter is optional. If you suppress the ```$operator``` parameter the lib will guess you want to ```Equal``` (=) this entry. The complete list of operator you find out in the REST API docs.


#### Method: getTags()
---
**Description**: Return an array of Tags that you have previously configured to send. **Example**:
```php
$tags = $push->getTags();
foreach ($tags as $tag) {
    var_dump($tags);
}
```
> This method can be used to inspect the data inside every notification before sending it.


#### Method: getPayload()
---
**Description**: Return the complete payload that you have previously configured to send. If you pass ```**true**``` in the first parameter, the method will return a JSON string, otherwise an array. **Example**:
```php
$payload = $this->getPayload();
var_dump($payload); # array
...
$payload = $this->getPayload(true);
echo $payload; # JSON string
```
> This method can be used to inspect the data inside every notification before sending it.


#### Method: send()
---
**Description**: Send the Push Notification previously configured. If the inputs dont validate this methos will return ```**false**```, otherwise ```**true**```. **Example**:
```php
if ($push->send()) {
    # ...
} else {
    # ...
}
```
> If this method return ```**false**``` probably you forgot to config a notification or a tag.


#### Method: getResult()
---
**Description**: Return a JSON string from the server after sending a Push Notification. **Example**:
```php
$result = $push->getResult();
$data = json_decode($result);
echo $data->pushid;
```


#### Method: getInfo()
---
**Description**: Return a JSON string information about the informed Push Notification. **Example**:
```php
$info = $push->getInfo(string $pushid);
$data = json_decode($info);

var_dump($data->notifications); # array of information about all notifications of this push.
foreach ($data->notifications as $notification) {
    echo $notification->appid;
    echo $notification->status;
    # if push was sended the follow property will be available.
    echo $notification->sended_at;
}
...
# if push was configured to be sent at a future moment in time, this two properties will be available.
echo $data->{'time-left'};
echo $data->date;
```


#### Method: cancel()
---
**Description**: Cancel and return a JSON string information about the informed Push Notification. **Example**:
```php
$push->cancel(string $pushid);
...
$info = $push->cancel(string $pushid);
# Info here get the same result as getInfo() method above.
```
> You can only cancel Push Notifications which were not sent.


### Support
Jonathan Martins
webmaster@getmo.com.br
---

> Developed by Getmo