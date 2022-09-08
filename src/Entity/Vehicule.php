<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
#[UniqueEntity('identification')]
class Vehicule
{
    public const TYPE = [
        'CAR'        => 'car',
        'MOTORCYCLE' => 'motorcycle',
        'SCOOTER'    => 'scooter'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(
        [
            'vehicule:read:collection',
            'vehicule:write:item',
            'maintenance:read:collection',
            'maintenance:read:item',
            'maintenance:write:item'
        ]
    )]
    private int $id;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['vehicule:read:item', 'vehicule:read:collection', 'vehicule:write:item'])]
    #[
        Assert\NotBlank(),
        Assert\Choice(
            choices: [
                self::TYPE['CAR'],
                self::TYPE['MOTORCYCLE'],
                self::TYPE['SCOOTER']
            ]
        )
    ]
    private string $type;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['vehicule:read:item', 'vehicule:read:collection', 'vehicule:write:item'])]
    #[
        Assert\NotBlank(),
        Assert\Length(max: 20)
    ]
    private string $identification;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['vehicule:read:item', 'vehicule:read:collection', 'vehicule:write:item'])]
    #[
        Assert\NotBlank(),
        Assert\Length(max: 50)
    ]
    private string $brand;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['vehicule:read:item', 'vehicule:read:collection', 'vehicule:write:item'])]
    #[
        Assert\NotBlank(),
        Assert\Length(max: 50)
    ]
    private string $reference;

    #[ORM\Column(type: 'integer')]
    #[Groups(['vehicule:read:item', 'vehicule:read:collection', 'vehicule:write:item'])]
    #[
        Assert\NotBlank(),
        Assert\Type(type: 'integer')
    ]
    private int $modelyear;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['vehicule:read:item', 'vehicule:write:item'])]
    #[
        Assert\NotBlank(),
        Assert\Type('\DateTimeInterface')
    ]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['vehicule:read:item', 'vehicule:write:item'])]
    #[Assert\Type('\DateTimeInterface')]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'vehicule', targetEntity: Maintenance::class, orphanRemoval: true)]
    #[Groups(['vehicule:read:item', 'vehicule:write:item'])]
    private Collection $maintenances;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'vehicules')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['vehicule:write:item'])]
    private User $user;

    public function __construct()
    {
        $this->maintenances = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Vehicule
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentification(): string
    {
        return $this->identification;
    }

    /**
     * @param string $identification
     * @return Vehicule
     */
    public function setIdentification(string $identification): self
    {
        $this->identification = $identification;

        return $this;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     * @return Vehicule
     */
    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return Vehicule
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return int
     */
    public function getModelyear(): int
    {
        return $this->modelyear;
    }

    /**
     * @param int $modelyear
     * @return Vehicule
     */
    public function setModelyear(int $modelyear): self
    {
        $this->modelyear = $modelyear;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return Vehicule
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface|null $updatedAt
     * @return Vehicule
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Maintenance>
     */
    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    /**
     * @param Maintenance $maintenance
     * @return $this
     */
    public function addMaintenance(Maintenance $maintenance): self
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances[] = $maintenance;
            $maintenance->setVehicule($this);
        }

        return $this;
    }

    /**
     * @param Maintenance $maintenance
     * @return $this
     */
    public function removeMaintenance(Maintenance $maintenance): self
    {
        if ($this->maintenances->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getVehicule() === $this) {
                $maintenance->setVehicule(null);
            }
        }

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
}
