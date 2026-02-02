<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Booking Entity
 *
 * @property int $id
 * @property int $cust_id
 * @property int $show_id
 * @property \Cake\I18n\FrozenTime $book_date_time
 * @property int $hall_id
 * @property int $seat_id
 * @property int $quantity
 * @property string $ticket_price
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Show $show
 * @property \App\Model\Entity\Hall $hall
 * @property \App\Model\Entity\Seat $seat
 * @property \App\Model\Entity\Payment[] $payments
 * @property \App\Model\Entity\Ticket[] $tickets
 */
class Booking extends Entity
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
        'cust_id' => true,
        'show_id' => true,
        'book_date_time' => true,
        'hall_id' => true,
        'seat_id' => true,
        'quantity' => true,
        'ticket_price' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'customer' => true,
        'show' => true,
        'hall' => true,
        'seat' => true,
        'payments' => true,
        'tickets' => true,
        'seat_selection' => true,
    ];
}
