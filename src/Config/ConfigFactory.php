<?php


namespace Rebuild\Config;


use Psr\Container\ContainerInterface;
use Symfony\Component\Finder\Finder;

class ConfigFactory
{
    public function __invoke()
    {
        $basePath = BASE_PATH . '/config/';
        $configFile = $this->readConfig($basePath . 'config.php');
        $autoloadConfig = $this->readPath([$basePath . 'autoload']);
        $configs = array_merge_recursive($configFile, ...$autoloadConfig);
        return new Config($configs);
    }

    protected function readConfig(string $string): array
    {
        $config = require $string;
        if (!is_array($config)) {
            return [];
        }
        return $config;
    }

    private function readPath(array $paths): array
    {
        $configs = [];
        $finder = new Finder();
        $finder->files()->in($paths)->name('*.php');
        foreach ($finder as $file) {
            $configs[] = [
                $file->getBasename('.php') => require $file->getRealPath()
            ];
        }
        return $configs;
    }

}