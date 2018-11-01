<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Language Extension</h1>
    <br>
</p>

Yii 2 Framework Language extension with Status Keep and Mapping

[![Latest Stable Version](https://poser.pugx.org/yidas/yii2-language/v/stable?format=flat-square)](https://packagist.org/packages/yidas/yii2-language)
[![Latest Unstable Version](https://poser.pugx.org/yidas/yii2-language/v/unstable?format=flat-square)](https://packagist.org/packages/yidas/yii2-language)
[![License](https://poser.pugx.org/yidas/yii2-language/license?format=flat-square)](https://packagist.org/packages/yidas/yii2-language)

FEATURES
--------

- ***language Mapping** integrated with Yii2 Language*

- ***Session & Cookie** storage support*

- ***Yii2 i18n** support*

---

OUTLINE
-------

* [Requirements](#requirements)
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
    - [get()](#get)
    - [set()](#set)
    - [getbymap()](#getbymap)
    - [setbymap()](#setbymap)
    - [isFirstCome()](#isfirstcome)
* [Implementation](implementation)
    - [Controller for Changing Language](#controller-for-changing-language)
    - [BeforeAction for globally changing language](#beforeaction-for-globally-changing-language)
    
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
    'language' => 'en-US',
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

|property|Type|Default|Description|
|:-|:-|:-|:-|
|languages|array|As example|Supported language list|
|maps|array|As example|Customized language map|
|storage|string|'session'|Storage carrier: 'session' or 'cookie'|
|storageKey|string|'language'|Storage carrier Key|

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

### get()

Get Current Language

```php
public string get($map=null)
```

*Example:*

```php
echo \Yii::$app->lang->get();  // en-US
```

You could get from map by giving map key as first argument:

```php
echo \Yii::$app->lang->get('html');  // en
```

### set()

Set Current Language synchronised to `\Yii::$app->language`

```php
public boolean set($language)
```

*Example:*

```php
\Yii::$app->lang->set('zh-TW');
```

### getByMap()

Get customized language value from $map

```php
public string getByMap($mapKey)
```

*Example:*

If you have to echo HTML language value by current language:

```php
echo \Yii::$app->lang->getByMap('html');  // en
```

### setByMap()

Set by using customized language value from $map

```php
public boolean setByMap($mapKey, $mapValue)
```

*Example:*

If you have to set current language by inputting a HTML language value:

```php
$this->setByMap('html', 'zh-Hant');
```

### isFirstCome()

First time coming check, which has no StorageRecord

Inverse alias with `hasStorageRecord()`

```php
public boolean isFirstCome()
```

*Example:*

```php
if (Yii::$app->lang->isFirstCome()) {
    // Detetmine user ip to set current language
} 
else if (Yii::$app->lang->hasStorageRecord()) {
    // Means !(Yii::$app->lang->isFirstCome())
}
```

---

IMPLEMENTATION
--------------

### Controller for Changing Language

You could add a controller or action for changing language like `/language?language=zh-TW`:

```php
<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

/**
 * The Controller for Language converting
 */
class LanguageController extends Controller
{
    public function actionIndex($language='')
    {
        $result = Yii::$app->lang->set($language);
        
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}
```

### BeforeAction for globally changing language

You could globally set language by handling language setting in the bootstrap of application.

For example, get `GET` parameter to set language in `on beforeAction` function:

```php
return [
    'on beforeAction' => function ($event) {
        // Always fetch language from get-parameter
        $lang = \Yii::$app->request->get('lang');
        // Set to given language with get-parameter
        if ($lang) {
            $result = \Yii::$app->lang->set($lang);
        }
    },
    ...
]
```

After that, by giving `lang` param from any url like `/post/my-article?lang=zh-TW` would change language.
