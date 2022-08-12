<?php

namespace App\Entity;

use App\Repository\MaintenanceRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MaintenanceRepository::class)]
class Maintenance
{
    public const TYPE = [
        'MAINTENANCE' => 'maintenance',
        'REPAIR'      => 'repair',
        'RESTORATION' => 'restoration'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(
        [
            'maintenance:read:collection',
            'maintenance:write:item',
            'vehicule:read:collection',
            'vehicule:read:item',
            'vehicule:write:item'
        ]
    )]
    private int $id;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['maintenance:read:item', 'maintenance:read:collection', 'maintenance:write:item'])]
    #[
        Assert\NotBlank(),
        Assert\Choice(
            choices: [
                self::TYPE['MAINTENANCE'],
                self::TYPE['REPAIR'],
                self::TYPE['RESTORATION']
            ]
        )
    ]
    private string $type;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['maintenance:read:item', 'maintenance:read:collection', 'maintenance:write:item'])]
    #[
        Assert\NotBlank(),
        Assert\Type('\DateTimeInterface')
    ]
    private DateTimeInterface $date;

    #[ORM\Column(type: 'float')]
    #[Groups(['maintenance:read:item', 'maintenance:read:collection', 'maintenance:write:item'])]
    #[
        Assert\NotBlank(),
        Assert\Type('float')
    ]
    private float $amount;

    #[ORM\Column(type: 'text')]
    #[Groups(['maintenance:read:item', 'maintenance:write:item'])]
    #[Assert\NotBlank()]
    private string $description;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['maintenance:read:item', 'maintenance:write:item'])]
    #[
        Assert\NotBlank(),
        Assert\Type('\DateTimeInterface')
    ]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['maintenance:read:item', 'maintenance:write:item'])]
    #[Assert\Type('\DateTimeInterface')]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Vehicule::class, inversedBy: 'maintenances')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['maintenance:write:item'])]
    private Vehicule $vehicule;

    public function __construct()
    {
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
     * @param int $id
     * @return Maintenance
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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
     * @return Maintenance
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface $date
     * @return Maintenance
     */
    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Maintenance
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Maintenance
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

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
     * @return Maintenance
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
     * @return Maintenance
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Vehicule|null
     */
    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    /**
     * @param Vehicule|null $vehicule
     * @return Maintenance
     */
    public function setVehicule(?Vehicule $vehicule): self
    {
        $this->vehicule = $vehicule;

        return $this;
    }
}
