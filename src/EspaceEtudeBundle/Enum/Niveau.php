<?php
 namespace EspaceEtudeBundle\Enum;

class Niveau
 {
     const TYPE_1A    = "1A";
     const TYPE_2A = "2A";
     const TYPE_2B  = "2B";
     const TYPE_3A = "3A";
     const TYPE_3B  = "3B";
     const TYPE_4A  = "4A";
     const TYPE_4B  = "4B";
     const TYPE_5A  = "5A";
     const TYPE_5B  = "5B";

     /** @var array user friendly named type */
     protected static $typeName = [
         self::TYPE_1A    => '1A',
         self::TYPE_2A => '2A',
         self::TYPE_2B => '2B',
         self::TYPE_3A  => '3A',
         self::TYPE_3B=> '3B',
         self::TYPE_4A=> '4A',
         self::TYPE_4B=> '4B',
         self::TYPE_5A=> '5A',
         self::TYPE_5B=> '5B',
     ];

     /**
      * @param  string $typeShortName
      * @return string
      */
     public static function getTypeName($typeShortName)
     {
         if (!isset(static::$typeName[$typeShortName])) {
             return "Unknown type ($typeShortName)";
         }

         return static::$typeName[$typeShortName];
     }

     /**
      * @return array<string>
      */
     public static function getAvailableTypes()
     {
         return [
             self::TYPE_1A    => '1A',
             self::TYPE_2A => '2A',
             self::TYPE_2B => '2B',
             self::TYPE_3A  => '3A',
             self::TYPE_3B=> '3B',
             self::TYPE_4A=> '4A',
             self::TYPE_4B=> '4B',
             self::TYPE_5A=> '5A',
             self::TYPE_5B=> '5B'
         ];
     }
 }
