<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comic", inversedBy="pages")
     */
    private $comic;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderPage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Box", mappedBy="page", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\OrderBy({"orderBox" = "ASC"})
     */
    private $boxes;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateCreation;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->boxes = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getComic(): ?comic
    {
        return $this->comic;
    }

    public function setComic(?comic $comic): self
    {
        $this->setOrderPage();
        $this->comic = $comic;

        return $this;
    }

    public function getOrderPage(): ?int
    {
        return $this->orderPage;
    }

    public function setOrderPage(): self
    {
        $orderPage = rand(0, 1000);
        $this->orderPage = $orderPage;

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
     * @return Collection|Box[]
     */
    public function getBoxes(): Collection
    {
        return $this->boxes;
    }

    public function addBox(Box $box): self
    {
        if (!$this->boxes->contains($box)) {
            $this->boxes[] = $box;
            $box->setPage($this);
        }

        return $this;
    }

    public function removeBox(Box $box): self
    {
        if ($this->boxes->contains($box)) {
            $this->boxes->removeElement($box);
            // set the owning side to null (unless already changed)
            if ($box->getPage() === $this) {
                $box->setPage(null);
            }
        }

        return $this;
    }
}
