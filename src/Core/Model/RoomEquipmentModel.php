<?php

namespace App\Core\Model;

use DateTime;

/**
 * Interface for RoomEquipment.
 */
interface RoomEquipmentModel extends ObjectModel
{
    public const GROUP_PREFIX = 'room_equipment';

    /**
     * Get the room associated with the equipment.
     *
     * @return RoomModel
     */
    public function getRoom(): RoomModel;

    /**
     * Get the ID of the room associated with the equipment.
     */
    public function getRoomId(): ?int;

    /**
     * Set the room associated with the equipment.
     *
     * @param RoomModel $room
     *
     * @return static
     */
    public function setRoom(RoomModel $room): static;

    /**
     * Get the equipment associated with the room.
     *
     * @return EquipmentModel
     */
    public function getEquipment(): EquipmentModel;

    /**
     * Get the ID of the equipment associated with the room.
     */
    public function getEquipmentId(): ?int;

    /**
     * Set the equipment associated with the room.
     *
     * @param EquipmentModel $equipment
     *
     * @return static
     */
    public function setEquipment(EquipmentModel $equipment): static;

    /**
     * Get the quantity of the equipment in the room.
     *
     * @return int
     */
    public function getQuantity(): int;

    /**
     * Set the quantity of the equipment in the room.
     *
     * @param int $quantity
     *
     * @return static
     */
    public function setQuantity(int $quantity): static;

}
