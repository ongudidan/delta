<?php

namespace app\components;

use yii\base\Component;
use Ramsey\Uuid\Uuid;

class IdGenerator extends Component
{
   

    public static function generateUniqueId()
    {
        // Generate a UUID
        $uuid = Uuid::uuid4()->toString();

        // Get the current timestamp with microseconds
        $timestamp = microtime(true);

        // Generate a random string of characters (16 bytes = 32 hex characters)
        $randomString = bin2hex(random_bytes(16)); // 16 bytes of randomness

        // Combine elements and hash them to produce a unique ID
        $uniqueId = hash('sha256', $uuid . $timestamp . $randomString);

        // Return the generated unique ID
        return $uniqueId;
    }


}
