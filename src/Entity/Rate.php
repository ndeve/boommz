<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={ "user_id", "comic_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\RateRepository")
 * @UniqueEntity(
 *     fields={"comic", "user"},
 *     message="error"
 * )
 */
class Rate
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
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comic", inversedBy="rates")
     */
    private $comic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="rates")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateUpdate;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->dateUpdate = new \DateTime();
    }

    public function __toString()
    {
        return (string)$this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getComic(): ?Comic
    {
        return $this->comic;
    }

    public function setComic(?Comic $comic): self
    {
        $this->comic = $comic;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDateCreeation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreeation(\DateTimeInterface $dateCreation): self
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
}
