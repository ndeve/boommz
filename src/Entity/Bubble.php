<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BubbleRepository")
 */
class Bubble
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Box", inversedBy="bubbles")
     */
    private $box;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $style;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBox(): ?box
    {
        return $this->box;
    }

    public function setBox(?box $box): self
    {
        $this->setDateCreation();
        $this->box = $box;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(?string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(): self
    {
        $this->dateCreation = new \DateTime();

        return $this;
    }
}
