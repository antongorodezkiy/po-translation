<?php

namespace TeamleadPower\POTranslator;

use cli;
use Gettext;
use Gettext\Translations;
use Yandex\Translate\Translator as YandexTranslator;
use Yandex\Translate\Exception as YandexTranslatorException;

class Translator
{
    protected $arguments = [];
    protected $translations = null;
    protected $translateDirection = null;
    protected $yandexTranslator = null;
    
    public function __construct()
    {
        cli\line('PO Translator (using Yandex Translator https://translate.yandex.com )');
            
        if (php_sapi_name() != 'cli') {
            $this->error('Must run from command line');
        }
        
        $this->parseArgs();
        
        $this->initYandexTranslator();
        
        $this->loadTranslations();
        
        $this->translate();
        
        $this->saveTranslations();
        
        cli\line('Finished, closing PO Translator.');
    }
    
    private function error($text)
    {
        cli\err('%rFinished with error: '.$text.'%n');
        exit;
    }
    
    protected function parseArgs()
    {
        $this->arguments = new \cli\Arguments();
        
        // flgas
        $this->arguments->addFlag(['help', 'h'], 'Show this help screen');
        
        // options
        $this->arguments->addOption(['api-key'], [
            'default'     => null,
            'description' => 'Yandex API key'
        ]);
        
        $this->arguments->addOption(['src-po'], [
            'default'     => null,
            'description' => 'source .po-file path'
        ]);
        
        $this->arguments->addOption(['src-lang'], [
            'default'     => 'en',
            'description' => 'source language'
        ]);
        
        $this->arguments->parse();
        
        if ($this->arguments['help']) {
            echo $this->arguments->getHelpScreen();
            echo "\n\n";
            exit;
        }
    }
    
    protected function initYandexTranslator()
    {
        if (empty($this->arguments['api-key'])) {
			$this->error('Yandex Translator API key is required in option api-key');
            exit;
		}
        
        $this->yandexTranslator = new YandexTranslator($this->arguments['api-key']);
    }
    
    protected function loadTranslations()
    {
        if ( ! file_exists($this->arguments['src-po'])) {
			$this->error('src-po file not found');
            exit;
		}
        
        $this->translations = Translations::fromPoFile($this->arguments['src-po']);
        list($code) = explode('_', $this->translations->getLanguage());
        $this->translateDirection = ($this->arguments['src-lang'] ?: 'en').'-'.$code;
    }
    
    protected function translate()
    {
        $translations_count = count($this->translations);
		$progress = new \cli\progress\Bar(
			'Processing translations',
			$translations_count
		);
        
        foreach($this->translations as $translation) {
            if (!$translation->getTranslation()) {
                
                $translatedText = $this->yandexTranslator->translate(
                    $translation->getOriginal(),
                    $this->translateDirection
                );
                
                $translation->setTranslation($translatedText);
                
                $this->saveTranslations();
            }
            $progress->tick();
        }
    }
    
    protected function saveTranslations()
    {
        Gettext\Generators\Po::toFile($this->translations, $this->arguments['src-po']);
    }
}
