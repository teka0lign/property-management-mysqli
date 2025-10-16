<?php
namespace App\Repositories;

use App\Models\Property;
use mysqli;

final class PropertyRepository
{
    private mysqli $db;

    public function __construct(mysqli $conn)
    {
        $this->db = $conn;
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare('SELECT id,user_id,title,description,price,address,meta FROM properties ORDER BY id DESC LIMIT ? OFFSET ?');
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        $res = $stmt->get_result();
        $items = [];
        while ($row = $res->fetch_assoc()) {
            $items[] = new Property($row);
        }
        $stmt->close();
        return $items;
    }

    public function findById(int $id): ?Property
    {
        $stmt = $this->db->prepare('SELECT id,user_id,title,description,price,address,meta FROM properties WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row ? new Property($row) : null;
    }

    public function create(array $data): Property
    {
        $metaJson = isset($data['meta']) ? json_encode($data['meta']) : null;
        $stmt = $this->db->prepare('INSERT INTO properties (user_id,title,description,price,address,meta) VALUES (?,?,?,?,?,?)');
        $stmt->bind_param('issdss', $data['user_id'], $data['title'], $data['description'], $data['price'], $data['address'], $metaJson);
        if (!$stmt->execute()) {
            throw new \RuntimeException('Insert failed: ' . $stmt->error);
        }
        $id = $stmt->insert_id;
        $stmt->close();
        return $this->findById((int)$id);
    }

    public function update(int $id, array $data): ?Property
    {
        $metaJson = isset($data['meta']) ? json_encode($data['meta']) : null;
        $stmt = $this->db->prepare('UPDATE properties SET title = ?, description = ?, price = ?, address = ?, meta = ? WHERE id = ?');
        $stmt->bind_param('sdsisi', $data['title'], $data['description'], $data['price'], $data['address'], $metaJson, $id);
        if (!$stmt->execute()) {
            throw new \RuntimeException('Update failed: ' . $stmt->error);
        }
        $stmt->close();
        return $this->findById($id);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM properties WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }
}
