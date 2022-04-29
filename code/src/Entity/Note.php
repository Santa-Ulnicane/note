<?php

namespace App\Entity;

use \DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteRepository")
 * @ORM\Table(name="note")
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $text;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime(); 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function fromArray(array $data): void
    {
        foreach ($data as $fieldName => $value) {
            $setter = sprintf('set%s', ucfirst($fieldName));

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }
}