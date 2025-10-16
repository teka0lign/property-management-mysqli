<?php
namespace App\Controllers;

use App\Repositories\PropertyRepository;
use App\Models\Property;

final class PropertyController
{
    private PropertyRepository $repo;

    public function __construct(PropertyRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(): void
    {
        $items = array_map(fn(Property $p) => $p->toArray(), $this->repo->findAll());
        $this->jsonResponse($items);
    }

    public function show(int $id): void
    {
        $p = $this->repo->findById($id);
        if (!$p) {
            $this->jsonError('Not found', 404);
            return;
        }
        $this->jsonResponse($p->toArray());
    }

    public function store(array $input, int $userId): void
    {
        // Basic validation
        if (empty($input['title']) || empty($input['price']) || empty($input['address'])) {
            $this->jsonError('Validation failed', 422);
            return;
        }
        $data = [
            'user_id' => $userId,
            'title' => htmlspecialchars($input['title']),
            'description' => $input['description'] ?? null,
            'price' => (float)$input['price'],
            'address' => htmlspecialchars($input['address']),
            'meta' => $input['meta'] ?? null,
        ];

        $property = $this->repo->create($data);
        $this->jsonResponse($property->toArray(), 201);
    }

    public function update(int $id, array $input): void
    {
        $p = $this->repo->findById($id);
        if (!$p) { $this->jsonError('Not found', 404); return; }
        $data = [
            'title' => $input['title'] ?? $p->title,
            'description' => $input['description'] ?? $p->description,
            'price' => isset($input['price']) ? (float)$input['price'] : $p->price,
            'address' => $input['address'] ?? $p->address,
            'meta' => $input['meta'] ?? $p->meta,
        ];
        $updated = $this->repo->update($id, $data);
        $this->jsonResponse($updated->toArray());
    }

    public function destroy(int $id): void
    {
        $p = $this->repo->findById($id);
        if (!$p) { $this->jsonError('Not found', 404); return; }
        $this->repo->delete($id);
        http_response_code(204);
    }

    private function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
    }

    private function jsonError(string $message, int $status = 400): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => $message], JSON_UNESCAPED_UNICODE);
    }
}
