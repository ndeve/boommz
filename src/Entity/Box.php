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
     * @ORM\Column(type="integer")
     */
    private $orderBox = 0;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateCreation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bubble", mappedBy="box", cascade={"persist"})
     */
    private $bubbles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="boxes")
     */
    private $page;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $size = 'one-third';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Background")
     */
    private $background;

    /**
     * @ORM\Column(type="integer")
     */
    private $height = '1';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $author;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->bubbles = new ArrayCollection();
        $this->page = new ArrayCollection();
    }

    public function __toString()
    {
        return 'bubble_'. $this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        if (!$this->dateCreation) {
            $this->dateCreation = $dateCreation;
        }

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

    /**
     * @return Collection|Page[]
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(Page $page): self
    {
        if (!$this->pages->contains($page)) {
            $this->pages[] = $page;
            $page->setBox($this);
        }

        return $this;
    }

    public function removePage(Page $page): self
    {
        if ($this->pages->contains($page)) {
            $this->pages->removeElement($page);
            // set the owning side to null (unless already changed)
            if ($page->getBox() === $this) {
                $page->setBox(null);
            }
        }

        return $this;
    }

    public function setPages(?Page $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getBackground(): ?Background
    {
        return $this->background;
    }

    public function setBackground(?Background $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

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
}
