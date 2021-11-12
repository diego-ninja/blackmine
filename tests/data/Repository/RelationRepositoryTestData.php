<?php

declare(strict_types=1);

use Blackmine\Model\Issue\Relation;

return [
    "__methods" => [
        "get" => [
            1 => [
                "__success" => [
                    "__input" => [
                        "relation" => [
                            "id" => 1,
                            "issue_id" => 1,
                            "issue_to_id" => 2,
                            "relation_type" => Relation::RELATION_TYPE_RELATES
                        ]
                    ],
                    "__output" => (new Relation())->fromArray([
                        "id" => 1,
                        "issue_id" => 1,
                        "issue_to_id" => 2,
                        "relation_type" => Relation::RELATION_TYPE_RELATES
                    ])
                ],
                "__error" => [
                    401 => \Blackmine\Exception\Api\UnauthorizedApiException::class,
                    403 => \Blackmine\Exception\Api\InaccessibleResourceException::class,
                    404 => \Blackmine\Exception\Api\EntityNotFoundException::class,
                    500 => Error::class

                ]
            ]
        ],
        "all" => [
            "__error" => \Blackmine\Exception\MethodNotImplementedException::class
        ],
        "search" => [
            "__error" => \Blackmine\Exception\MethodNotImplementedException::class
        ],
        "create" => [
            "__error" => \Blackmine\Exception\MethodNotImplementedException::class
        ],
        "update" => [
            "__error" => \Blackmine\Exception\MethodNotImplementedException::class
        ],
        "delete" => [
            1 => [
                "__success" => [
                    "__input" => null,
                    "__output" => null
                ],
                "__error" => [
                    401 => \Blackmine\Exception\Api\UnauthorizedApiException::class,
                    403 => \Blackmine\Exception\Api\InaccessibleResourceException::class,
                    404 => \Blackmine\Exception\Api\EntityNotFoundException::class,
                    500 => Error::class
                ]
            ]
        ]
    ]
];
