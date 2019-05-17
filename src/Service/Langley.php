<?php

/**
 * This file is part of the BpolNet company package.
 *
 * Marek Krokwa <marek.krokwa@bpol.net>
 */

namespace BpolNet\Bundle\LangleyBundle\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class Langley
{
    const BASE_URL = 'http://langley.pl/';

    /**
     * @var array
     */
    private $config;

    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(array $config, KernelInterface $kernel)
    {
        $this->config = $config;
        $this->kernel = $kernel;
    }

    /**
     * @return string
     */
    private function getSecretKey() : string
    {
        return $this->config['secret'];
    }

    /**
     * @return string
     */
    public function getTranslationsFullPath() : string
    {
        return $this->config['translationsPath'];
    }

    /**
     * @return string
     */
    public function getTranslationsFullJsPath() : string
    {
        return $this->config['translationsJsPath'];
    }

    /**
     * @return string
     */
    public function getTranslationsJsFile() : string
    {
        return $this->config['translationsJsFile'];
    }

    /**
     * @return string
     */
    public function getVariableJsObjectName() : string
    {
        return $this->config['variableJsObject'];
    }

    public function fetchTranslations(string $locale): array
    {
        $content = file_get_contents(self::BASE_URL . 'export/' . $this->getSecretKey() . '/' . $locale);

        $translations = json_decode($content, true);

        return $translations;
    }
}