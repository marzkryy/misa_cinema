<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payment Entity
 *
 * @property int $id
 * @property int $booking_id
 * @property \Cake\I18n\FrozenTime $payment_date_time
 * @property string $payment_method
 * @property string $total_price
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Booking $booking
 */
class Payment extends Entity
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
        'booking_id' => true,
        'payment_date_time' => true,
        'payment_method' => true,
        'total_price' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'booking' => true,
    ];
}
