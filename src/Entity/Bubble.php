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
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $orientation = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $x = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $y = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persona")
     */
    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $author;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return 'bubble_'. $this->getId();
    }

    public function getBox(): ?box
    {
        return $this->box;
    }

    public function setBox(?box $box): self
    {
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

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        if (!$this->dateCreation) {
            $this->dateCreation = $dateCreation;
        }

        return $this;
    }

    public function getOrientation(): ?bool
    {
        return $this->orientation;
    }

    public function setOrientation(bool $orientation): self
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(int $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(int $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getClasses()
    {
        $classes = '';

        $fs12 = 95;
        $fs14 = 55;
        $fs16 = 35;

        if (count($this->getBox()->getBubbles()) == 1) {
            $classes .= 'alone ';
            $fs12 = 125;
            $fs14 = 95;
            $fs16 = 75;
        }

        if ($this->getText()) {
            $nbCar = strlen(trim($this->getText()));
            $classes .= 'bubble ' . ($this->getOrientation() ? 'left ': 'right ') . ($this->getStyle() ?? '') .' ';

            if ($nbCar > $fs12) {
                $classes .= 'fs-12';
            }
            else if ($nbCar > $fs14) {
                $classes .= 'fs-14';
            }
            else if ($nbCar > $fs16) {
                $classes .= 'fs-16';
            }
            else {
                $classes .= 'fs-18';
            }
        }

        return $classes;
    }
}
