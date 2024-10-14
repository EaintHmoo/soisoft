<?php

namespace App\Infolists\Components;

use Filament\Infolists\Components\Entry;

class DescriptionList extends Entry
{
    protected string $view = 'infolists.components.description-list';

    protected string $type = 'text';

    protected array $customBooleanText = ['No', 'Yes'];

    public function type($type): static {
        $this->type = $type;

        return $this;
    }

    public function getType(): ?string {
        return $this->evaluate($this->type);
    }

    public function customBooleanText($customBooleanText): static {
        $this->customBooleanText = $customBooleanText;

        return $this;
    }

    public function getCustomBooleanText(): array {
        return $this->evaluate($this->customBooleanText);
    }
}
