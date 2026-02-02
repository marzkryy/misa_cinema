<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ticket Entity
 *
 * @property int $id
 * @property int $seat_id
 * @property int $hall_id
 * @property int $booking_id
 * @property int $show_id
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Seat $seat
 * @property \App\Model\Entity\Hall $hall
 * @property \App\Model\Entity\Booking $booking
 * @property \App\Model\Entity\Show $show
 */
class Ticket extends Entity
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
        'seat_id' => true,
        'hall_id' => true,
        'booking_id' => true,
        'show_id' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'seat' => true,
        'hall' => true,
        'booking' => true,
        'show' => true,
    ];
}
