<?php

namespace craftsnippets\paginationtoolbox;

use craftsnippets\paginationtoolbox\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

use craft\events\RegisterTemplateRootsEvent;
use craft\web\View;

// use craft\web\UrlManager;
// use craft\events\RegisterUrlRulesEvent;

class PaginationToolbox extends Plugin
{

    public static $plugin;
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = false;
    public bool $hasCpSection = false;

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set('pagination', \craftsnippets\paginationtoolbox\variables\PaginationVariable::class);
            }
        );

        $this->setComponents([
            'pagination' => \craftsnippets\paginationtoolbox\services\PaginationService::class,
        ]);

        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function(RegisterTemplateRootsEvent $event) {
                $keyword = 'pagination-toolbox';
                $event->roots[$keyword] = __DIR__ . '/templates';
            }
        );    

    }

    protected function createSettingsModel(): ?craft\base\Model
    {
        return new Settings();
    }

}
