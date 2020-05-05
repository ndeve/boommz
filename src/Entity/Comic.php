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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rate", mappedBy="comic")
     */
    private $rates;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contestDescription;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $contest;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="comic")
     * @ORM\OrderBy({"dateCreation" = "DESC"})
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comic", inversedBy="comics")
     */
    private $comicContest;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comic", mappedBy="comicContest")
     */
    private $comics;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->dateUpdate = new \DateTime();
        $this->screen = 0;
        $this->pages = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->comics = new ArrayCollection();
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

    /**
     * @return Collection|Rate[]
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function getTotalRate()
    {
        $sum = 0;
        foreach ($this->rates as $rate) {
            $sum += $rate->getValue();
        }

        return $sum;
    }

    public function getAverageRate()
    {
        $average = 0;

        if (count($this->rates)) {
            $sum = 0;
            foreach ($this->rates as $rate) {
                $sum += $rate->getValue();
            }

            $average =  $sum / count($this->rates);
        }

        return sprintf("%.1f", $average);
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates[] = $rate;
            $rate->setComic($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->contains($rate)) {
            $this->rates->removeElement($rate);
            // set the owning side to null (unless already changed)
            if ($rate->getComic() === $this) {
                $rate->setComic(null);
            }
        }

        return $this;
    }

    public function getContestDescription(): ?string
    {
        return $this->contestDescription;
    }

    public function setContestDescription(?string $contestDescription): self
    {
        $this->contestDescription = $contestDescription;

        return $this;
    }

    public function getContest(): ?bool
    {
        return $this->contest;
    }

    public function setContest(bool $contest): self
    {
        $this->contest = $contest;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setComic($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getComic() === $this) {
                $comment->setComic(null);
            }
        }

        return $this;
    }

    public function getComicContest(): ?self
    {
        return $this->comicContest;
    }

    public function setComicContest(?self $comicContest): self
    {
        $this->comicContest = $comicContest;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getComics(): Collection
    {
        return $this->comics;
    }

    public function addComic(self $comic): self
    {
        if (!$this->comics->contains($comic)) {
            $this->comics[] = $comic;
            $comic->setComicContest($this);
        }

        return $this;
    }

    public function removeComic(self $comic): self
    {
        if ($this->comics->contains($comic)) {
            $this->comics->removeElement($comic);
            // set the owning side to null (unless already changed)
            if ($comic->getComicContest() === $this) {
                $comic->setComicContest(null);
            }
        }

        return $this;
    }

}
