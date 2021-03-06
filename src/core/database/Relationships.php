<?php

/**
 * Interface for relationships between tables.
 *
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.7
 */

namespace Core\Database;

interface Relationships {
    public function hasOne(&$object, $relationalClass, $foreignKey, $localKey);
    public function hasMany(&$object, $relationalClass, $foreignKey, $localKey);
    public function belongsTo(&$object, $relationalClass, $foreignKey, $localKey);
    public function belongsToMany(&$object, $relationalClass, $foreignKey, $localKey);
//    HasManyThrough
//    PolymorphicRelations
//    ManyToManyPolymorphicRelations
}