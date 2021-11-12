<?php

declare(strict_types=1);

return [
    "__construct" => [
        "id" => 1,
        "project" => new \Blackmine\Model\Project\Project(id: 1, name: "Test Project"),
        "tracker" => new \Blackmine\Model\Project\Tracker(id: 1, name: "Test Tracker"),
        "priority" => new \Blackmine\Model\Enumeration\IssuePriority(id: 1, name: "Test Priority"),
        "author" => (new \Blackmine\Model\User\User())->fromArray(
            ["id" => 1, "firstname" => "Test", "lastname" => "User"]
        ),
        "category" => new \Blackmine\Model\Project\IssueCategory(id: 1, name: "Test Category"),
        "fixed_version" => new \Blackmine\Model\Project\Version(id: 1, name: "Test Version"),
        "subject" => "Test Subject",
        "description" => "Test Description"

    ],
    "__expects" => [
        "id" => 1,
        "project_id" => 1,
        "tracker_id" => 1,
        "priority_id" => 1,
        "author_id" => 1,
        "category_id" => 1,
        "fixed_version_id" => 1,
        "subject" => "Test Subject",
        "description" => "Test Description",
        "attachments" => [],
        "children" => [],
        "custom_fields" => [],
        "watchers" => [],
        "journals" => [],
        "relations" => [],
        "changesets" => []
    ],
    "__payload" => [
        "issue" => [
            "id" => 1,
            "project_id" => 1,
            "tracker_id" => 1,
            "priority_id" => 1,
            "author_id" => 1,
            "category_id" => 1,
            "fixed_version_id" => 1,
            "subject" => "Test Subject",
            "description" => "Test Description",
            "uploads" => [],
            "custom_fields" => [],
            "watcher_user_ids" => []
        ]
    ],
    "__implements" => [
        \Blackmine\Mutator\MutableInterface::class,
        \Blackmine\Model\FetchableInterface::class
    ]
];
