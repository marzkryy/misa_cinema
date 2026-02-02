<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SeatLocksTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('seat_locks');
        $this->setDisplayField('seat_label');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->integer('show_id')
            ->requirePresence('show_id', 'create')
            ->notEmptyString('show_id');

        $validator
            ->scalar('seat_label')
            ->maxLength('seat_label', 10)
            ->requirePresence('seat_label', 'create')
            ->notEmptyString('seat_label');

        $validator
            ->scalar('session_id')
            ->maxLength('session_id', 255)
            ->requirePresence('session_id', 'create')
            ->notEmptyString('session_id');

        $validator
            ->dateTime('expires_at')
            ->requirePresence('expires_at', 'create')
            ->notEmptyDateTime('expires_at');

        return $validator;
    }
}
