<?php

namespace App\Core\Model;

/**
 * Interface for Equipment.
 */
interface EquipmentModel extends ObjectModel
{
    public const GROUP_PREFIX = 'equipment';

    /**
     * Get the total stock available for the equipment.
     *
     * @return int
     */
    public function getTotalStock(): int;

    /**
     * Set the total stock available for the equipment.
     *
     * @param int $totalStock
     * @return static
     */
    public function setTotalStock(int $totalStock): static;
}
