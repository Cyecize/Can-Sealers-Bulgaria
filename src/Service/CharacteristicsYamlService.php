<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 4:44 PM
 */

namespace App\Service;

use App\BindingModel\CharacteristicBindingModel;
use App\Exception\IllegalArgumentException;
use App\ViewModel\CharacteristicViewModel;

interface CharacteristicsYamlService
{
    /**
     * @param CharacteristicBindingModel $bindingModel
     * @throws IllegalArgumentException
     */
    public function saveCharacteristic(CharacteristicBindingModel $bindingModel) : void ;

    /**
     * @param $id
     * @return CharacteristicViewModel|null
     */
    public function findOneById($id) : ?CharacteristicViewModel;

    /**
     * @return CharacteristicViewModel[]
     */
    public function getCharacteristics() : array ;
}