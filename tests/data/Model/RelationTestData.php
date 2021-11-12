<?php

declare(strict_types=1);

return [
    "__construct" => [
        "id" => 1,
        "issue_id" => 1,
        "issue_to_id" => 2,
        "relation_type" => \Blackmine\Model\Issue\Relation::RELATION_TYPE_RELATES
    ],
    "__expects" => [
        "id" => 1,
        "issue_id" => 1,
        "issue_to_id" => 2,
        "relation_type" => \Blackmine\Model\Issue\Relation::RELATION_TYPE_RELATES
    ],
    "__payload" => [
        "relation" => [
            "id" => 1,
            "issue_id" => 1,
            "issue_to_id" => 2,
            "relation_type" => \Blackmine\Model\Issue\Relation::RELATION_TYPE_RELATES
        ]
    ],
    "__implements" => [
        \Blackmine\Model\FetchableInterface::class
    ]
];
