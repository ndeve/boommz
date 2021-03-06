<?php

namespace App\Entity;

use Behat\Transliterator\Transliterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonaRepository")
 */
class Persona
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $path;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $public;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="personas")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $job;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nationality;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $biography;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Persona", inversedBy="personas")
     */
    private $friends;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Persona", mappedBy="friends")
     */
    private $personas;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasHead;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $sex;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->users = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->personas = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

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
     * @return Collection|user[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(user $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(user $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function getUrl()
    {
        if ('stars/' === $this->getPath()) {
            return '/persona/'. $this->getPath() . Transliterator::urlize($this->getName()) .'.png';
        }

        $dir = str_replace('-', '/', $this->getPath());
        $dir = str_replace('w', '', $dir);
        $dir = str_replace('m', '', $dir);
        $dir = substr($dir, 0, 15);

        return '/persona/creator/p/'.  $dir . $this->getPath() .'.png';
    }

    public function getUrlHead()
    {
        if ('stars/' === $this->getPath()) {
            return '/persona/'. $this->getPath() . 'head/'. Transliterator::urlize($this->getName()) .'.png';
        }

        $path = str_replace('w', '', $this->getPath());
        $path = str_replace('m', '', $path);

        $dir = str_replace('-', '/', $path);
        $dir = substr($dir, 0, 15);

        return '/persona/creator/p/'. $dir . 'head-'. $path .'.png';
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getRewritten()
    {
        return Transliterator::urlize($this->getName());
    }

    public function getRouteParams()
    {
        if ('stars/' === $this->getPath()) {
            return [ 'id' => $this->getId(), 'rewritten' => $this->getRewritten() ];
        }
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(self $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
        }

        return $this;
    }

    public function removeFriend(self $friend): self
    {
        if ($this->friends->contains($friend)) {
            $this->friends->removeElement($friend);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPersonas(): Collection
    {
        return $this->personas;
    }

    public function addPersona(self $persona): self
    {
        if (!$this->personas->contains($persona)) {
            $this->personas[] = $persona;
            $persona->addFriend($this);
        }

        return $this;
    }

    public function removePersona(self $persona): self
    {
        if ($this->personas->contains($persona)) {
            $this->personas->removeElement($persona);
            $persona->removeFriend($this);
        }

        return $this;
    }

	public function getComic(): ?comic
                           	{
                           		return $this->comic;
                           	}

    public function isStar(): bool
    {
    	if ('stars/' === $this->getPath()) {
    		return true;
	    }

    	return false;
    }

    public function getHasHead(): ?bool
    {
        return $this->hasHead;
    }

    public function setHasHead(bool $hasHead): self
    {
        $this->hasHead = $hasHead;

        return $this;
    }

    public function getColor(): ?int
    {
        return $this->color;
    }

    public function setColor(int $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }
}
