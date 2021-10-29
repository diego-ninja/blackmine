<?php

namespace Blackmine\Mutator;

use Doctrine\Common\Collections\ArrayCollection;

class ModelMutator
{
    protected ArrayCollection $mutations;

    public function __construct(protected MutableInterface $model)
    {
        $this->mutations = new ArrayCollection($this->model->getMutations());
    }

    public function mutate(): ?array
    {
        if ($this->model->isMutable()) {
            $payload = $this->model->toArray();
            foreach ($this->mutations as $key => $mutations) {
                foreach ($mutations as $mutation_class => $mutation_args) {
                    $mutation = new $mutation_class;
                    array_unshift($mutation_args, $key);

                    $payload = $mutation($payload, $mutation_args);
                }
            }

            return $payload;
        }

        return null;

    }
}