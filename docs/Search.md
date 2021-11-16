# Searching data

Blackmine implements a fluid serach api in every repository that allows you to filter api results and include 
entity relations.

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

$cf = new CustomField(id: 1, name: "MyCustomField");
$cf->setValue("MyCustomFieldValue");

$data = $issues
    ->with([
        Issues::ISSUE_RELATION_WATCHERS,
        
    ]) // included watchers in results
    ->addFilter(Issues::ISSUE_FILTER_PROJECT_ID, 1) // filter results by project
    ->addFilter(Issues::ISSUE_FILTER_TRACKER_ID, 1) // filter results by tracker
    ->addCustomFieldFilter($cf) // filter results by custom field
    ->from(\Carbon\Carbon::yesterday(), Issues::ISSUE_FILTER_DUE_DATE) // filter by due date
    ->to(\Carbon\Carbon::tomorrow(), Issues::ISSUE_FILTER_DUE_DATE) // filter by due date
    ->sortBy("title", RepositoryInterface::SORT_DIRECTION_DESC)
    ->limit(10)
    ->offset(10)
    ->search();

```


