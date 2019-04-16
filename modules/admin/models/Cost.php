<?php

namespace app\modules\admin\models;

use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "cost".
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $name
 * @property double $price
 * @property int $unitgroup_id
 * @property int $expense_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Expense $expense
 * @property Supplier $supplier
 * @property UnitGroup $unitgroup
 */
class Cost extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cost';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_id', 'name', 'price', 'unitgroup_id', 'expense_id'], 'required'],
            [['supplier_id', 'unitgroup_id', 'expense_id'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['expense_id'], 'exist', 'skipOnError' => true, 'targetClass' => Expense::className(), 'targetAttribute' => ['expense_id' => 'id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplier_id' => 'id']],
            [['unitgroup_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnitGroup::className(), 'targetAttribute' => ['unitgroup_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Поставщик',
            'name' => 'Наименование',
            'price' => 'Цена, руб',
            'unitgroup_id' => 'Подразделение',
            'expense_id' => 'Статья расхода',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpense()
    {
        return $this->hasOne(Expense::className(), ['id' => 'expense_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitgroup()
    {
        return $this->hasOne(UnitGroup::className(), ['id' => 'unitgroup_id']);
    }
}
