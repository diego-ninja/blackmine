<?php

namespace Blackmine\Model\Project;

use Blackmine\Collection\HierarchyCollection;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\Identity;
use Blackmine\Model\Issue\Attachment;
use Blackmine\Model\ParentableInterface;
use Blackmine\Model\User\User;
use Blackmine\Mutator\MutableInterface;
use Blackmine\Mutator\Mutation\AddSubkeyMutation;
use Blackmine\Mutator\Mutation\RemoveKeyMutation;
use Blackmine\Mutator\Mutation\RenameKeyMutation;
use Blackmine\Repository\Projects\WikiPages;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\Collection;

/**
 * @method void setTitle(string $title)
 * @method void setText(string $text)
 * @method void setComments(string $comments)
 * @method void setAuthor(User $author)
 * @method void setRevisions(IdentityCollection $revisions)
 *
 * @method string getTitle()
 * @method string getText()
 * @method int getVersion()
 * @method string getComments()
 * @method IdentityCollection getAttachments()
 * @method IdentityCollection getRevisions()
 * @method CarbonImmutable getCreatedOn()
 * @method CarbonImmutable getUpdatedOn()
 *
 * @method void addAttachment(Attachment $attachment)
 * @method void removeAttachment(Attachment $attachment)
 * @method void removeRevision(WikiPage $revision);
 */
class WikiPage extends Identity implements FetchableInterface, MutableInterface, ParentableInterface
{
    public const ENTITY_NAME = "wiki_page";

    protected string $title;
    protected string $text;
    protected int $version;
    protected string $comments;

    protected ?WikiPage $parent = null;
    protected User $author;

    protected IdentityCollection $attachments;
    protected IdentityCollection $revisions;
    protected HierarchyCollection $children;

    protected CarbonImmutable $created_on;
    protected CarbonImmutable $updated_on;

    public function __construct(protected ?int $id = null)
    {
        $this->attachments = new IdentityCollection();
        $this->revisions = new IdentityCollection();
        $this->children = new HierarchyCollection(parent_field: "title");
    }

    public function getId(): mixed
    {
        return $this->title;
    }

    public static function getRepositoryClass(): ?string
    {
        return WikiPages::class;
    }

    public function getMutations(): array
    {
        return [
            "parent_id" => [
                AddSubkeyMutation::class => ["title"],
                RenameKeyMutation::class => ["parent"],
            ],
            "attachments" => [RenameKeyMutation::class => ["uploads"]],
            "created_on" => [RemoveKeyMutation::class => []],
            "updated_on" => [RemoveKeyMutation::class  => []]
        ];
    }

    public function addRevision(WikiPage $revision): void
    {
        if (!$this->revisions->contains($revision)) {
            $this->revisions->add($revision);
        }
    }


    public function getParent(): ?ParentableInterface
    {
        return $this->parent;
    }

    public function setParent(ParentableInterface | array $parent): void
    {
        $this->parent = $this->normalizeValue("parent", __CLASS__, $parent);
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(ParentableInterface $child): void
    {
        $child->setParent($this);
        $this->children->addDirect($child);
    }

    public function removeChild(ParentableInterface $child): void
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
        }
    }

    public function isPersisted(): bool
    {
        return is_initialized($this, "version") && $this->version > 0;
    }
}
