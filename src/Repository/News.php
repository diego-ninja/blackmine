<?php

namespace Blackmine\Repository;

class News extends AbstractRepository
{

    public function getModelClass(): string
    {
        return \Blackmine\Model\News::class;
    }
}
