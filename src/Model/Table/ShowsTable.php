<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Shows Model
 *
 * @property \App\Model\Table\BookingsTable&\Cake\ORM\Association\HasMany $Bookings
 * @property \App\Model\Table\TicketsTable&\Cake\ORM\Association\HasMany $Tickets
 * @property \App\Model\Table\HallsTable&\Cake\ORM\Association\BelongsTo $Halls
 *
 * @method \App\Model\Entity\Show newEmptyEntity()
 * @method \App\Model\Entity\Show newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Show[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Show get($primaryKey, $options = [])
 * @method \App\Model\Entity\Show findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Show patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Show[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Show|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Show saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Show[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Show[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Show[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Show[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ShowsTable extends Table
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

        $this->setTable('shows');
        $this->setDisplayField('show_title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Bookings', [
            'foreignKey' => 'show_id',
        ]);
        $this->hasMany('Tickets', [
            'foreignKey' => 'show_id',
        ]);
        $this->hasMany('ShowSeats', [
            'foreignKey' => 'show_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->belongsTo('Halls', [
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
            ->scalar('show_title')
            ->maxLength('show_title', 255)
            ->requirePresence('show_title', 'create')
            ->notEmptyString('show_title');

        $validator
            ->allowEmptyString('avatar');

        $validator
            ->scalar('avatar_dir')
            ->maxLength('avatar_dir', 255)
            ->allowEmptyString('avatar_dir');

        $validator
            ->scalar('genre')
            ->maxLength('genre', 100)
            ->requirePresence('genre', 'create')
            ->notEmptyString('genre');

        $validator
            ->time('show_time')
            ->requirePresence('show_time', 'create')
            ->notEmptyTime('show_time');

        $validator
            ->date('show_date')
            ->requirePresence('show_date', 'create')
            ->notEmptyDate('show_date');

        $validator
            ->integer('status')
            ->notEmptyString('status');

        $validator
            ->integer('hall_id')
            ->allowEmptyString('hall_id'); // Optional for now to avoid breaking existing data

        $validator
            ->integer('duration')
            ->allowEmptyString('duration');

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
        $rules->add($rules->isUnique(
            ['show_title', 'show_date', 'show_time', 'hall_id'],
            'This movie already has a session scheduled at this exact time in this hall.'
        ));

        return $rules;
    }
}
