<?php
namespace Blog;

// use Zend\ModuleManager\ModuleEvent;
// use Zend\ModuleManager\ModuleManager;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    /* public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();
        
        // Registering a listener at default priority, 1, which will trigger
        // after the ConfigListener merges config.
        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig']);
    }
    
    public function onMergeConfig(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $config         = $configListener->getMergedConfig(false);
        
        // Modify the configuration; here, we'll remove a specific key:
        if (isset($config['some_key'])) {
            unset($config['some_key']);
        }
        
        // Pass the changed configuration back to the listener:
        $configListener->setMergedConfig($config);
    } */
}