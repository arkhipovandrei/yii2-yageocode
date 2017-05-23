Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```sh
php composer.phar require arkhipovandrei/yii2-yageocoder:dev-master
```

or add

```json
"arkhipovandrei/yii2-yageocoder": "*"
```

to the require section of your composer.json.


Usage
 
Set component in your config
```php
'components'=>[
    ...
    'geoCode' => [
        'class' => 'common\components\yamp\GeoCode'
    ]
    ...
],
```
In you app:
 * find one Yii::$app->geoCode->setAddress('Санкт-Петербург')->find(); 
 * find all Yii::$app->geoCode->setAddress('Санкт-Петербург')->findAll()
