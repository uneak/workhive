<?php

namespace App\Core\Model;

use DateTime;

/**
 * Interface for RoomEquipment.
 */
interface RoomEquipmentModel extends ObjectModel
{

    /**
     * Get the room associated with the equipment.
     *
     * @return RoomModel
     */
    public function getRoom(): RoomModel;

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
     * @return $this
     */
    public function setQuantity(int $quantity): static;

}
