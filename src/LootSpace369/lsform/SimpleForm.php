<?php

declare(strict_types=1);

namespace LootSpace369\lsform;

use pocketmine\form\Form;
use pocketmine\player\Player;

class SimpleForm implements Form {

    private $title;
    private $content;
    private $buttons = [];
    private $onSubmit;

    public function __construct(string $title, string $content, callable $onSubmit) {
        $this->title = $title;
        $this->content = $content;
        $this->onSubmit = $onSubmit;
    }

    public function addButton(string $text, ?callable $callback = null): self {
        $this->buttons[] = ["text" => $text, "callback" => $callback];
        return $this;
    }

    public function jsonSerialize(): array {
        $buttons = array_map(fn($button) => ["text" => $button["text"]], $this->buttons);
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
