<?php

declare(strict_types=1);

namespace LootSpace369\lsform;

use pocketmine\form\Form;
use pocketmine\player\Player;

class SimpleForm implements Form {

    private string $title;
    private string $content;
    private array $buttons = [];
    private $onSubmit;

    public function __construct(string $title, string $content, callable $onSubmit) {
        $this->title = $title;
        $this->content = $content;
        $this->onSubmit = $onSubmit;
    }

    public function addButton(string|array $text, ?string $image = null, ?callable $callback = null): self {
        if (is_string($text)) {
            $button = ["text" => $text, "callback" => $callback];
            if ($image !== null) {
                $button["image"] = [
                    "type" => filter_var($image, FILTER_VALIDATE_URL) ? "url" : "path",
                    "data" => $image
                    ];
            }
            
            $this->buttons[] = $button;
            return $this;
        }else
        if (is_array($text)) {
            foreach ($text as $tex_t) {
                $button = ["text" => $tex_t, "callback" => $callback];
                if ($image !== null) {
                    $button["image"] = [
                        "type" => filter_var($image, FILTER_VALIDATE_URL) ? "url" : "path",
                        "data" => $image
                        ];
                }
            
                $this->buttons[] = $button;
            }
            return $this;
        }
    }

    public function jsonSerialize(): array {
        $buttons = array_map(function($button) {
            $buttonData = ["text" => $button["text"]];
            if (isset($button["image"])) {
                $buttonData["image"] = $button["image"];
            }
            return $buttonData;
        }, $this->buttons);

        return [
            "type" => "form",
            "title" => $this->title,
            "content" => $this->content,
            "buttons" => $buttons
        ];
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) return;

        $button = $this->buttons[$data] ?? null;
        if ($button !== null && isset($button["callback"])) {
            $callback = $button["callback"];
            $callback($player);
        }

        $onSubmit = $this->onSubmit;
        $onSubmit($player, $data);
    }
}
