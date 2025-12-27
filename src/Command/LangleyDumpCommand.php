<?php

declare(strict_types=1);

namespace BpolNet\Bundle\LangleyBundle\Command;

use BpolNet\Bundle\LangleyBundle\Service\Langley;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Marek Krokwa <marek.krokwa@gmail.com>
 */
class LangleyDumpCommand extends Command
{

    const ARGUMENT_LOCALES = 'locales';

    protected static $defaultName = 'langley:dump';

    private Langley $langley;

    public function __construct(Langley $langley)
    {
        $this->langley = $langley;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(self::ARGUMENT_LOCALES, InputArgument::REQUIRED, 'Locales which you are using in your application in ISO 639-1 format (2 chars). Ex: en,fr')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $locales = explode(',', $input->getArgument(self::ARGUMENT_LOCALES));

        $javascript = [];

        foreach ($locales as $locale) {
            $output->writeln(sprintf('Fetching locale %s', $locale));

            try {
                $translations = $this->langley->fetchTranslations($locale);
            }
            catch (Exception) {
                $output->writeln(sprintf('Nothing to save. Check if %s locale is available.', $locale));
                continue;
            }

            $catalogues = [
                'messages' => [],
            ];

            if (0 !== sizeof($translations)) {
                $javascript[$locale] = [];

                foreach ($translations as $k => $translation) {
                    $catalogues['messages'][strtolower($k)] = $translation['translation'];

                    foreach ($translation['tags'] as $tag) {
                        if ($tag['name'] === 'intl-icu') {
                            $catalogues['intl-icu'][strtolower($k)] = $translation['translation'];
                            continue;
                        }

                        $catalogues[$tag['name']][strtolower($k)] = $translation['translation'];
                    }
                }

                foreach ($catalogues as $catalogueName => $catalogue) {
                    if ($catalogueName === 'intl-icu') {
                        $filepath = sprintf(
                            '%s/messages+intl-icu.%s.php',
                            $this->langley->getTranslationsFullPath(),
                            $locale
                        );
                    }
                    else {
                        $filepath = sprintf(
                            '%s/%s.%s.php',
                            $this->langley->getTranslationsFullPath(),
                            $catalogueName,
                            $locale
                        );
                    }

                    file_put_contents($filepath, "<?php\n\nreturn " . var_export($catalogue, true) . ';');
                }

                $output->writeln('Saving');
            }
            else {
                $output->writeln('Nothing to save !');
            }
        }

        $this->saveJsTranslations($output, $javascript);

        $output->writeln('Im done');

        return 0;
    }

    private function saveJsTranslations(OutputInterface $output, array $javascript): void
    {
        $jsTranslations = 'var ' . $this->langley->getVariableJsObjectName() . ' = {};';

        foreach ($javascript as $locale => $items) {
            $jsTranslations .= $this->langley->getVariableJsObjectName() . '.' . strtolower($locale) . '=' . json_encode($items, JSON_UNESCAPED_UNICODE) . ';';
        }

        $filePath = realpath($this->langley->getTranslationsFullJsPath()) . '/' . $this->langley->getTranslationsJsFile();

        if (file_put_contents($filePath, $jsTranslations)) {
            $output->writeln('<info> ✔ ' . $filePath . '</info>');
        }
        else {
            $output->writeln('<error> ✘ ' . $filePath . ' failed</error>');
        }
    }

}
