<?php
class Category {
    public static function create(PDO $db, string $name, string $description, int $createdBy): bool {
        $stmt = $db->prepare("INSERT INTO ServiceCategories (Name, Description, CreatedBy) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $description, $createdBy]);
    }
    
    public static function getAll(PDO $db): array {
        $stmt = $db->query("SELECT * FROM ServiceCategories ORDER BY Name");
        return $stmt->fetchAll();
    }
    
    public static function delete(PDO $db, int $categoryID): bool {
        $stmt = $db->prepare("DELETE FROM ServiceCategories WHERE CategoryID = ?");
        return $stmt->execute([$categoryID]);
    }
    
    public static function getCount(PDO $db): int {
        return $db->query("SELECT COUNT(*) FROM ServiceCategories")->fetchColumn();
    }
    public static function update(PDO $db, int $categoryID, string $name, string $description): bool {
        $stmt = $db->prepare("UPDATE ServiceCategories SET Name = ?, Description = ? WHERE CategoryID = ?");
        return $stmt->execute([$name, $description, $categoryID]);
    }

    public static function getById(PDO $db, int $categoryID): ?array {
        $stmt = $db->prepare("SELECT * FROM ServiceCategories WHERE CategoryID = ?");
        $stmt->execute([$categoryID]);
        return $stmt->fetch() ?: null;
    }
}
?>