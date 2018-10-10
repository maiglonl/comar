<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BillRepository.
 *
 * @package namespace App\Repositories;
 */
interface BillRepository extends RepositoryInterface {
    public function generateBills($order_id);
}
