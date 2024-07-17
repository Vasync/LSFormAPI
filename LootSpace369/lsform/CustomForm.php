<?php

namespace LootSpace369\lsform;

use pocketmine\form\Form;
use pocketmine\player\Player;

class CustomForm implements Form {

    private $title;
    private $content = [];
    private $onSubmit;

    public function __construct(string $title, callable $onSubmit) {
        $this->title = $title;
        $this->onSubmit = $onSubmit;
    }

    public function addLabel(string $text): self {
        $this->content[] = ["type" => "label", "text" => $text];
        return $this;
    }

    public function addInput(string $text, string $placeholder = "", string $default = ""): self {
        $this->content[] = ["type" => "input", "text" => $text, "placeholder" => $placeholder, "default" => $default];
        return $this;
    }

    public function addSlider(string $text, float $min, float $max, float $step = 1.0, float $default = 0): self {
        $this->content[] = ["type" => "slider", "text" => $text, "min" => $min, "max" => $max, "step" => $step, "default" => $default];
        return $this;
    }

    public function addStepSlider(string $text, array $steps, int $defaultIndex = 0): self {
        $this->content[] = ["type" => "step_slider", "text" => $text, "steps" => $steps, "default" => $defaultIndex];
        return $this;
    }

    public function addToggle(string $text, bool $default = false): self {
        $this->content[] = ["type" => "toggle", "text" => $text, "default" => $default];
        return $this;
    }

    public function jsonSerialize(): array {
        return [
            "type" => "custom_form",
            "title" => $this->title,
            "content" => $this->content
        ];
    }

    public function handleResponse(Player $player, $data): void {
        $onSubmit = $this->onSubmit;
        $onSubmit($player, $data);
    }
}
