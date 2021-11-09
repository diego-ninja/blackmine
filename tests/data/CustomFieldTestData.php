<?php

declare(strict_types=1);

return [
    "__construct" => [
        "id" => 1,
        "name" => "Test Custom Field",
        "value" => "Test Value",
        "customized_type"=> "issue",
        "min_length"=> 5,
        "max_length"=> 10,
        "is_required"=> true,
        "is_filter"=> false,
        "searchable"=> true,
        "multiple"=> false,
        "visible"=> false
    ],
    "__expects" => [
        "id" => 1,
        "name" => "Test Custom Field",
        "value" => "Test Value",
        "customized_type"=> "issue",
        "min_length"=> 5,
        "max_length"=> 10,
        "is_required"=> true,
        "is_filter"=> false,
        "searchable"=> true,
        "multiple"=> false,
        "visible"=> false
    ],
    "__payload" => [
        "custom_field" => [
            "id" => 1,
            "name" => "Test Custom Field",
            "value" => "Test Value",
            "customized_type"=> "issue",
            "min_length"=> 5,
            "max_length"=> 10,
            "is_required"=> true,
            "is_filter"=> false,
            "searchable"=> true,
            "multiple"=> false,
            "visible"=> false
        ]
    ]
];