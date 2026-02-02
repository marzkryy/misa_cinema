<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class SeatLock extends Entity
{
    protected $_accessible = [
        'show_id' => true,
        'seat_label' => true,
        'session_id' => true,
        'expires_at' => true,
        'created' => true,
    ];
}
