<?php

declare(strict_types=1);

return [
    "__construct" => [
        "id" => 1,
        "name" => "Test Status",
        "is_closed" => false
    ],
    "__expects" => [
        "id" => 1,
        "name" => "Test Status",
        "is_closed" => false
    ],
    "__payload" => [
        "issue_status" => [
            "id" => 1,
            "name" => "Test Status",
            "is_closed" => false
        ]
    ]
];
