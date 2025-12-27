<?php

declare(strict_types=1);

namespace BpolNet\Bundle\LangleyBundle\Service;

/**
 * Marek Krokwa <marek.krokwa@gmail.com>
 */
class Langley
{
    const BASE_URL = 'https://langley.pl/';

    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    private function getSecretKey() : string
    {
        return $this->config['secret'];
    }

    public function getTranslationsFullPath() : string
    {
        return $this->config['translationsPath'];
    }

    public function getTranslationsFullJsPath() : string
    {
        return $this->config['translationsJsPath'];
    }

    public function getTranslationsJsFile() : string
    {
        return $this->config['translationsJsFile'];
    }

    public function getVariableJsObjectName() : string
    {
        return $this->config['variableJsObject'];
    }

    public function fetchTranslations(string $locale): array
    {
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
        $content = file_get_contents(self::BASE_URL . 'export/' . $this->getSecretKey() . '/' . $locale);

        return json_decode($content, true);
    }
}
