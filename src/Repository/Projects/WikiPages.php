<?php

namespace Blackmine\Repository\Projects;

use Blackmine\Client\ClientOptions;
use Blackmine\Collection\HierarchyCollection;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Exception\Api\AbstractApiException;
use Blackmine\Exception\InvalidModelException;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\Issue\Attachment;
use Blackmine\Model\Project\Project;
use Blackmine\Model\Project\WikiPage;
use Blackmine\Repository\AbstractRepository;
use Blackmine\Repository\Uploads;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Error;
use JsonException;

class WikiPages extends AbstractRepository
{
    public const API_ROOT = "wiki_pages";

    protected Project $project;

    public const WIKI_PAGES_RELATION_ATTACHMENTS = "attachments";
    public const WIKI_PAGES_RELATION_REVISIONS = "revisions";
    public const WIKI_PAGES_RELATION_CHILDREN = "children";

    protected static array $relation_class_map = [
        self::WIKI_PAGES_RELATION_ATTACHMENTS => Attachment::class,
        self::WIKI_PAGES_RELATION_REVISIONS => WikiPage::class,
        self::WIKI_PAGES_RELATION_CHILDREN => WikiPage::class
    ];

    public function getModelClass(): string
    {
        return WikiPage::class;
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        if (!$model instanceof WikiPage) {
            throw new InvalidModelException(
                'Wrong model class for ' . $this->getEndpoint() . " api. Expected " . $this->getModelClass()
            );
        }

        $api_response = $this->client->put(
            endpoint: $this->getEndpoint() . "/" . $model->getTitle() . "." . $this->client->getFormat(),
            body: json_encode($model->getPayload(), JSON_THROW_ON_ERROR),
            headers: $this->options[ClientOptions::CLIENT_OPTION_REQUEST_HEADERS] ?? []
        );

        if ($api_response->isSuccess()) {
            $model_data = $api_response->getData()[$model->getEntityName()] ?? null;

            if ($model_data) {
                $model->fromArray($model_data);
                $this->hydrateRelations($model);

                return $model;
            }
        }

        throw AbstractApiException::fromApiResponse($api_response);
    }

    /**
     * @throws AbstractApiException
     * @throws JsonException
     */
    public function all(?string $endpoint = null): ArrayCollection
    {
        if (is_initialized($this, "project")) {
            $endpoint = $endpoint ?? $this->getEndpoint() . "/index." . $this->client->getFormat();
            $response = $this->client->get($endpoint);

            if ($response->isSuccess()) {
                $collection = new HierarchyCollection(parent_field: "title");

                foreach ($response->getData()[self::API_ROOT] as $relation_data) {
                    $wiki_page = $this->getWikiPage($relation_data["title"]);
                    if (in_array(self::WIKI_PAGES_RELATION_REVISIONS, $this->getFetchRelations(), true)) {
                        $wiki_page->setRevisions($this->getRevisions($wiki_page));
                    }
                    $collection->add($wiki_page);
                }

                return $collection;
            }

            throw AbstractApiException::fromApiResponse($response);
        }

        throw new Error("Mandatory class property project not initialized");
    }

    /**
     * @throws AbstractApiException
     * @throws JsonException
     */
    protected function getWikiPage(string $title, ?int $revision = null): WikiPage
    {
        if ($revision !== null) {
            $endpoint = $this->getEndpoint() . "/" . $title . "/" . $revision . "." . $this->client->getFormat() . "?include=attachments";
        } else {
            $endpoint = $this->getEndpoint() . "/" . $title . "." . $this->client->getFormat() . "?include=attachments";
        }

        $response = $this->client->get($endpoint);

        if ($response->isSuccess()) {
            return (new WikiPage())->fromArray($response->getData()["wiki_page"]);
        }

        throw AbstractApiException::fromApiResponse($response);
    }

    /**
     * @throws AbstractApiException
     * @throws JsonException
     */
    protected function getRevisions(WikiPage $wiki_page): IdentityCollection
    {
        $collection = new IdentityCollection();
        $max_revision = $wiki_page->getVersion();
        for ($i = 1; $i <= $max_revision; $i++) {
            $collection->add($this->getWikiPage($wiki_page->getTitle(), $i));
        }

        return $collection;
    }

    /**
     * @throws AbstractApiException
     * @throws JsonException
     * @throws InvalidModelException
     */
    public function addAttachment(WikiPage $wiki_page, Attachment $attachment): WikiPage
    {
        $attachment = $this->client->getRepository(Uploads::API_ROOT)?->create($attachment);
        if ($attachment) {
            $wiki_page->addAttachment($attachment);
        }
        return $wiki_page;
    }

    /**
     * @param Project $project
     * @return WikiPages
     */
    public function setProject(Project $project): self
    {
        $this->project = $project;
        return $this;
    }

    public function getEndpoint(): string
    {
        return "projects/" . $this->project->getId() . "/wiki";
    }
}
