<?php

namespace App\Entity;

use Gedmo\Timestampable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EntryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: EntryRepository::class)]
class Entry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'entry', targetEntity: FoodCount::class)]
    private Collection $foodCount;

    // #[ORM\Column]
    // private ?\DateTimeImmutable $created_at = null;

    // #[ORM\Column]
    // private ?\DateTimeImmutable $updated_at = null;

    #[Gedmo\Timestampable(on:"create")]
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $created_at;
 
    #[Gedmo\Timestampable(on:"update")]
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_at;


    public function __construct()
    {
        $this->foodCount = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
        // return "test";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, FoodCount>
     */
    public function getFoodCount(): Collection
    {
        return $this->foodCount;
    }

    public function addFoodCount(FoodCount $foodCount): static
    {
        if (!$this->foodCount->contains($foodCount)) {
            $this->foodCount->add($foodCount);
            $foodCount->setEntry($this);
        }

        return $this;
    }

    public function removeFoodCount(FoodCount $foodCount): static
    {
        if ($this->foodCount->removeElement($foodCount)) {
            // set the owning side to null (unless already changed)
            if ($foodCount->getEntry() === $this) {
                $foodCount->setEntry(null);
            }
        }

        return $this;
    }

    // public function getCreatedAt(): ?\DateTimeImmutable
    // {
    //     return $this->created_at;
    // }

    // public function setCreatedAt(\DateTimeImmutable $created_at): static
    // {
    //     $this->created_at = $created_at;

    //     return $this;
    // }

    // public function getUpdatedAt(): ?\DateTimeImmutable
    // {
    //     return $this->updated_at;
    // }

    // public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    // {
    //     $this->updated_at = $updated_at;

    //     return $this;
    // }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }
 
    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
 
        return $this;
    }
 
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }
 
    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;
 
        return $this;
    }    
}
