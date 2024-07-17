<?php

namespace LootSpace369\lsform;

use pocketmine\form\Form;
use pocketmine\player\Player;

class ModalForm implements Form {

    private $title;
    private $content;
    private $button1;
    private $button2;
    private $onSubmit;

    public function __construct(string $title, string $content, string $button1, string $button2, callable $onSubmit) {
        $this->title = $title;
        $this->content = $content;
        $this->button1 = $button1;
        $this->button2 = $button2;
        $this->onSubmit = $onSubmit;
    }

    public function jsonSerialize(): array {
        return [
            "type" => "modal",
            "title" => $this->title,
            "content" => $this->content,
            "button1" => $this->button1,
            "button2" => $this->button2
        ];
    }

    public function handleResponse(Player $player, $data): void {
        $onSubmit = $this->onSubmit;
        $onSubmit($player, $data);
    }
}
