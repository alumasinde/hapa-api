<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repository\ModeRepository;
use App\Support\Response;

final class ModeController
{
    public function __construct(private readonly ModeRepository $modes = new ModeRepository())
    {
    }

    public function index(): never
    {
        Response::json(['modes' => $this->modes->active()]);
    }
}
