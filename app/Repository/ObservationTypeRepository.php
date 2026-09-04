<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;

final class ObservationTypeRepository
{
    public function findForCategory(string $categoryKey, string $observationKey): ?array
    {
        $statement = Connection::get()->prepare('SELECT ot.* FROM observation_types ot JOIN category_observation_types cot ON cot.observation_type_id = ot.id JOIN categories c ON c.id = cot.category_id WHERE c.category_key = :category_key AND ot.observation_key = :observation_key LIMIT 1');
        $statement->execute(['category_key' => $categoryKey, 'observation_key' => $observationKey]);

        return $statement->fetch() ?: null;
    }
}
