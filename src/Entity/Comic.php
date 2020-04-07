<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Behat\Transliterator;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComicRepository")
 */
class Comic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateUpdate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePublication;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="comics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Box", mappedBy="comic", orphanRemoval=true)
     */
    private $boxes;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $locale;

    public function __construct()
    {
        $this->boxes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->getTitle();
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(\DateTimeInterface $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(?\DateTimeInterface $datePublication): self
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getAuthor(): ?user
    {
        return $this->author;
    }

    public function setAuthor(?user $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

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
            $box->setComic($this);
        }

        return $this;
    }

    public function removeBox(Box $box): self
    {
        if ($this->boxes->contains($box)) {
            $this->boxes->removeElement($box);
            // set the owning side to null (unless already changed)
            if ($box->getComic() === $this) {
                $box->setComic(null);
            }
        }

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getRewritten()
    {
        return $this->getTitle();
    }

    public function getRouteParams()
    {
        return ['id' => $this->getId(), 'rewritten' => $this->getRewritten()];
    }

    public function getOnline()
    {
        return true;
    }
}
