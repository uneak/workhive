<?php

namespace App\Entity;

use App\Core\Model\EquipmentModel;
use App\Core\Model\RoomEquipmentModel;
use App\Core\Model\RoomModel;
use App\Repository\RoomEquipmentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents the association of equipment with a room and its quantity.
 * 
 * Groups:
 * - read: Global read group
 * - write: Global write group
 * - room-equipment:read: Room equipment-specific read group
 * - room-equipment:write: Room equipment-specific write group
 */
#[ORM\Entity(repositoryClass: RoomEquipmentRepository::class)]
#[ORM\Table(name: 'room_equipment')]
class RoomEquipment implements RoomEquipmentModel
{
    public const READ_GROUPS = ['read', RoomEquipmentModel::GROUP_PREFIX . ':read'];
    public const WRITE_GROUPS = ['write', RoomEquipmentModel::GROUP_PREFIX . ':write'];

    /**
     * The unique identifier of the room-equipment association.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(self::READ_GROUPS)]
    private ?int $id = null;

    /**
     * The room associated with the equipment.
     */
    #[ORM\ManyToOne(targetEntity: Room::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Assert\NotNull(message: 'Room is required')]
    private RoomModel $room;

    /**
     * The equipment associated with the room.
     */
    #[ORM\ManyToOne(targetEntity: Equipment::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Assert\NotNull(message: 'Equipment is required')]
    private EquipmentModel $equipment;

    /**
     * The quantity of the equipment in the room.
     */
    #[ORM\Column(type: 'integer')]
    #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
    #[Assert\NotNull(message: 'Quantity is required')]
    #[Assert\GreaterThan(value: 0, message: 'Quantity must be greater than zero')]
    private int $quantity;

    /**
     * The timestamp when the equipment was assigned to the room.
     */
    #[ORM\Column(type: 'datetime')]
    #[Groups(self::READ_GROUPS)]
    private DateTime $assignedAt;

    public function __construct()
    {
        $this->assignedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): RoomModel
    {
        return $this->room;
    }

    #[Groups(self::READ_GROUPS)]
    public function getRoomId(): ?int
    {
        return $this->room?->getId();
    }

    public function setRoom(RoomModel $room): static
    {
        $this->room = $room;
        return $this;
    }

    public function getEquipment(): EquipmentModel
    {
        return $this->equipment;
    }

    #[Groups(self::READ_GROUPS)]
    public function getEquipmentId(): ?int
    {
        return $this->equipment?->getId();
    }

    public function setEquipment(EquipmentModel $equipment): static
    {
        $this->equipment = $equipment;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getAssignedAt(): DateTime
    {
        return $this->assignedAt;
    }

    public function setAssignedAt(DateTime $assignedAt): static
    {
        $this->assignedAt = $assignedAt;
        return $this;
    }
}
