<?php

namespace Blackmine\Repository\Projects;

use Blackmine\Exception\Api\AbstractApiException;
use Blackmine\Exception\InvalidModelException;
use Blackmine\Model\Issue\Attachment;
use Blackmine\Model\Project\WikiPage;
use Blackmine\Repository\AbstractRepository;
use Blackmine\Repository\Uploads;
use JsonException;

class WikiPages extends AbstractRepository
{
    public const API_ROOT = "wiki_pages";

    public function getModelClass(): string
    {
        return WikiPage::class;
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

}