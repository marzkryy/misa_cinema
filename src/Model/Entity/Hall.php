<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Hall Entity
 *
 * @property int $id
 * @property string $hall_type
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Seat[] $seats
 * @property \App\Model\Entity\Ticket[] $tickets
 */
class Hall extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'hall_type' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'seats' => true,
        'tickets' => true,
    ];
}
