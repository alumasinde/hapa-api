<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repository\CategoryRepository;
use App\Support\Response;

final class CategoryController
{
    public function __construct(private readonly CategoryRepository $categories = new CategoryRepository())
    {
    }

    public function index(): never
    {
        $categories = array_map(static fn (array $category): array => [
            'key' => $category['category_key'],
            'name' => $category['name'],
            'description' => $category['description'],
            'icon' => $category['icon'],
            'expires_after_minutes' => (int) $category['expires_after_minutes'],
        ], $this->categories->enabled());

        Response::json(['categories' => $categories]);
    }
}
