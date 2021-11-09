<?php

namespace Blackmine\Collection;

use Blackmine\Model\Identity;
use Blackmine\Tool\Inflect;

class HierarchyCollection extends IdentityCollection
{
    protected array $orphans = [];

    public function __construct(array $elements = [], protected string $parent_field = "id")
    {
        parent::__construct($elements);
    }

    public function findBy(string $field, mixed $value, bool $recursive = false): mixed
    {
        foreach ($this->getElements() as $identity) {
            $getter = "get" . ucfirst(Inflect::camelize($field));
            if ($identity->$getter() === $value) {
                return $identity;
            }

            if ($recursive) {
                $children = $identity->getChildren();
                if ($children instanceof self) {
                    return $children->findBy($field, $value, $recursive);
                }
            }
        }

        return null;
    }

    public function replaceBy(string $field, Identity $value, bool $recursive = false)
    {
        foreach ($this->getElements() as $identity) {
            $getter = "get" . ucfirst(Inflect::camelize($field));
            if ($identity->$getter() === $value->$getter()) {
                $idx = $this->indexOf($identity);
                $this->set($idx, $value);
                return;
            }

            if ($recursive) {
                $children = $identity->getChildren();
                if ($children instanceof self) {
                    $children->replaceBy($field, $value, $recursive);
                }
            }
        }
    }

    /**
     * @param Identity $element
     */
    public function add($element)
    {
        $has_parent = $element->getParent();
        if ($has_parent) {
            $parent = $this->findBy($this->parent_field, $element->getParent()->getTitle(), true);
            if ($parent) {
                $parent->addChild($element);
                $this->replaceBy($this->parent_field, $parent, true);
            } else {
                $this->orphans[$element->getTitle()] = $element;
            }
        } else {
            parent::add($element);
            if (!empty($this->orphans)) {
                foreach ($this->orphans as $title => $orphan) {
                    $this->add($orphan);
                    unset($this->orphans[$title]);
                }
            }
        }
    }

    public function addDirect(Identity $element)
    {
        parent::add($element);
    }
}
