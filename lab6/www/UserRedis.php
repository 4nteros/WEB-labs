<?php
namespace App;

use Predis\Client;

class UserRedis {
    private Client $redis;

    public function __construct() {
        $this->redis = new Client('tcp://redis:6379');
    }

    public function setUserProfile(string $userId, string $name, int $age, int $reputation): void {
        $this->redis->hmset("user:$userId", [
            'name'       => $name,
            'age'        => $age,
            'reputation' => $reputation
        ]);
    }

    public function getUserProfile(string $userId): array {
        $profile = $this->redis->hgetall("user:$userId");
        return is_array($profile) ? $profile : [];
    }

    public function updateAge(string $userId, int $newAge): void {
        $this->redis->hset("user:$userId", 'age', $newAge);
    }

    public function addReputation(string $userId, int $points): int {
        $newReputation = $this->redis->hincrby("user:$userId", 'reputation', $points);
        $this->redis->zadd("user_rating", [$userId => $newReputation]);
        return $newReputation;
    }

    public function getTopUsers(int $limit = 10): array {
        $result = $this->redis->zrevrange("user_rating", 0, $limit - 1, 'WITHSCORES');

        if (empty($result) || !is_array($result)) {
            return [];
        }
        
        $top = [];
        $keys = array_keys($result);
        
        for ($i = 0; $i < count($keys); $i++) {
            $userId = $keys[$i];
            $reputation = $result[$userId];
        
            if (empty($userId)) {
                continue;
            }
            
            $profile = $this->getUserProfile((string)$userId);
            $top[] = [
                'id'         => (string)$userId,
                'name'       => $profile['name'] ?? 'Unknown',
                'reputation' => (int)$reputation,
                'age'        => isset($profile['age']) ? (int)$profile['age'] : 0
            ];
        }
        
        return $top;
    }

    public function deleteUser(string $userId): void {
        $this->redis->del("user:$userId");
        $this->redis->zrem("user_rating", $userId);
    }

    public function getTotalUsers(): int {
        $count = $this->redis->zcard("user_rating");
        return is_int($count) ? $count : 0;
    }
}