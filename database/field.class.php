<?php
class Field {
    public static function searchFields(PDO $db, string $searchTerm, int $limit = 10): array {
        $stmt = $db->prepare("
            SELECT field FROM Field 
            WHERE field LIKE ? 
            ORDER BY field
            LIMIT ?
        ");
        $stmt->execute(['%' . $searchTerm . '%', $limit]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getAllFields(PDO $db): array {
        $stmt = $db->query("SELECT field FROM Field ORDER BY field");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function addField(PDO $db, string $field): bool {
        $stmt = $db->prepare("INSERT INTO Field (field) VALUES (?)");
        return $stmt->execute([$field]);
    }

    public static function deleteField(PDO $db, string $field): bool {
        $stmt = $db->prepare("DELETE FROM Field WHERE field = ?");
        return $stmt->execute([$field]);
    }
}
?>