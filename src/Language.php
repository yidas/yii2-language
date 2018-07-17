<?php

namespace yidas\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Language Extension Component
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @version 1.0.1
 */
class Language extends Component
{
    /**
     * Supported language list
     *
     * @var array Values refer to \Yii::$app->language
     */
    public $languages = [
        0 => 'en-US',
        1 => 'zh-TW',
        2 => 'zh-CN',
    ];

    /**
     * Customized language map
     *
     * @var array Values map to $languages
     * @example $this->getByMap('html');
     */
    public $maps = [
        'html' => [
            0 => 'en',
            1 => 'zh-Hant',
            2 => 'zh-Hans',
        ],
    ];

    /**
     * Storage carrier such as Session and Cookie
     *
     * @var string ['session', 'cookie']
     */
    public $storage = 'session';

    /**
     * Storage carrier Key
     *
     * @var string
     */
    public $storageKey = 'language';

    /**
     * Current Language
     *
     * @var string Should same as \Yii::$app->language
     */
    private $_currentLang = null;

    /**
     * Current Language index
     *
     * @var integer
     */
    private $_currentLangIndex = null;

    /**
     * Has storage record remark
     *
     * @var boolean
     */
    private $_hasStorageRecord = false;

    /**
     * Bootstrap
     * 
     * You could register this componet then add to 'bootstrap' in configuration.
     *
     * @return void
     */
    public function init()
    {
        // Get Language from storage such as Session or Cookie
        $language = $this->_getFromStorage();

        if ($language) {
            // Has storage record remark
            $this->_hasStorageRecord = true;
            // Set to current language
            $this->set($language);
        } else {
            // Refer Yii::$app->language as default language
            if (Yii::$app->language) {
                $this->set(Yii::$app->language);
            } else {
                $this->setByIndex(0);
            } 
        }
    }

    /**
     * Get Current Language
     *
     * @return string
     */
    public function get($map=null)
    {
        if ($map) {
            // Get from map
            return $this->getByMap($map);
        }
        
        return ($this->_currentLang) ?: Yii::$app->language;
    }

    /**
     * Get Current Language index
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->_currentLangIndex;
    }

    /**
     * Get customized language value from $map
     *
     * @param string $map Variable name defined in component
     * @return string Language value from diving $map with current language
     * @example $this->getByMap('html');
     */
    public function getByMap($mapKey)
    {
        $index = $this->getIndex();

        if (!isset($this->maps[$mapKey])) {
            
            throw new Exception("Given map `{$mapKey}` doesn't declared in Language Component", 500);
        }

        return isset($this->maps[$mapKey][$index]) ? $this->maps[$mapKey][$index] : null;
    }

    /**
     * Set by using customized language value from $map
     *
     * @param string $map Variable name defined in component
     * @param string $mapValue Refer to giving map's values
     * @return bool Result
     * @example $this->setByMap('html', 'zh-Hant');
     */
    public function setByMap($mapKey, $mapValue)
    {
        if (!isset($this->maps[$mapKey])) {
            
            throw new Exception("Given map `{$mapKey}` doesn't declared in Language Component", 500);
        }

        $index = array_search($mapValue, $this->maps[$mapKey]);

        return $this->setByIndex($index);
    }

    /**
     * Set Current Language synchronised to `\Yii::$app->language`
     *
     * @param string $language Refer to $languages
     * @return bool Result
     */
    public function set($language)
    {
        $index = array_search($language, $this->languages);

        // Check the match from supported language list 
        if ($index === false) {
            // Not matched
            return false;

        } else {
            // Set status
            $this->_set($index, $language);
            // Success
            return true;
        }
    }

    /**
     * Set Current Language
     *
     * @param string $language Refer to $languages
     * @return bool Result
     */
    public function setByIndex($index)
    {
        $language = isset($this->languages[$index]) ? $this->languages[$index] : false;

        // Check the match from supported language list 
        if ($index === false) {
            // Not matched
            return false;

        } else {
            // Set status
            $this->_set($index, $language);
            // Success
            return true;
        }
    }

    /**
     * Check hasStorageRecord
     *
     * @return boolean
     */
    public function hasStorageRecord()
    {
        return $this->_hasStorageRecord;
    }

    /**
     * First time coming check, which has no StorageRecord
     *
     * @return boolean
     */
    public function isFirstCome()
    {
        return !($this->_hasStorageRecord);
    }

    /**
     * Set Language
     *
     * @param integer $index
     * @param string $lang
     * @return void
     */
    protected function _set($index, $language)
    {
        $this->_currentLang = $language;
        $this->_currentLangIndex = $index;
        // Storage
        $this->_setToStorage($language);
        // Yii Language
        Yii::$app->language = $language;
    }
    
    /**
     * Get current language from storage
     *
     * @return string Language
     */
    protected function _getFromStorage()
    {
        switch ($this->storage) {

            case 'session':
                return Yii::$app->session->get($this->storageKey);
                break;
            
            case 'cookie':
                return Yii::$app->request->cookies->getValue($this->storageKey);
                break;
            
            default:
                return false;
                break;
        }
    }

    /**
     * Set language to storage
     *
     * @param string $language Refer to $languages
     * @return bool Result
     */
    protected function _setToStorage($language)
    {
        switch ($this->storage) {

            case 'session':
                return Yii::$app->session->set($this->storageKey, $language);
                break;
            
            case 'cookie':
                return Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => $this->storageKey,
                    'value' => $language,
                ]));
                break;
            
            default:
                return false;
                break;
        }
    }
}
