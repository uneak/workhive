<?php

namespace App\Core\Model;

/**
 * Interface for Equipment.
 */
interface EquipmentModel extends ObjectModel
{
    public const GROUP_PREFIX = 'equipment';
    public const CREATE_GROUPS = [ObjectModel::CREATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::CREATE_PREFIX];
    public const UPDATE_GROUPS = [ObjectModel::UPDATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::UPDATE_PREFIX];
    public const WRITE_GROUPS = [...self::CREATE_GROUPS, ...self::UPDATE_GROUPS];
    public const READ_GROUPS = [ObjectModel::READ_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::READ_PREFIX];
    public const RW_GROUPS = [...self::READ_GROUPS, ...self::WRITE_GROUPS];

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
