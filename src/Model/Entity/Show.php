<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Show Entity
 *
 * @property int $id
 * @property string $show_title
 * @property string $avatar
 * @property string $avatar_dir
 * @property string $genre
 * @property \Cake\I18n\Time $show_time
 * @property \Cake\I18n\FrozenDate $show_date
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Booking[] $bookings
 * @property \App\Model\Entity\Ticket[] $tickets
 * @property \App\Model\Entity\Hall $hall
 * @property int $hall_id
 */
class Show extends Entity
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
        'show_title' => true,
        'avatar' => true,
        'avatar_dir' => true,
        'genre' => true,
        'show_time' => true,
        'show_date' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'bookings' => true,
        'tickets' => true,
        'hall' => true,
        'hall_id' => true,
        'duration' => true,
    ];
}
