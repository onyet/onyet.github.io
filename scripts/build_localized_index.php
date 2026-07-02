<?php

$root = dirname(__DIR__);
$sourceFile = $root . '/index.html';
$translationsFile = $root . '/assets/i18n/translations.json';

if (!file_exists($sourceFile) || !file_exists($translationsFile)) {
    fwrite(STDERR, "Required source files are missing.\n");
    exit(1);
}

$html = file_get_contents($sourceFile);
$translations = json_decode(file_get_contents($translationsFile), true);

if (!is_array($translations)) {
    fwrite(STDERR, "Failed to parse translations.json.\n");
    exit(1);
}

$targets = [
    'id' => '/id/',
    'zh' => '/zh/',
];
$flags = [
    'en' => '🇺🇸',
    'id' => '🇮🇩',
    'zh' => '🇨🇳',
];
$localeMap = [
    'en' => 'en_US',
    'id' => 'id_ID',
    'zh' => 'zh_CN',
];
$baseUrl = 'https://onyet.github.io';
$ogImage = $baseUrl . '/assets/portofolio-dian/image206.png';

libxml_use_internal_errors(true);

foreach ($targets as $lang => $path) {
    if (empty($translations[$lang])) {
        fwrite(STDERR, "Missing translations for {$lang}.\n");
        exit(1);
    }

    $localized = $html;
    $localized = preg_replace('/<html lang="[^"]+" dir="[^"]+">/', '<html lang="' . $lang . '" dir="ltr">', $localized, 1);

    $canonicalBlock = <<<HTML
  <!-- SEO_ALTERNATES_START -->
  <link rel="canonical" href="https://onyet.github.io{$path}">
  <link rel="alternate" hreflang="en" href="https://onyet.github.io/">
  <link rel="alternate" hreflang="id" href="https://onyet.github.io/id/">
  <link rel="alternate" hreflang="zh" href="https://onyet.github.io/zh/">
  <link rel="alternate" hreflang="x-default" href="https://onyet.github.io/">
  <!-- SEO_ALTERNATES_END -->
HTML;
    $localized = preg_replace('/<!-- SEO_ALTERNATES_START -->[\s\S]*?<!-- SEO_ALTERNATES_END -->/', $canonicalBlock, $localized, 1);

    $pageTitle = $translations[$lang]['page-title'] ?? ($translations['en']['page-title'] ?? '');
    $metaDescription = $translations[$lang]['meta-description'] ?? ($translations['en']['meta-description'] ?? '');
    $ogImageAlt = $translations[$lang]['og-image-alt'] ?? ($translations['en']['og-image-alt'] ?? 'Portfolio preview image');
    $pageUrl = $baseUrl . $path;
    $locale = $localeMap[$lang] ?? 'en_US';
    $alternateLocaleLines = [];
    foreach ($localeMap as $localeLang => $localeValue) {
        if ($localeLang === $lang) {
            continue;
        }
        $alternateLocaleLines[] = '  <meta property="og:locale:alternate" content="' . $localeValue . '">';
    }
    $alternateLocales = implode("\n", $alternateLocaleLines);

    $socialBlock = <<<HTML
  <!-- SEO_SOCIAL_START -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Dian Mukti Wibowo">
  <meta property="og:locale" content="{$locale}">
{$alternateLocales}
  <meta property="og:title" content="{$pageTitle}">
  <meta property="og:description" content="{$metaDescription}">
  <meta property="og:url" content="{$pageUrl}">
  <meta property="og:image" content="{$ogImage}">
  <meta property="og:image:alt" content="{$ogImageAlt}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{$pageTitle}">
  <meta name="twitter:description" content="{$metaDescription}">
  <meta name="twitter:image" content="{$ogImage}">
  <!-- SEO_SOCIAL_END -->
HTML;
    $localized = preg_replace('/<!-- SEO_SOCIAL_START -->[\s\S]*?<!-- SEO_SOCIAL_END -->/', $socialBlock, $localized, 1);

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Person',
                '@id' => $baseUrl . '/#person',
                'name' => 'Dian Mukti Wibowo',
                'url' => $baseUrl . '/',
                'image' => $ogImage,
                'jobTitle' => 'Software Engineer',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Purwokerto Timur',
                    'addressRegion' => 'Jawa Tengah',
                    'addressCountry' => 'ID',
                ],
                'email' => 'mailto:onyetcorp@gmail.com',
                'sameAs' => [
                    'https://github.com/onyet',
                    'https://www.linkedin.com/in/dian-mukti-wibowo-244576a6/',
                    'https://play.google.com/store/apps/dev?id=4679714779847784928',
                ],
            ],
            [
                '@type' => 'WebSite',
                '@id' => $baseUrl . '/#website',
                'url' => $baseUrl . '/',
                'name' => 'Dian Mukti Wibowo',
                'description' => $metaDescription,
                'inLanguage' => $lang,
                'publisher' => [
                    '@id' => $baseUrl . '/#person',
                ],
            ],
            [
                '@type' => 'WebPage',
                '@id' => $pageUrl . '#webpage',
                'url' => $pageUrl,
                'name' => $pageTitle,
                'description' => $metaDescription,
                'isPartOf' => [
                    '@id' => $baseUrl . '/#website',
                ],
                'about' => [
                    '@id' => $baseUrl . '/#person',
                ],
                'inLanguage' => $lang,
            ],
        ],
    ];
    $jsonBlock = "  <!-- SEO_JSONLD_START -->\n  <script type=\"application/ld+json\">\n" .
        json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) .
        "\n  </script>\n  <!-- SEO_JSONLD_END -->";
    $localized = preg_replace('/<!-- SEO_JSONLD_START -->[\s\S]*?<!-- SEO_JSONLD_END -->/', $jsonBlock, $localized, 1);

    $dom = new DOMDocument();
    $dom->loadHTML($localized, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $xpath = new DOMXPath($dom);

    $titleNode = $xpath->query('//title')->item(0);
    if ($titleNode) {
        $titleNode->nodeValue = $pageTitle;
    }

    $metaNode = $xpath->query('//meta[@name="description"]')->item(0);
    if ($metaNode) {
        $metaNode->setAttribute('content', $metaDescription);
    }

    $flagNode = $xpath->query('//*[@id="lang-fab-flag"]')->item(0);
    if ($flagNode && isset($flags[$lang])) {
        $flagNode->nodeValue = $flags[$lang];
    }

    foreach ($xpath->query('//*[@id="lang-modal"]//*[@data-lang]') as $button) {
        $buttonLang = $button->getAttribute('data-lang');
        $isActive = $buttonLang === $lang;
        $button->setAttribute('aria-pressed', $isActive ? 'true' : 'false');

        $classTokens = preg_split('/\s+/', trim($button->getAttribute('class'))) ?: [];
        $classTokens = array_values(array_filter($classTokens, static function ($token) {
            return $token !== 'bg-slate-50';
        }));
        if ($isActive) {
            $classTokens[] = 'bg-slate-50';
        }
        $button->setAttribute('class', implode(' ', array_unique($classTokens)));

        foreach ($button->getElementsByTagName('svg') as $svg) {
            $svg->setAttribute('style', 'visibility: ' . ($isActive ? 'visible' : 'hidden') . ';');
        }
    }

    foreach ($xpath->query('//*[@data-i18n]') as $node) {
        $key = $node->getAttribute('data-i18n');
        $value = $translations[$lang][$key] ?? ($translations['en'][$key] ?? null);
        if ($value === null) {
            continue;
        }

        if ($node->hasAttribute('data-i18n-attr')) {
            $attrs = array_filter(array_map('trim', explode(',', $node->getAttribute('data-i18n-attr'))));
            foreach ($attrs as $attr) {
                $node->setAttribute($attr, $value);
            }
            continue;
        }

        while ($node->firstChild) {
            $node->removeChild($node->firstChild);
        }
        $node->appendChild($dom->createTextNode($value));
    }

    $output = $dom->saveHTML();
    $output = preg_replace('~([("\'=])assets/~', '$1/assets/', $output);

    $targetDir = $root . '/' . $lang;
    if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
        fwrite(STDERR, "Failed to create directory {$targetDir}.\n");
        exit(1);
    }

    file_put_contents($targetDir . '/index.html', $output);
}

echo "Localized index pages generated.\n";
