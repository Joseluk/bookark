<?php

namespace App\DataProvider;

interface DataProviderInterface
{
    public function fetchData(string $query): array;
}
