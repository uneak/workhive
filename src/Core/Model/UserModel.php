<?php

namespace App\Core\Model;

use App\Core\Enum\Status;
use App\Core\Enum\UserRole;

/**
 * Interface for User.
 */
interface UserModel extends ObjectModel
{

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
