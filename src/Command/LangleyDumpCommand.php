<?php

/**
 * This file is part of the BpolNet company package.
 *
 * Marek Krokwa <marek.krokwa@bpol.net>
 */

namespace BpolNet\Bundle\LangleyBundle\Command;

use BpolNet\Bundle\LangleyBundle\Service\Langley;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LangleyDumpCommand extends Command
{

    const ARGUMENT_LOCALES = 'locales';

    /**
     * @var Langley
     */
    private $langley;

    /**
     * @param Langley $langley
     */
    public function __construct(Langley $langley)
    {
        $this->langley = $langley;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('langley:dump')
            ->addArgument(self::ARGUMENT_LOCALES, InputArgument::REQUIRED, 'Locales which you are using in your application in ISO 639-1 format (2 chars). Ex: en,fr')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $locales = explode(',', $input->getArgument(self::ARGUMENT_LOCALES));

        $javascript = [];

        foreach ($locales as $locale) {
            $output->writeln(sprintf('Fetching locale %s', $locale));
            $translations = $this->langley->fetchTranslations($locale);

            $catalogues = [
                'messages' => [],
            ];

            if (is_array($translations) && 0 !== sizeof($translations)) {
                $javascript[$locale] = [];

                foreach ($translations as $k => $translation) {
                    $catalogues['messages'][strtolower($k)] = $translation['translation'];

                    if (isset($translation['tags']) && sizeof($translation['tags'])) {
                        foreach ($translation['tags'] as $tag) {
                            if ($tag === 'javascript') {
                                $javascript[$locale][strtolower($k)] = $translation['translation'];
                                continue;
                            }

                            $catalogues[$tag][strtolower($k)] = $translation['translation'];
                        }
                    }
                }

                foreach ($catalogues as $catalogueName => $catalogue) {
                    file_put_contents(
                        sprintf(
                            '%s/%s.%s.php',
                            $this->langley->getTranslationsFullPath(),
                            $catalogueName,
                            $locale
                        ), "<?php\n\nreturn " . var_export($catalogue, 1) . ';');
                }

                $output->writeln(sprintf('Saving'));
            }
            else {
                $output->writeln('Nothing to save !');
            }
        }

        $this->saveJsTranslations($output, $javascript);

        $output->writeln('Im done');
    }

    /**
     * @param OutputInterface $output
     * @param array $javascript
     */
    private function saveJsTranslations(OutputInterface $output, array $javascript)
    {
        $jsTranslations = 'var ' . $this->langley->getVariableJsObjectName() . ' = {};';

        foreach ($javascript as $locale => $items)
        {
            $jsTranslations .= $this->langley->getVariableJsObjectName() . '.' . strtolower($locale) . '=' . json_encode($items, JSON_UNESCAPED_UNICODE) . ';';
        }

        $filePath = realpath($this->langley->getTranslationsFullJsPath()) . '/' . $this->langley->getTranslationsJsFile();

        if (file_put_contents($filePath, $jsTranslations))
        {
            $output->writeln('<info> ✔ ' . $filePath . '</info>');
        }
        else
        {
            $output->writeln('<error> ✘ ' . $filePath . ' failed</error>');
        }
    }

}