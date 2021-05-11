<?php declare(strict_types=1);
namespace sergiosgc\translation;

class Translation {
    static ?Translation $singleton = null;
    public ?mixed $_getDb = null;
    public ICache $cache;
    public $language = 'pt';
    public $territory = 'pt';
    public function __construct() {
        $this->cache = new NoopCache();
    }
    public static function singleton(): Translation {
        return static::$singleton ?? (static::$singleton = new Translation());

    }
    public function setLocale($locale) {
        $language = strtolower($locale);
        $territory = '';
        if (strpos($language, '_')) list($language, $territory) = explode('_', $language, 2);
        $this->language = $language;
        $this->territory = $territory;
    }
    public function setGetDatabase(callable $_getDb) : void {
        $this->_getDb = $_getDb;
    }
    public function getDb(): \Pdo {
        if (is_null($this->$_getDb)) throw new Exception('Translation::getDb() called before Translation::setGetDatabase()');
        return $this->_getDb();
    }
    public function getCache(): ICache { return $this->cache; }
    public function setCache(ICache $cache) { $this->cache = $cache; }
    public function translate(string $original, ?string $context = null): string {
        return TranslationString::translate(
            $translations = $this->getCache()->get(
                strtolower($this->language),
                [ '\sergiosgc\translation\TranslationString', 'dbGetTranslations'],
                strtolower($this->language)
            ),
            $original,
            $this->territory,
            $context);
    }
}