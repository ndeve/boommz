<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoxRepository")
 */
class Box
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\comic", inversedBy="boxes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comic;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderBox;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $background;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bubble", mappedBy="box")
     */
    private $bubbles;

    public function __construct()
    {
        $this->bubbles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComic(): ?comic
    {
        return $this->comic;
    }

    public function setComic(?comic $comic): self
    {
        $this->comic = $comic;

        return $this;
    }

    public function getOrderBox(): ?int
    {
        return $this->orderBox;
    }

    public function setOrderBox(int $orderBox): self
    {
        $this->orderBox = $orderBox;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): self
    {
        $this->background = $background;

        return $this;
    }

    /**
     * @return Collection|Bubble[]
     */
    public function getBubbles(): Collection
    {
        return $this->bubbles;
    }

    public function addBubble(Bubble $bubble): self
    {
        if (!$this->bubbles->contains($bubble)) {
            $this->bubbles[] = $bubble;
            $bubble->setBox($this);
        }

        return $this;
    }

    public function removeBubble(Bubble $bubble): self
    {
        if ($this->bubbles->contains($bubble)) {
            $this->bubbles->removeElement($bubble);
            // set the owning side to null (unless already changed)
            if ($bubble->getBox() === $this) {
                $bubble->setBox(null);
            }
        }

        return $this;
    }
}
