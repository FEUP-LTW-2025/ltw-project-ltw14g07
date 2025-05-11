<?php
    class Filters {
        public $languages;
        public $fields;

    public function __construct($languages, $fields) {
      $this->languages = $languages;
      $this->fields = $fields;
    }

    public static function getAllFilters($db) {
        $stmt1 = $db->prepare('SELECT * FROM Language');
        $stmt2 = $db->prepare('SELECT * FROM Field');

        $languages = $stmt1->execute();
        $fields = $stmt2->execute();

        $languages = $stmt1->fetchAll();
        $fields = $stmt2->fetchAll();

        return new Filters (
            array_column($languages, 'language'),
            array_column($fields, 'field')
        );
    }

}

?>


