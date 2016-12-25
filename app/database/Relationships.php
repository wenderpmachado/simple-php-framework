<?php

/**
 * Interface for relationships between tables.
 *
 * @author	Wender Pinto Machado
 * @email wenderpmachado@gmail.com
 * @version 0.7
 */

namespace App\Database;

interface Relationships {
    public function hasOne(&$object, $relationalClass, $foreignKey, $localKey);
    public function hasMany(&$object, $relationalClass, $foreignKey, $localKey);
    public function belongsTo(&$object, $relationalClass, $foreignKey, $localKey);
    public function belongsToMany(&$object, $relationalClass, $foreignKey, $localKey);
//    HasManyThrough
//    PolymorphicRelations
//    ManyToManyPolymorphicRelations
}