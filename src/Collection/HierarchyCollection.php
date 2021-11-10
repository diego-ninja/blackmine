<?php

namespace Blackmine\Collection;

use Blackmine\Model\ParentableInterface;
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

    public function replaceBy(string $field, ParentableInterface $value, bool $recursive = false): void
    {
        foreach ($this->getElements() as $element) {
            $getter = "get" . ucfirst(Inflect::camelize($field));
            if ($element->$getter() === $value->$getter()) {
                $idx = $this->indexOf($element);
                $this->set($idx, $value);
                return;
            }

            if ($recursive) {
                $children = $element->getChildren();
                if ($children instanceof self) {
                    $children->replaceBy($field, $value, $recursive);
                }
            }
        }
    }

    /**
     * @param ParentableInterface $element
     */
    public function add($element)
    {
        $has_parent = $element->getParent();
        if ($has_parent) {
            $getter = "get" . ucfirst(Inflect::camelize($this->parent_field));
            $parent = $this->findBy($this->parent_field, $element->getParent()?->$getter(), true);
            if ($parent) {
                $parent->addChild($element);
                $this->replaceBy($this->parent_field, $parent, true);
            } else {
                $this->orphans[$element->$getter()] = $element;
            }
        } else {
            parent::add($element);
            if (!empty($this->orphans)) {
                foreach ($this->orphans as $id => $orphan) {
                    $this->add($orphan);
                    unset($this->orphans[$id]);
                }
            }
        }
    }

    public function addDirect(ParentableInterface $element): void
    {
        parent::add($element);
    }
}
