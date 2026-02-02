<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tickets Model
 *
 * @property \App\Model\Table\SeatsTable&\Cake\ORM\Association\BelongsTo $Seats
 * @property \App\Model\Table\HallsTable&\Cake\ORM\Association\BelongsTo $Halls
 * @property \App\Model\Table\BookingsTable&\Cake\ORM\Association\BelongsTo $Bookings
 * @property \App\Model\Table\ShowsTable&\Cake\ORM\Association\BelongsTo $Shows
 *
 * @method \App\Model\Entity\Ticket newEmptyEntity()
 * @method \App\Model\Entity\Ticket newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Ticket[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ticket get($primaryKey, $options = [])
 * @method \App\Model\Entity\Ticket findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Ticket patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Ticket[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ticket|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ticket saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TicketsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tickets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShowSeats', [
            'foreignKey' => 'show_seat_id',
        ]);
        $this->belongsTo('Seats', [
            'foreignKey' => 'seat_id',
            'joinType' => 'LEFT',
        ]);
        $this->belongsTo('Halls', [
            'foreignKey' => 'hall_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Bookings', [
            'foreignKey' => 'booking_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Shows', [
            'foreignKey' => 'show_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('seat_id')
            ->notEmptyString('seat_id');

        $validator
            ->integer('hall_id')
            ->notEmptyString('hall_id');

        $validator
            ->integer('booking_id')
            ->notEmptyString('booking_id');

        $validator
            ->integer('show_id')
            ->notEmptyString('show_id');

        $validator
            ->integer('status')
            ->notEmptyString('status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('seat_id', 'Seats'), ['errorField' => 'seat_id']);
        $rules->add($rules->existsIn('hall_id', 'Halls'), ['errorField' => 'hall_id']);
        $rules->add($rules->existsIn('booking_id', 'Bookings'), ['errorField' => 'booking_id']);
        $rules->add($rules->existsIn('show_id', 'Shows'), ['errorField' => 'show_id']);

        return $rules;
    }
}
