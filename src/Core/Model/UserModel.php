<?php

namespace App\Core\Model;

use App\Core\Enum\Status;
use App\Core\Enum\UserRole;

/**
 * Interface for User.
 */
interface UserModel extends ObjectModel
{
    public const GROUP_PREFIX = 'user';
    public const CREATE_GROUPS = [ObjectModel::CREATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::CREATE_PREFIX];
    public const UPDATE_GROUPS = [ObjectModel::UPDATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::UPDATE_PREFIX];
    public const WRITE_GROUPS = [...self::CREATE_GROUPS, ...self::UPDATE_GROUPS];
    public const READ_GROUPS = [ObjectModel::READ_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::READ_PREFIX];
    public const RW_GROUPS = [...self::READ_GROUPS, ...self::WRITE_GROUPS];
    /**
     * Get the role of the user.
     *
     * @return UserRole|null
     */
    public function getUserRole(): ?UserRole;

    /**
     * Set the role of the user.
     *
     * @param UserRole $role
     * @return static
     */
    public function setUserRole(UserRole $role): static;

    /**
     * Get the status of the user.
     *
     * @return Status|null
     */
    public function getStatus(): ?Status;

    /**
     * Set the status of the user.
     *
     * @param Status $status
     * @return static
     */
    public function setStatus(Status $status): static;
}
