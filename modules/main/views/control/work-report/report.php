<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Учет времени';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visit-index">

    <div class="page-header text-center">
        <h2>Учет времени присутствия на выставке</h2>
        <small>За период с <?= $start ?> по <?= $finish ?> </small>
    </div>

    <p>
        <?//= Html::a('Новая запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <table class="table table-hover">
        <tr><th>Дата\Период</th><th>10-11</th><th>11-12</th><th>12-13</th><th>13-14</th><th>14-15</th><th>15-16</th><th>16-17</th><th>17-18</th><th>18-19</th>
            <th>19-20</th><th>20-21</th>
        </tr>
        <?= $content ?>
    </table>
</div>
