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
            ->addArgument('locales', InputArgument::REQUIRED, 'Locales which you are using in your application in ISO 639-1 format (2 chars). Ex: en,fr')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $locales = explode(',', $input->getArgument('locales'));

        $javascript = [];

        foreach ($locales as $locale)
        {
            $output->writeln(sprintf('Fetching locale %s', $locale));
            $translations = $this->langley->fetchTranslations($locale);

            if (is_array($translations) && 0 != sizeof($translations))
            {
                $dump = [];

                $javascript[$locale] = [];

                foreach ($translations as $k => $translation)
                {
                    $dump[strtolower($k)] = $translation['translation'];

                    if (isset($translation['tags']) && sizeof($translation['tags']))
                    {
                        foreach ($translation['tags'] as $tag)
                        {
                            if ($tag == 'javascript')
                            {
                                $javascript[$locale][strtolower($k)] = $translation['translation'];
                            }
                        }
                    }
                }

                $file = $this->langley->getTranslationsFullPath() . '/messages.' . $locale . '.php';

                if (file_put_contents($file, "<?php\n\nreturn " . var_export($dump, 1) . ';'))
                {
                    $output->writeln('<info>' . $file . ' saved</info>');
                }
                else
                {
                    $output->writeln('<error>' . $file . ' failed</error>');
                }

                $output->writeln(sprintf('Saving'));
            }
            else
            {
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
        $jsTranslations = 'var Trans = {};';

        foreach ($javascript as $locale => $items)
        {
            $jsTranslations .= 'Trans.' . strtolower($locale) . '=' . json_encode($items, JSON_UNESCAPED_UNICODE) . ';';
        }

        $filePath = $this->langley->getTranslationsFullJsPath() . '/' . $this->langley->getTranslationsJsFile();

        $output->writeln('Javascripts translations');

        if (file_put_contents($filePath, $jsTranslations))
        {
            $output->writeln('<info>' . $filePath . ' saved</info>');
        }
        else
        {
            $output->writeln('<error>' . $filePath . ' failed</error>');
        }
    }

}