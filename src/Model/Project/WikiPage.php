<?php

namespace Blackmine\Model\Project;

use Blackmine\Collection\HierarchyCollection;
use Blackmine\Collection\IdentityCollection;
use Blackmine\Model\AbstractModel;
use Blackmine\Model\FetchableInterface;
use Blackmine\Model\Identity;
use Blackmine\Model\Issue\Attachment;
use Blackmine\Model\User\User;
use Blackmine\Mutator\Mutation\RemoveKeyMutation;
use Blackmine\Mutator\Mutation\RenameKeyMutation;
use Blackmine\Repository\Projects\WikiPages;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method void setTitle(string $title)
 * @method void setText(string $text)
 * @method void setComments(string $comments)
 * @method void setParent(WikiPage $parent)
 * @method void setAuthor(User $author)
 *
 * @method string getTitle()
 * @method string getText()
 * @method int getVersion()
 * @method string getComments()
 * @method WikiPage getParent()
 * @method IdentityCollection getAttachments()
 * @method IdentityCollection getVersions()
 * @method CarbonImmutable getCreatedOn()
 * @method CarbonImmutable getUpdatedOn()
 *
 * @method void addAttachment(Attachment $attachment)
 * @method void removeAttachment(Attachment $attachment)
 */
class WikiPage extends Identity implements FetchableInterface
{
    protected string $title;
    protected string $text;
    protected int $version;
    protected string $comments;

    protected ?WikiPage $parent = null;
    protected User $author;

    protected ?IdentityCollection $attachments;
    protected ?IdentityCollection $versions;
    protected ?HierarchyCollection $children;

    protected CarbonImmutable $created_on;
    protected CarbonImmutable $updated_on;

    public function __construct(protected ?int $id = null)
    {
        $this->attachments = new IdentityCollection();
        $this->children = new HierarchyCollection(parent_field: "title");
    }

    public static function getRepositoryClass(): ?string
    {
        return WikiPages::class;
    }

    public function getMutations(): array
    {
        return [
            "attachments" => [RenameKeyMutation::class => ["uploads"]],
            "created_on" => [RemoveKeyMutation::class => []],
            "updated_on" => [RemoveKeyMutation::class  => []]
        ];
    }

    public function addChild(WikiPage $child): void
    {
        $this->children->addDirect($child);
    }

}