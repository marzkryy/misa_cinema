<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Staff Entity
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Staff extends Entity
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
        'name' => true,
        'phone' => true,
        'email' => true,
        'status' => true,
        'password' => true,
        'reset_token' => true,
        'reset_expiry' => true,
        'is_verified' => true,
        'verification_token' => true,
        'temp_email' => true,
        'email_code' => true,
        'email_code_expiry' => true,
        'role' => true,
        'created' => true,
        'modified' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    /**
     * Hash password
     *
     * @param string $password Password
     * @return string|null
     */
    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new \Cake\Auth\DefaultPasswordHasher())->hash($password);
        }
        return $password;
    }
}
