<?php
class Surface {
    private int $id;
    private string $description;

    public function __construct(int $id, string $description)
    {
        $this->id = $id;
        $this->description = $description;
    }

    public function getID(): int {
        return $this->id;
    }

    public function getDescription(): string {
        return $this->description;
    }
}
?>
