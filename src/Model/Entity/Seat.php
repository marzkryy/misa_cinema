<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Seat Entity
 *
 * @property int $id
 * @property int $hall_id
 * @property string $seat_row
 * @property string $seat_number
 * @property string $seat_type
 * @property int $seat_price
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Hall $hall
 */
class Seat extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'hall_id' => true,
        'seat_row' => true,
        'seat_number' => true,
        'seat_type' => true,
        'seat_price' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'hall' => true,
    ];
}
