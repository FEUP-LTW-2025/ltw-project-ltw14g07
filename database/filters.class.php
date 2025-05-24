<?php
class Filters {
    public $languages;
    public $fields;

    public function __construct($languages, $fields) {
        $this->languages = $languages;
        $this->fields = $fields;
    }

    public static function getAllFilters($db) {
        // Get languages using the new Language class method
        $languages = self::getAllLanguages($db);
        
        // Get fields using the new Field class method
        $fields = self::getAllFields($db);

        return new Filters(
            array_column($languages, 'language'),
            array_column($fields, 'field')
        );
    }

    // New helper methods to maintain backward compatibility
    private static function getAllLanguages($db) {
        if (class_exists('Language')) {
            return Language::getAllLanguages($db);
        } else {
            // Fallback to your original implementation
            $stmt = $db->prepare('SELECT * FROM Language');
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    private static function getAllFields($db) {
        if (class_exists('Field')) {
            return Field::getAllFields($db);
        } else {
            // Fallback to your original implementation
            $stmt = $db->prepare('SELECT * FROM Field');
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    // New search functionality
    public static function searchFilters($db, string $searchTerm) {
        $languages = [];
        $fields = [];

        if (class_exists('Language')) {
            $languages = Language::searchLanguages($db, $searchTerm);
        }

        if (class_exists('Field')) {
            $fields = Field::searchFields($db, $searchTerm);
        }

        return new Filters($languages, $fields);
    }
}
?>
