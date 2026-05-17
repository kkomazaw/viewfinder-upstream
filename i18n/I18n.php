<?php
/**
 * I18n - Internationalization Class
 * Handles translation and locale management for Viewfinder Upstream
 *
 * @package Viewfinder
 * @author Viewfinder Team
 * @license Apache-2.0
 */

class I18n {
    private static $instance = null;
    private $locale = 'en';
    private $translations = [];
    private $fallbackLocale = 'en';
    private $availableLocales = ['en', 'ja'];

    /**
     * Private constructor for singleton pattern
     */
    private function __construct() {
        $this->locale = $this->detectLocale();
        $this->loadTranslations($this->locale);
    }

    /**
     * Get singleton instance
     * @return I18n
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Set the current locale
     * @param string $locale
     */
    public function setLocale($locale) {
        if ($this->isValidLocale($locale)) {
            $this->locale = $locale;
            $this->loadTranslations($locale);
        }
    }

    /**
     * Get the current locale
     * @return string
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * Detect locale from various sources
     * Priority: 1) Session, 2) Cookie, 3) Browser, 4) Default
     * @return string
     */
    private function detectLocale() {
        // 1. Check session
        if (isset($_SESSION['locale']) && $this->isValidLocale($_SESSION['locale'])) {
            return $_SESSION['locale'];
        }

        // 2. Check cookie
        if (isset($_COOKIE['locale']) && $this->isValidLocale($_COOKIE['locale'])) {
            return $_COOKIE['locale'];
        }

        // 3. Check browser language
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if ($this->isValidLocale($browserLang)) {
                return $browserLang;
            }
        }

        // 4. Return default
        return $this->fallbackLocale;
    }

    /**
     * Validate if locale is supported
     * @param string $locale
     * @return bool
     */
    private function isValidLocale($locale) {
        return in_array($locale, $this->availableLocales, true);
    }

    /**
     * Load translations for a specific locale
     * @param string $locale
     */
    private function loadTranslations($locale) {
        // Validate locale first
        if (!$this->isValidLocale($locale)) {
            $locale = $this->fallbackLocale;
        }

        $file = __DIR__ . "/locales/{$locale}.php";
        $realFile = realpath($file);
        $basePath = realpath(__DIR__ . '/locales/');

        if ($realFile && $basePath && strpos($realFile, $basePath) === 0 && file_exists($realFile)) {
            $this->translations = require $realFile;
        } else {
            // Load fallback language
            $fallbackFile = __DIR__ . "/locales/{$this->fallbackLocale}.php";
            if (file_exists($fallbackFile)) {
                $this->translations = require $fallbackFile;
            } else {
                $this->translations = [];
            }
        }
    }

    /**
     * Translate a key with optional parameters
     * @param string $key Translation key
     * @param array $params Parameters for replacement
     * @return string Translated string
     */
    public function translate($key, $params = []) {
        $translation = $this->translations[$key] ?? $key;

        // Replace parameters
        foreach ($params as $placeholder => $value) {
            $translation = str_replace("{{$placeholder}}", $value, $translation);
        }

        return $translation;
    }

    /**
     * Get list of available locales
     * @return array
     */
    public function getAvailableLocales() {
        return $this->availableLocales;
    }

    /**
     * Get locale name for display
     * @param string $locale
     * @return string
     */
    public function getLocaleName($locale) {
        $names = [
            'en' => 'English',
            'ja' => '日本語',
        ];
        return $names[$locale] ?? $locale;
    }

    /**
     * Get text direction for locale
     * @return string 'ltr' or 'rtl'
     */
    public function getTextDirection() {
        $rtlLocales = ['ar', 'he', 'fa', 'ur'];
        return in_array($this->locale, $rtlLocales, true) ? 'rtl' : 'ltr';
    }
}

/**
 * Global translation function
 * @param string $key Translation key
 * @param array $params Parameters for replacement
 * @return string Translated string
 */
function __($key, $params = []) {
    return I18n::getInstance()->translate($key, $params);
}

/**
 * Global translation function with HTML escaping
 * @param string $key Translation key
 * @param array $params Parameters for replacement
 * @return string HTML-escaped translated string
 */
function __e($key, $params = []) {
    return htmlspecialchars(__($key, $params), ENT_QUOTES, 'UTF-8');
}

/**
 * Set the current locale
 * @param string $locale
 */
function setLocale($locale) {
    I18n::getInstance()->setLocale($locale);
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION['locale'] = $locale;
    }
    setcookie('locale', $locale, time() + (86400 * 365), '/');
}

/**
 * Get the current locale
 * @return string
 */
function getLocale() {
    return I18n::getInstance()->getLocale();
}

/**
 * Get available locales
 * @return array
 */
function getAvailableLocales() {
    return I18n::getInstance()->getAvailableLocales();
}

/**
 * Get locale name for display
 * @param string $locale
 * @return string
 */
function getLocaleName($locale) {
    return I18n::getInstance()->getLocaleName($locale);
}

/**
 * Get text direction for current locale
 * @return string 'ltr' or 'rtl'
 */
function getTextDirection() {
    return I18n::getInstance()->getTextDirection();
}

/**
 * Format date according to locale
 * @param int $timestamp
 * @param string $format 'long' or 'short'
 * @return string
 */
function formatDate($timestamp, $format = 'long') {
    $locale = getLocale();

    $formats = [
        'en' => [
            'long' => 'F j, Y \a\t g:i A',
            'short' => 'm/d/Y'
        ],
        'ja' => [
            'long' => 'Y年n月j日 H:i',
            'short' => 'Y/m/d'
        ]
    ];

    return date($formats[$locale][$format] ?? $formats['en'][$format], $timestamp);
}

/**
 * Format number according to locale
 * @param float $number
 * @param int $decimals
 * @return string
 */
function formatNumber($number, $decimals = 0) {
    $locale = getLocale();

    $formats = [
        'en' => ['decimal' => '.', 'thousands' => ','],
        'ja' => ['decimal' => '.', 'thousands' => ',']
    ];

    $format = $formats[$locale] ?? $formats['en'];
    return number_format($number, $decimals, $format['decimal'], $format['thousands']);
}
