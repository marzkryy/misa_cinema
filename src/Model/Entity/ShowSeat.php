<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShowSeat Entity
 *
 * @property int $id
 * @property int $show_id
 * @property string $seat_row
 * @property int $seat_number
 * @property string $seat_type
 * @property string $seat_price
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Show $show
 */
class ShowSeat extends Entity
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
        'show_id' => true,
        'seat_row' => true,
        'seat_number' => true,
        'seat_type' => true,
        'seat_price' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'show' => true,
    ];
}
