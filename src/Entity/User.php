<?php
// src/Entity/User.php

namespace App\Entity;

use Behat\Transliterator\Transliterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comic", mappedBy="author", orphanRemoval=true)
     * @ORM\OrderBy({"dateCreation" = "DESC"})
     */
    private $comics;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Persona", mappedBy="users")
     */
    private $personas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Background", mappedBy="author")
     */
    private $backgrounds;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Persona", cascade={"persist", "remove"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rate", mappedBy="user")
     */
    private $rates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     */
    private $comments;

    public function __construct()
    {
        parent::__construct();
        $this->comics = new ArrayCollection();
        $this->personas = new ArrayCollection();
        $this->backgrounds = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->comments = new ArrayCollection();
        // your own logic
    }

    /**
     * @return Collection|Comic[]
     */
    public function getComics(): Collection
    {
        return $this->comics;
    }

    public function getPublicComics(): Collection
    {
        $comics = new ArrayCollection();

        foreach ($this->comics as $comic) {
            if ($comic->getDatePublication()) {
                $comics->add($comic);
            }
        }

        return $comics;
    }

    public function addComic(Comic $comic): self
    {
        if (!$this->comics->contains($comic)) {
            $this->comics[] = $comic;
            $comic->setAuthor($this);
        }

        return $this;
    }

    public function removeComic(Comic $comic): self
    {
        if ($this->comics->contains($comic)) {
            $this->comics->removeElement($comic);
            // set the owning side to null (unless already changed)
            if ($comic->getAuthor() === $this) {
                $comic->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Persona[]
     */
    public function getPersonas(): Collection
    {
        return $this->personas;
    }

    public function addPersona(Persona $persona): self
    {
        if (!$this->personas->contains($persona)) {
            $this->personas[] = $persona;
            $persona->addUser($this);
        }

        return $this;
    }

    public function removePersona(Persona $persona): self
    {
        if ($this->personas->contains($persona)) {
            $this->personas->removeElement($persona);
            $persona->removeUser($this);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Background[]
     */
    public function getBackgrounds(): Collection
    {
        return $this->backgrounds;
    }

    public function addBackground(Background $background): self
    {
        if (!$this->backgrounds->contains($background)) {
            $this->backgrounds[] = $background;
            $background->setUser($this);
        }

        return $this;
    }

    public function removeBackground(Background $background): self
    {
        if ($this->backgrounds->contains($background)) {
            $this->backgrounds->removeElement($background);
            // set the owning side to null (unless already changed)
            if ($background->getUser() === $this) {
                $background->setUser(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?Persona
    {
        return $this->avatar;
    }

    public function getHeadAvatar(): String
    {
        if ($this->avatar) {
            return $this->avatar->getUrlHead();
        }

        return '/persona/creator/p/0001/0000/0000/head-0001-0000-0000-0000-0000-0000-0000-0000.png';
    }

    public function setAvatar(?Persona $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRewritten()
    {
        return Transliterator::urlize($this->getUsername());
    }

    public function getRouteParams()
    {
        return ['id' => $this->getId(), 'rewritten' => $this->getRewritten()];
    }

    /**
     * @return Collection|Rate[]
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates[] = $rate;
            $rate->setUser($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->contains($rate)) {
            $this->rates->removeElement($rate);
            // set the owning side to null (unless already changed)
            if ($rate->getUser() === $this) {
                $rate->setUser(null);
            }
        }

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }
}
