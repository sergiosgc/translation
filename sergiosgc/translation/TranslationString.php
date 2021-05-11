<?php declare(strict_types=1);
namespace sergiosgc\translation;

class TranslationString implements \sergiosgc\crud\Describable, \ArrayAccess {
    use \sergiosgc\crud\ArrayAccess;
    use \sergiosgc\crud\CRUD;
    public int $id;
    public string $value;
    public string $context;
    public bool $in_use;

    public static function dbTableName($readOperation = false): string {
        return 'localization.string';
    }
    public static function describeFields(): array {
        return [
            'id' => [ 'type' => 'int', 'db:primarykey' => true, 'ui:widget' => 'hidden' ],
            'value' => [ 
                'type' => 'string', 
                'title' => __('Value'), 
            ],
            'context' => [ 
                'type' => 'string', 
                'title' => __('Context'), 
            ],
            'in_use' => [ 
                'type' => 'boolean', 
                'title' => __('In use'), 
            ],
        ];
    }
    public static function dbGetTranslations(string $language): array {
        return array_reduce(
            static::dbFetchAll(<<<EOQ
SELECT 
 string.value AS original,
 string.context AS context,
 translation.locale_territory AS locale_territory,
 translation.value AS translation
FROM
 localization.string
 JOIN localization.translation ON (translation.string = string.id)
WHERE
translation.locale_language = ?
EOQ,            strtolower($language)),
            function($result, $row) {
                $index1 = $row['original'];
                $index2 = $row['locale_territory'];
                $index3 = $row['context'];
                if (!isset($result[$index1])) $result[$index1] = [];
                if (!isset($result[$index1][$index2])) $result[$index1][$index2] = [];
                $result[$index1][$index2][$index3] = $row['translation'];

                return $result;
            },
            []);
    }
    public static function translate(array $translations, string $original, string $territory = '', $context = null) {
        if (!isset($translations[$original])) return $original;
        if (!isset($translations[$original][$territory])) $territory = '';
        if (!isset($translations[$original][$territory]) || 0 === count($translations[$original][$territory])) return $original;
        if (!isset($translations[$original][$territory][$context])) $context = array_keys($translations[$original][$territory])[0];
        return $translations[$original][$territory][$context];
    }
}