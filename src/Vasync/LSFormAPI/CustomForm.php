<?php

declare(strict_types=1);

namespace Vasync\LSFormAPI;

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

    public function addLabel(string|array $text): self {
        $texts = is_array($text) ? $text : [$text];
        
        foreach ($texts as $tex_t) {
            $content = ["type" => "label", "text" => $tex_t];
            
            $this->content[] = $content;
        }
        return $this;
    }
    
    public function addInput(string|array $text, string|array $placeholder = "", string|array $default = ""): self {
        $texts = is_array($text) ? $text : [$text];
        $placeholders = is_array($placeholder) ? $placeholder : [$placeholder];
        $defaults = is_array($default) ? $default : [$default];

        $maxCount = max(count($texts), count($placeholders), count($defaults));

        for ($i = 0; $i < $maxCount; $i++) {
            $content = [
                "type" => "input",
                "text" => $texts[$i] ?? "",
                "placeholder" => $placeholders[$i] ?? "",
                "default" => $defaults[$i] ?? ""
            ];
            $this->content[] = $content;
        }
        return $this;
    }
    
    public function addSlider(string|array $text, float|array $min, float|array $max, float|array $step = 1.0, float|array $default = 0): self {
        $texts = is_array($text) ? $text : [$text];
        $mins = is_array($min) ? $min : [$min];
        $maxes = is_array($max) ? $max : [$max];
        $steps = is_array($step) ? $step : [$step];
        $defaults = is_array($default) ? $default : [$default];

        $maxCount = max(count($texts), count($mins), count($maxes), count($steps), count($defaults));
        
        for ($i = 0; $i < $maxCount; $i++) {
            $content = [
                "type" => "slider",
                "text" => $texts[$i] ?? "",
                "min" => $mins[$i] ?? 0.0,
                "max" => $maxes[$i] ?? 0.0,
                "step" => $steps[$i] ?? 1.0,
                "default" => $defaults[$i] ?? 0.0
            ];
            $this->content[] = $content;
        }
        return $this;
    }

    
    public function addStepSlider(string|array $text, array $steps, int|array $defaultIndex = 0): self {
        $texts = is_array($text) ? $text : [$text];
        $defaultIndices = is_array($defaultIndex) ? $defaultIndex : [$defaultIndex];
        
        $maxCount = max(count($texts), count($steps), count($defaultIndices));
        
        for ($i = 0; $i < $maxCount; $i++) {
            $content = [
                "type" => "step_slider",
                "text" => $texts[$i] ?? "",
                "steps" => $steps[$i] ?? [],
                "default" => $defaultIndices[$i] ?? 0
            ];
            $this->content[] = $content;
        }
        return $this;
    }


    public function addToggle(string|array $text, bool|array $default = false): self {
        $texts = is_array($text) ? $text : [$text];
        $defaults = is_array($default) ? $default : [$default];
        $maxCount = max(count($texts), count($defaults));
        
        for ($i = 0; $i < $maxCount; $i++) {
            $content = [
                "type" => "toggle",
                "text" => $texts[$i] ?? "",
                "default" => $defaults[$i] ?? false
                ];
            $this->content[] = $content;
        }
        return $this;
    }

    public function addDropdown(string $text, array $options, int $default = null, ?string $label = null) : self {
        $this->content[] = [
            "type" => "dropdown",
            "text" => $text,
            "options" => $options,
            "default" => $default
            ];
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
