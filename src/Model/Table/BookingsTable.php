<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bookings Model
 *
 * @property \App\Model\Table\CustomersTable&\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\ShowsTable&\Cake\ORM\Association\BelongsTo $Shows
 * @property \App\Model\Table\HallsTable&\Cake\ORM\Association\BelongsTo $Halls
 * @property \App\Model\Table\SeatsTable&\Cake\ORM\Association\BelongsTo $Seats
 * @property \App\Model\Table\PaymentsTable&\Cake\ORM\Association\HasMany $Payments
 * @property \App\Model\Table\TicketsTable&\Cake\ORM\Association\HasMany $Tickets
 *
 * @method \App\Model\Entity\Booking newEmptyEntity()
 * @method \App\Model\Entity\Booking newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Booking[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Booking get($primaryKey, $options = [])
 * @method \App\Model\Entity\Booking findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Booking patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Booking[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Booking|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Booking saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BookingsTable extends Table
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

        $this->setTable('bookings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Customers', [
            'foreignKey' => 'cust_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Shows', [
            'foreignKey' => 'show_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Halls', [
            'foreignKey' => 'hall_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Seats', [
            'foreignKey' => 'seat_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Payments', [
            'foreignKey' => 'booking_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('Tickets', [
            'foreignKey' => 'booking_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
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
            ->integer('cust_id')
            ->notEmptyString('cust_id');

        $validator
            ->integer('show_id')
            ->notEmptyString('show_id');

        $validator
            ->dateTime('book_date_time')
            ->notEmptyDateTime('book_date_time');

        $validator
            ->integer('hall_id')
            ->notEmptyString('hall_id');

        $validator
            ->integer('seat_id')
            ->notEmptyString('seat_id');

        $validator
            ->integer('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmptyString('quantity');

        $validator
            ->scalar('ticket_price')
            ->maxLength('ticket_price', 15)
            ->requirePresence('ticket_price', 'create')
            ->notEmptyString('ticket_price');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

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
        $rules->add($rules->existsIn('cust_id', 'Customers'), ['errorField' => 'cust_id']);
        $rules->add($rules->existsIn('show_id', 'Shows'), ['errorField' => 'show_id']);
        $rules->add($rules->existsIn('hall_id', 'Halls'), ['errorField' => 'hall_id']);
        $rules->add($rules->existsIn('seat_id', 'Seats'), ['errorField' => 'seat_id']);

        return $rules;
    }
}
