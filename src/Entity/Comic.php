<?php

namespace App\Entity;

use Behat\Transliterator\Transliterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateUpdate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePublication;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comics")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $locale = 'fr';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Page", mappedBy="comic",
     *   orphanRemoval=true, cascade={"persist"})
     */
    private $pages;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $selected = 0;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateSelected;

    /**
     * @ORM\Column(type="boolean")
     */
    private $screen;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->dateUpdate = new \DateTime();
        $this->screen = 0;
        $this->pages = new ArrayCollection();
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

    public function setDatePublication(?\DateTimeInterface $datePublication
    ): self {
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
        return Transliterator::urlize($this->getTitle());
    }

    public function getRouteParams()
    {
        return ['id' => $this->getId(), 'rewritten' => $this->getRewritten()];
    }

    public function getOnline()
    {
        return true;
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
            $page->setComic($this);
        }

        $this->pages->add($page);

        return $this;
    }

    public function removePage(Page $page): self
    {
        if ($this->pages->contains($page)) {
            $this->pages->removeElement($page);
            // set the owning side to null (unless already changed)
            if ($page->getComic() === $this) {
                $page->setComic(null);
            }
        }

        return $this;
    }

    public function getSelected(): ?bool
    {
        return $this->selected;
    }

    public function setSelected(bool $selected): self
    {
        $this->setDateSelected($selected ? new \DateTime() : null);

        $this->selected = $selected;

        return $this;
    }

    public function getDateSelected(): ?\DateTimeInterface
    {
        return $this->dateSelected;
    }

    public function setDateSelected(?\DateTimeInterface $dateSelected): self
    {
        $this->dateSelected = $dateSelected;

        return $this;
    }

    public function getScreen(): ?bool
    {
        return $this->screen;
    }

    public function setScreen(bool $screen): self
    {
        $this->screen = $screen;

        return $this;
    }

    public function getUrlScreen()
    {
        return 'comics/'. substr($this->getId(),-1) . '/'. substr($this->getId(), -2, 1) .'/';
    }

}
