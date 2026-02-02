<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Halls Model
 *
 * @property \App\Model\Table\SeatsTable&\Cake\ORM\Association\HasMany $Seats
 * @property \App\Model\Table\TicketsTable&\Cake\ORM\Association\HasMany $Tickets
 *
 * @method \App\Model\Entity\Hall newEmptyEntity()
 * @method \App\Model\Entity\Hall newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Hall[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Hall get($primaryKey, $options = [])
 * @method \App\Model\Entity\Hall findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Hall patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Hall[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Hall|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hall saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Hall[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Hall[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Hall[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Hall[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HallsTable extends Table
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

        $this->setTable('halls');
        $this->setDisplayField('hall_type');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Seats', [
            'foreignKey' => 'hall_id',
        ]);
        $this->hasMany('Tickets', [
            'foreignKey' => 'hall_id',
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
            ->scalar('hall_type')
            ->maxLength('hall_type', 100)
            ->requirePresence('hall_type', 'create')
            ->notEmptyString('hall_type');

        $validator
            ->integer('status')
            ->notEmptyString('status');

        return $validator;
    }
}
