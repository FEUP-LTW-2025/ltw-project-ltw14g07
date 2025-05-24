<?php
class Language {
    public static function searchLanguages(PDO $db, string $searchTerm, int $limit = 10): array {
        $stmt = $db->prepare("
            SELECT language FROM Language 
            WHERE language LIKE ? 
            ORDER BY language
            LIMIT ?
        ");
        $stmt->execute(['%' . $searchTerm . '%', $limit]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getAllLanguages(PDO $db): array {
        $stmt = $db->query("SELECT language FROM Language ORDER BY language");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function addLanguage(PDO $db, string $language): bool {
        $stmt = $db->prepare("INSERT INTO Language (language) VALUES (?)");
        return $stmt->execute([$language]);
    }

    public static function deleteLanguage(PDO $db, string $language): bool {
        $stmt = $db->prepare("DELETE FROM Language WHERE language = ?");
        return $stmt->execute([$language]);
    }
}
?>