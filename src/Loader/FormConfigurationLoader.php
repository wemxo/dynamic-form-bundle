<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Loader;

use Iterator;
use Psr\Cache\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Wemxo\DynamicFormBundle\DTO\DynamicFormConfiguration;
use Wemxo\DynamicFormBundle\Exception\FormConfigurationNotFound;
use Wemxo\DynamicFormBundle\Parser\FormConfigurationParserInterface;
use Wemxo\DynamicFormBundle\Utils\Helper\DynamicFormHelper;

class FormConfigurationLoader implements FormConfigurationLoaderInterface
{
    private const CACHE_ITEM_KEY = 'wemxo_dynamic_form_configuration';
    private const CACHE_ITEM_TTL = 3600;
    private const FORM_CONFIG_KEY = 'form';

    private bool $recursive = true;
    private array $configPaths = [];
    private array $configurations = [];
    private bool $configurationLoaded = false;
    private ?CacheInterface $cache = null;
    private iterable $formConfigurationParsers;

    public function __construct(iterable $formConfigurationParsers)
    {
        $this->formConfigurationParsers = $formConfigurationParsers;
    }

    public function addConfigurationPath(string $configurationPath): self
    {
        if (!in_array($configurationPath, $this->configPaths)) {
            $this->configPaths[] = $configurationPath;
        }

        return $this;
    }

    public function setCache(?CacheInterface $cache): void
    {
        $this->cache = $cache;
    }

    public function setRecursive(bool $recursive): void
    {
        $this->recursive = $recursive;
    }

    public function getConfiguration(string $key): DynamicFormConfiguration
    {
        if (!$this->configurationLoaded) {
            $this->loadConfigurations();
            $this->configurationLoaded = true;
        }

        if (!array_key_exists($key, $this->configurations)) {
            throw new FormConfigurationNotFound($key);
        }

        return new DynamicFormConfiguration($key, $this->configurations[$key]);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function loadConfigurations(): void
    {
        $this->configurations = $this->cache
            ? $this->cache->get(self::CACHE_ITEM_KEY, function (ItemInterface $item) {
                $item->expiresAfter(self::CACHE_ITEM_TTL);

                return  $this->getFilesConfig();
            })
            : $this->getFilesConfig()
        ;
    }

    private function getFilesConfig(): array
    {
        if (empty($this->configPaths)) {
            return [];
        }

        $configurations = [];
        /** @var SplFileInfo $file */
        foreach ($this->getFiles() as $file) {
            $configurations = array_merge($configurations, $this->getFileConfigs($file));
        }

        return $configurations;
    }

    private function getFileConfigs(SplFileInfo $file): array
    {
        $formConfigurationParser = $this->getFormConfigurationParser($file->getRealPath());
        $fileConfigurations = [];
        $fileConfigurationKeyPrefix = str_replace(
            ['/', sprintf('.%s', $formConfigurationParser->getFileExtension())],
            ['#', ''],
            $file->getRelativePathname()
        );
        $this->browseFormConfiguration(
            $formConfigurationParser->parseFile($file->getRealPath()),
            $fileConfigurations,
            $fileConfigurationKeyPrefix
        );

        return $fileConfigurations;
    }

    private function browseFormConfiguration(array $fileContent, array &$fileConfigurations, string $configKeyPrefix): void
    {
        foreach ($fileContent as $key => $value) {
            if (self::FORM_CONFIG_KEY === $key) {
                $fileConfigurations[$configKeyPrefix] = $value;

                continue;
            }

            $this->browseFormConfiguration($value, $fileConfigurations, DynamicFormHelper::configKey($configKeyPrefix, $key));
        }
    }

    private function getFiles(): Iterator
    {
        $finder = (new Finder())
            ->files()
            ->in($this->configPaths)
        ;

        /** @var FormConfigurationParserInterface $formConfigurationParser */
        foreach ($this->formConfigurationParsers as $formConfigurationParser) {
            $finder->name(sprintf('*.%s', $formConfigurationParser->getFileExtension()));
        }

        if (!$this->recursive) {
            $finder->depth(0);
        }

        return $finder->getIterator();
    }

    private function getFormConfigurationParser(string $filename): FormConfigurationParserInterface
    {
        /** @var FormConfigurationParserInterface $formConfigurationParser */
        foreach ($this->formConfigurationParsers as $formConfigurationParser) {
            if ($formConfigurationParser->supportFile($filename)) {
                return $formConfigurationParser;
            }
        }

        throw new RuntimeException(sprintf('Unable to find form configuration parser for %s', $filename));
    }
}
