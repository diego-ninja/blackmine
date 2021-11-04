<?php

declare(strict_types=1);

namespace Blackmine\Repository\Issues;

use Blackmine\Collection\IdentityCollection;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\User\User;
use Blackmine\Repository\RepositoryTrait;
use Blackmine\Repository\Uploads;
use Carbon\CarbonInterface;
use Blackmine\Model\CustomField;
use Blackmine\Repository\AbstractRepository;
use Blackmine\Model\Issue\Attachment;
use Blackmine\Model\Issue\Changeset;
use Blackmine\Model\Issue\Issue;
use Blackmine\Model\Issue\Journal;
use Blackmine\Model\Issue\Relation;
use Blackmine\Repository\RepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use JsonException;

class Issues extends AbstractRepository
{
    use RepositoryTrait;

    public const API_ROOT = "issues";

    public const ISSUE_RELATION_CHILDREN = "children";
    public const ISSUE_RELATION_ATTACHMENTS = "attachments";
    public const ISSUE_RELATION_RELATIONS = "relations";
    public const ISSUE_RELATION_JOURNALS = "journals";
    public const ISSUE_RELATION_CHANGESETS = "changesets";
    public const ISSUE_RELATION_WATCHERS = "watchers";
    public const ISSUE_RELATION_CUSTOM_FIELDS = "custom_fields";

    public const ISSUE_FILTER_ISSUE_ID = "issue_id";
    public const ISSUE_FILTER_PARENT_ID = "parent_id";
    public const ISSUE_FILTER_PROJECT_ID = "project_id";
    public const ISSUE_FILTER_SUBPROJECT_ID = "subproject_id";
    public const ISSUE_FILTER_TRACKER_ID = "tracker_id";
    public const ISSUE_FILTER_STATUS_ID = "status_id";
    public const ISSUE_FILTER_ASSIGNED_TO_ID = "assigned_to_id";


    protected static array $relation_class_map = [
        self::ISSUE_RELATION_CHILDREN => Issue::class,
        self::ISSUE_RELATION_ATTACHMENTS => Attachment::class,
        self::ISSUE_RELATION_RELATIONS => Relation::class,
        self::ISSUE_RELATION_JOURNALS => Journal::class,
        self::ISSUE_RELATION_CHANGESETS => Changeset::class,
        self::ISSUE_RELATION_WATCHERS => User::class,
        self::ISSUE_RELATION_CUSTOM_FIELDS => CustomField::class
    ];

    protected static array $allowed_filters = [
        self::ISSUE_FILTER_ISSUE_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT_ARRAY,
        self::ISSUE_FILTER_PROJECT_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::ISSUE_FILTER_SUBPROJECT_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::ISSUE_FILTER_TRACKER_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::ISSUE_FILTER_STATUS_ID => RepositoryInterface::SEARCH_PARAM_TYPE_STRING,
        self::ISSUE_FILTER_PARENT_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::ISSUE_FILTER_ASSIGNED_TO_ID => RepositoryInterface::SEARCH_PARAM_TYPE_INT,
        self::COMMON_FILTER_CREATED_ON => CarbonInterface::class,
        self::COMMON_FILTER_UPDATED_ON => CarbonInterface::class,
        self::COMMON_FILTER_CUSTOM_FIELDS => RepositoryInterface::SEARCH_PARAM_TYPE_CF_ARRAY
    ];

    public function getModelClass(): string
    {
        return Issue::class;
    }

    public function create(AbstractModel $model): ?AbstractModel
    {
        $model = parent::create($model);
        if ($model && !$model->getAttachments()->isEmpty()) {
            $api_response = $this->client->put(
                $this->getEndpoint() . "/" . $model->getId() . "." . $this->client->getFormat(),
                json_encode($model->getPayload(), JSON_THROW_ON_ERROR)
            );

            if ($api_response->isSuccess()) {
                return $model;
            }

            return null;
        }

        return $model;
    }

    public function addChild(Issue $issue, Issue $child): Issue
    {
        if (!$child->isPersisted()) { // Issue already exists
            $child->setParentIssue($issue);
            $child = $this->create($child);
        }

        $issue->addChild($child);

        return $issue;
    }

    /**
     * @throws JsonException
     */
    public function getChildren(Issue $issue): IdentityCollection | ArrayCollection
    {
        return $this->client->getRepository(self::API_ROOT)
            ?->addFilter(self::ISSUE_FILTER_PARENT_ID, $issue->getId())
            ->addFilter(self::ISSUE_FILTER_STATUS_ID, "*")
            ->search();
    }


    public function addAttachment(Issue $issue, Attachment $attachment): Issue
    {
        $attachment = $this->client->getRepository(Uploads::API_ROOT)?->create($attachment);
        if ($attachment) {
            $attachment->setVersion($issue->getFixedVersion());
            $issue->addAttachment($attachment);
        }
        return $issue;

    }

}