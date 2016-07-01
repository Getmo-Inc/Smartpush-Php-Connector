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
**Description**: Create a new push instance.

**Example**:
```php
$push = new Push(string $devid, string $when = 'now', string $alias = '');
```

> The second and thirth parameters are optional. The second ```$when``` parameter can handle this entry models: **'now'**, **'0000-00-00 00:00:00'**, **'00/00/000 00:00:00'**, or a valida UNIX timestamp. The thirth ```$alias``` paramenter sets a custom name for this Push Notification, so you can track it later on control panel.


#### Method: setEnvironment()
---
**Description**: (Optional) Set the environment you want to use to sent this Push Notification.

**Example**:
```php
$push->setEnvironment(string $envinronment = '1'); # This method return $this, so you can chain it.
```
**Return**:
```php
$this; # so you can chain methods! 
```

> This method is optional. If you dont set the environment, the lib will guess you choose ```production```. If you want to use ```sandbox```, use this method with $environment = '0'.


#### Method: addNotification()
---
**Description**: Add a notification to the Push payload.

**Example**:
```php
$push->addNotification(string $appid, string $platform, array $params);
# or
$push->addNotification(string $appid, string $platform, object $params);
```
**Return**:
```php
$this; # so you can chain methods! 
```

> The thirth parameter, **$params**, must be an ```array``` or an ```object``` (If you pass in an array the lib will turn it into an object). The content schema variates according the platform. Consult the REST API docs to obtain the correct schema for the platform that you want to target.


#### Method: getNotifications()
---
**Description**: (Optional) Return an array of Notifications that you have previously configured to send.

**Example**:
```php
$notifications = $push->getNotifications();
foreach ($notifications as $notification) {
    echo $notification->appid;
    echo $notification->platform;
    var_dump($notification->params);
}
```
**Return**:
```php
array; # of objects 
``` 

> This method can be used to inspect the Notifications data inside the Push object before sending.

#### Method: setFilterRange()
---
**Description**: (Optional) Set the range days of which the filter will operate when the job is processed. The $range value can be one of there: `7`, `15`, `30`, `60`, `90`, `all`, otherwise it will fallback to default: `all`.

**Example**:
```php
$push->setFilterRange(string $range);
```
**Return**:
```php
$this; # so you can chain methods! 
```


#### Method: setFilterOperator()
---
**Description**: (Optional) Set the operator of which the filter will use to do comparisons. The $operator value can be one of there: `AND`, `OR`, otherwise it will fallback to default: `AND`.

**Example**:
```php
$push->setFilterOperator(string $operator);
```
**Return**:
```php
$this; # so you can chain methods! 
```


#### Method: addTag()
---
**Description**: Add a Tag to filter the Push Notification devices.

**Example**:
```php
$push->addTag(string $key, string $value);
# or
$push->addTag(string $key, string $comparator, string $value);
```
**Return**:
```php
$this; # so you can chain methods! 
```

> The thirth parameter is optional. If you suppress the ```$comparator``` parameter the lib will guess you want to ```Equal``` (=) this entry. The complete list of comparators you find out in the REST API docs.


#### Method: getTags()
---
**Description**: (Optional) Return an ```array``` of Tags that you have previously configured to send. 

**Example**:
```php
$tags = $push->getTags();
foreach ($tags as $tag) {
    var_dump($tags);
}
```
**Return**:
```php
array; # of tags 
```

> This method can be used to inspect the Notifications data inside the Push object before sending.


#### Method: getPayload()
---
**Description**: (Optional) Return the complete payload that you have previously configured to send. If you pass ```true``` in the first parameter, the method will return a JSON string, otherwise an array.

**Example**:
```php
var_dump($this->getPayload()); # array
# or
var_dump($this->getPayload(true)); # JSON string
```
**Return**:
```php
'{...}' || array; # JSON string or array
```

> This method can be used to inspect the data inside every notification before sending it.


#### Method: send()
---
**Description**: Send the Push Notification previously configured. If the inputs dont validate this methos will return ```false```, otherwise ```true```.

**Example**:
```php
if ($push->send()) {
    # ...
} else {
    # ...
}
```
**Return**:
```php
true || false;
```
 
> If this method return ```false``` probably you forgot to config a notification or a tag.


#### Method: getResult()
---
**Description**: Grab the server response after send a Push Notification. One of the most important information that returns in the JSON string is the **pushid**.

**Example**:
```php
$result = $push->getResult();
$data = json_decode($result);
echo $data->pushid;
```
**Return**:
```php
'{...}'; # JSON string
```


#### Method: getInfo()
---
**Description**: Consult server for the status (and more information) about a Push Notification.

**Example**:
```php
$info = $push->getInfo(string $pushid);
$data = json_decode($info);

var_dump($data->notifications); # array of information about all notifications of this push.
foreach ($data->notifications as $notification) {
    echo $notification->appid;
    echo $notification->status;
    # if push was sent the follow property will be available.
    echo $notification->sent_at;
}
...
# if push was configured to be sent at a future moment in time, this two properties will be available.
echo $data->{'time-left'};
echo $data->date;
```
**Return**:
```php
'{...}'; # JSON string
```


#### Method: cancel().
---
**Description**: Cancel and Consult server for the status (and more information) about a Push Notification.

**Example**:
```php
$push->cancel(string $pushid);
# or
$info = $push->cancel(string $pushid);
# Here, $info get the same result as getInfo() mention above.
```
**Return**:
```php
'{...}'; # JSON string
```

> You can only cancel Push Notifications which were not sent.


### Support

Jonathan Martins
webmaster@getmo.com.br
---

> Developed by Getmo
