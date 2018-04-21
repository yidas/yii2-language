<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii2 Language</h1>
    <br>
</p>

Language extension component for Yii 2 framework.

[![Latest Stable Version](https://poser.pugx.org/yidas/yii2-language/v/stable?format=flat-square)](https://packagist.org/packages/yidas/yii2-language)
[![Latest Unstable Version](https://poser.pugx.org/yidas/yii2-language/v/unstable?format=flat-square)](https://packagist.org/packages/yidas/yii2-language)
[![License](https://poser.pugx.org/yidas/yii2-language/license?format=flat-square)](https://packagist.org/packages/yidas/yii2-language)

FEATURES
--------

- ***language Mapping** integrated with Yii2 Language*


---

REQUIREMENTS
------------

This library requires the following:

- PHP 5.4.0+
- Yii 2.0.0+

---


INSTALLATION
------------

Install via Composer in your Yii2 project:

```
composer require yidas/yii2-language
```

---

CONFIGURATION
-------------

Add a component using `yidas\components\Language` with configurations:

```php
return [
    'bootstrap' => ['log', 'lang'],
    'components' => [
        'lang' => [
            'class' => 'yidas\components\Language',
            'languages' => [
                0 => 'en-US',
                1 => 'zh-TW',
                2 => 'zh-CN',
            ],
            'maps' => [
                'html' => [
                    0 => 'en',
                    1 => 'zh-Hant',
                    2 => 'zh-Hans',
                ],
            ],
            // 'storage' => 'session',
            // 'storageKey' => 'language',
        ],
        ...
```

|property|Description|
|:-|:-|
|languages|Supported language list|
|maps|Customized language map|
|storage|Storage carrier such as Session and Cookie|
|storageKey|Storage carrier Key|

### Bootstrap

You could add the language component into `bootstrap` for keeping the language storage work such as Seesion and Cookie.

```php
// `lang` component for example
return [
    'bootstrap' => ['lang'], 
    ...
```

---

USAGE
-----

### `get()`

Get Current Language

```php
echo \Yii::$app->lang->get();  // en-US
```

You could get from map by giving map key as first argument:

```php
echo \Yii::$app->lang->get('html');  // en
```



### `getByMap()`

Get customized language value from $map

```php
echo \Yii::$app->lang->getByMap('html');  // en
```

### `set()`

Set Current Language

```php
\Yii::$app->lang->set('zh-TW');
```
