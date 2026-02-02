<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShowSeats Model
 *
 * @property \App\Model\Table\ShowsTable&\Cake\ORM\Association\BelongsTo $Shows
 *
 * @method \App\Model\Entity\ShowSeat newEmptyEntity()
 * @method \App\Model\Entity\ShowSeat newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ShowSeat[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ShowSeat get($primaryKey, $options = [])
 * @method \App\Model\Entity\ShowSeat findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ShowSeat patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ShowSeat[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ShowSeat|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShowSeat saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShowSeat[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ShowSeat[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ShowSeat[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ShowSeat[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ShowSeatsTable extends Table
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

        $this->setTable('show_seats');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

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
            ->integer('show_id')
            ->notEmptyString('show_id');

        $validator
            ->scalar('seat_row')
            ->maxLength('seat_row', 10)
            ->requirePresence('seat_row', 'create')
            ->notEmptyString('seat_row');

        $validator
            ->integer('seat_number')
            ->requirePresence('seat_number', 'create')
            ->notEmptyString('seat_number');

        $validator
            ->scalar('seat_type')
            ->maxLength('seat_type', 50)
            ->requirePresence('seat_type', 'create')
            ->notEmptyString('seat_type');

        $validator
            ->decimal('seat_price')
            ->requirePresence('seat_price', 'create')
            ->notEmptyString('seat_price');

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
        $rules->add($rules->existsIn('show_id', 'Shows'), ['errorField' => 'show_id']);

        return $rules;
    }
}
