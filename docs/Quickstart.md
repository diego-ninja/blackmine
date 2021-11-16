# Quickstart

Once you required the package using composer, you need to create a client. The client receives a ClientOptions object with the Redmine instance configuration and optionally a [cache adapter](Cache.md) to speed up responses.

```php
$options = new \Blackmine\Client\ClientOptions([
    ClientOptions::CLIENT_OPTION_BASE_URL => "https://your.redmine.url",
    ClientOptions::CLIENT_OPTION_API_KEY => "your.api.key",
    ClientOptions::CLIENT_OPTION_FORMAT => ClientInterface::REDMINE_FORMAT_JSON // Only JSON is supported right now
    ClientOptions::CLIENT_OPTION_REQUEST_HEADERS => [ // You can define common headers to all requests
        "User-Agent" => "My Custom UserAgent v1.0"
    ]   
]);

$client = new \Blackmine\Client\Client($options);
$issues = $client->getRepository("issues");

$data = $issues
    ->addFilter(Issues::ISSUE_FILTER_ISSUE_ID, [41432, 41436])
    ->with([Issues::ISSUE_RELATION_WATCHERS])
    ->search();

$issue = new \Blackmine\Model\Issue\Issue();
$issue->setSubject("Test Issue");
$issue->setDescription("An issue description, yeah, a real one...");
$issue->setStartDate(\Carbon\CarbonImmutable::create(2021, 10, 31));
$issue->setDueDate(\Carbon\CarbonImmutable::create(2022, 10, 31));
$issues->create($issue);

```
