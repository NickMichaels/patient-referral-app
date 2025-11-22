<?php

namespace App\Cache;

use App\Entity\Provider;
use App\Repository\ProviderRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProviderCache
{
    public function __construct(private CacheInterface $cache, private ProviderRepository $repository)
    {
    }

    /**
     * Final all providers in the system using the cache
     *
     * @return ?array<Provider>
     */
    public function findAll(): ?array
    {
        $key = sprintf("find-all-providers-%d", time());

        return $this->cache->get($key, function (ItemInterface $item) {

            $item->expiresAfter(3600);

            return $this->repository->findAll();
        });
    }
}
