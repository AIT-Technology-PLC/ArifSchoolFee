<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArifPayTransactionDetail;

class ArifPayTransactionDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $data = ArifPayTransactionDetail::create(
            [
                'company_id' => 1,
                'gdn_detail_id' => 4,
                'transaction_status' => 'SUCCESS',
                'notification_url' => 'https://65cc9048dd519126b83ee6af.mockapi.io/api/notifyUrl',
                'session_id_number' => '1',
                'uuid' => 'AA56C69DD8FF',
                'nonce' => '291881c0-1d9b-42c1-89d8-06c692b516be',
                'phone' => '251939474140',
                'total_amount' => 200,
                'transaction_id' => 'AA56C69DD8FF',
            ],
        );

        $this->command->info('ArifPayTransactionDetail seeded successfully!');
    }
}
