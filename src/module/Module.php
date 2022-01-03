<?php
/**
 * Site: https://yiiman.ir
 * AuthorName: gholamreza beheshtian
 * AuthorNumber:09353466620
 * AuthorCompany: YiiMan
 */

namespace Yiiman\ModuleUser\module;

/**
 * tour module definition class
 */

use Yii;
use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */

    public $controllerNamespace;
    public $name;
    public $nameSpace;
    public $config = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        // < set Class Parameters >
        {
            $this->config = include realpath(__DIR__.'/config.php');
            $this->nameSpace = 'system\modules\\'.$this->config['name'];
            $this->controllerNamespace = 'system\modules\\'.$this->config['name'].'\controllers';
            $this->name = $this->config['name'];

        }
        // </ set Class Parameters >


        $this->initI18N();
        $this->initModules();
        $this->initMigrations();
        $this->registerTranslations();
    }


    /**
     * TranslationTrait manages methods for all translations used in Krajee extensions
     * @author Kartik Visweswaran <kartikv2@gmail.com>
     * @return void
     * @since  1.8.8
     *         Yii i18n messages configuration for generating translations
     *         source : https://github.com/kartik-v/yii2-krajee-base/blob/master/TranslationTrait.php
     *         Edited by : Yohanes Candrajaya <moo.tensai@gmail.com>
     * @property array $i18n
     */
    public function initI18N()
    {
        $reflector = new \ReflectionClass(get_class($this));
        $dir = dirname($reflector->getFileName());

        if (!empty($this->config['message'])) {
            foreach ($this->config['message'] as $message) {
                Yii::setAlias("@".$message, $dir);
                $config = [
                    'class'            => 'yii\i18n\PhpMessageSource',
                    'basePath'         => "@".$message."/messages",
                    'forceTranslation' => true
                ];
                $globalConfig = ArrayHelper::getValue(Yii::$app->i18n->translations, $message."*", []);
                if (!empty($globalConfig)) {
                    $config = array_merge(
                        $config,
                        is_array($globalConfig) ? $globalConfig : (array) $globalConfig
                    );
                }
                Yii::$app->i18n->translations[$message."*"] = $config;
            }
        }

    }

    protected function registerTranslations()
    {
        Yii::$app->i18n->translations[$this->name] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => Yii::$app->language,
            'basePath'       => '@vendor/yiiman/yii-module-user/src/module/messages',
            'fileMap'        => [
                $this->name => 'module.php',
            ],
        ];
    }



    public function initModules()
    {
        if (!empty($this->config['modules'])) {

            foreach ($this->config['modules'] as $key => $val) {
                $this->modules[$key] = $val;
            }
        }
    }
    function getFileList( $dir ) {
        // array to hold return value
        $retval = [];

        // add trailing slash if missing
        if ( substr( $dir, - 1 ) != "/" ) {
            $dir .= "/";
        }

        // open pointer to directory and read list of files
        try{

            $d = @dir( $dir );
            if (empty($d))return null;
        }catch (\Exception $e){
            return null;
        }
        while ( false !== ( $entry = $d->read() ) ) {
            // skip hidden files
            if ( $entry[0] == "." ) {
                continue;
            }
            if ( is_dir( "{$dir}{$entry}" ) ) {
                $retval[] = [
                    'name'    => "{$entry}",
                    'type'    => filetype( "{$dir}{$entry}" ),
                    'size'    => 0,
                    'lastmod' => filemtime( "{$dir}{$entry}" )
                ];
            } elseif ( is_readable( "{$dir}{$entry}" ) ) {
                $retval[] = [
                    'name'    => "{$entry}",
                    'type'    => mime_content_type( "{$dir}{$entry}" ),
                    'size'    => filesize( "{$dir}{$entry}" ),
                    'lastmod' => filemtime( "{$dir}{$entry}" )
                ];
            }
        }
        $d->close();

        return $retval;
    }
    public function initMigrations()
    {
        $classes = $this->getFileList(realpath(__DIR__.'/migration'));
        if (!empty($classes)) {
            foreach ($classes as $key => $val) {
                if ($val['type'] == 'text/x-php') {
                    $val['name'] = str_replace('.php', '', $val['name']);
                    $cname = $this->nameSpace.'\migration\\'.$val['name'];
                    $class = new $cname();
                    try {
                        $generate = $class->safeUp();
                    } catch (\Exception $e) {
                    }


                }

            }
        }


    }

    /**
     * Translates a message. This is just a wrapper of Yii::t
     * @param         $category
     * @param         $message
     * @param  array  $params
     * @param  null   $language
     * @return string
     * @see Yii::t
     */
    public static function t($category, $message, $params = [], $language = null)
    {

        return Yii::t($category, $message, $params, $language);
    }
}
