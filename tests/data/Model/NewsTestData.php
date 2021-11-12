<?php

declare(strict_types=1);

return [
    "__construct" => [
        "id" => 1,
        "title" => "News Test Title",
        "summary" => "News Test Summary",
        "description" => "News Tests Description",
        "project" => new \Blackmine\Model\Project\Project(id: 1, name: "Test Project"),
        "author" => (new \Blackmine\Model\User\User())
            ->fromArray(["id" => 1, "firstname" => "Test", "lastname" => "User"]),
        "created_on" => \Carbon\CarbonImmutable::create(2020, 7, 15)
    ],
    "__expects" => [
        "id" => 1,
        "title" => "News Test Title",
        "summary" => "News Test Summary",
        "description" => "News Tests Description",
        "project_id" => 1,
        "author_id" => 1,
        "created_on" => \Carbon\CarbonImmutable::create(2020, 7, 15)->format("Y-m-d")
    ],
    "__payload" => [
        "news" => [
            "id" => 1,
            "title" => "News Test Title",
            "summary" => "News Test Summary",
            "description" => "News Tests Description",
            "project_id" => 1,
            "author_id" => 1
        ]
    ],
    "__implements" => [
        \Blackmine\Mutator\MutableInterface::class,
        \Blackmine\Model\FetchableInterface::class
    ]
];
