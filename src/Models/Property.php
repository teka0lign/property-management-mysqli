<?php
namespace App\Models;

class Property
{
    public ?int $id;
    public int $user_id;
    public string $title;
    public ?string $description;
    public float $price;
    public string $address;
    public ?array $meta;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->user_id = (int)($data['user_id'] ?? 0);
        $this->title = (string)($data['title'] ?? '');
        $this->description = $data['description'] ?? null;
        $this->price = (float)($data['price'] ?? 0.0);
        $this->address = (string)($data['address'] ?? '');
        $this->meta = isset($data['meta']) && is_string($data['meta']) ? json_decode($data['meta'], true) : ($data['meta'] ?? null);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'address' => $this->address,
            'meta' => $this->meta,
        ];
    }
}
