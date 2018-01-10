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
    private function getSecretKey()
    {
        return $this->config['secret'];
    }

    public function getTranslationsFullPath()
    {
        return $this->config['translationsPath'];
    }

    public function getTranslationsFullJsPath()
    {
        return $this->config['translationsJsPath'];
    }

    public function getTranslationsJsFile()
    {
        return $this->config['translationsJsFile'];
    }

    /**
     * @param string $locale
     * @return array
     */
    public function fetchTranslations(string $locale)
    {
        $translations = json_decode(file_get_contents(self::BASE_URL . 'export/' . $this->getSecretKey() . '/' . $locale), true);

        return $translations;
    }
}