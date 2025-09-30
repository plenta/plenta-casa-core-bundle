<?php

namespace Plenta\CasaCoreBundle\Filter;

class Parameter
{
    public function __construct(
        protected string $name,
        protected array $columns,
        protected array $values,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Parameter
    {
        $this->name = $name;
        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setColumns(array $columns): Parameter
    {
        $this->columns = $columns;
        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setValues(array $values): Parameter
    {
        $this->values = $values;
        return $this;
    }
}