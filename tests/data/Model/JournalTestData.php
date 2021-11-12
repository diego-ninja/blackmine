<?php

declare(strict_types=1);

return [
    "__construct" => [
        "id" => 1,
        "notes" => "Journal Test Notes",
        "private_notes" => false,
        "user" => (new \Blackmine\Model\User\User())
            ->fromArray(["id" => 1, "firstname" => "Test", "lastname" => "User"])
    ],
    "__expects" => [
        "id" => 1,
        "notes" => "Journal Test Notes",
        "private_notes" => false,
        "user_id" => 1
    ],
    "__payload" => [
        "journal" => [
            "id" => 1,
            "notes" => "Journal Test Notes",
            "private_notes" => false,
            "user_id" => 1
        ]
    ],
    "__implements" => [
        \Blackmine\Model\FetchableInterface::class
    ]
];
