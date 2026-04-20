<?php

namespace App\Contracts;

use App\Models\Bid;

interface BidServiceInterface
{
    public function create(array $data, int $projectId);
    public function accept(Bid $bid);
}
