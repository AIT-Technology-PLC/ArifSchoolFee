<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Arr;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $items = [
            [
                'company_id' => '12',
                'company_name' => 'Dejene Lemessa Trading',
                'plan_name' => 'professional',
                'starts_on' => '2023-09-21',
                'months' => '12',
            ],
            [
                'company_id' => '14',
                'company_name' => 'Ethioagra Trading',
                'plan_name' => 'professional',
                'starts_on' => '2023-03-6',
                'months' => '12',
            ],
            [
                'company_id' => '18',
                'company_name' => 'Onrica Technologies PLC',
                'plan_name' => 'professional',
                'starts_on' => '2023-01-1',
                'months' => '12',
            ],
            [
                'company_id' => '19',
                'company_name' => 'Grasel Trading PLC',
                'plan_name' => 'v2-premium',
                'starts_on' => '2023-07-6',
                'months' => '12',
            ],
            [
                'company_id' => '20',
                'company_name' => 'Allin Global Printing Press PLC',
                'plan_name' => 'professional',
                'starts_on' => '2023-07-25',
                'months' => '12',
            ],
            [
                'company_id' => '27',
                'company_name' => 'IZARA PHARMACEUTICALS',
                'plan_name' => 'v3-professional',
                'starts_on' => '2023-10-24',
                'months' => '12',
            ],
            [
                'company_id' => '40',
                'company_name' => 'SAH PHARMACEUTICALS & MEDICAL IMPORTER',
                'plan_name' => 'professional',
                'starts_on' => '2022-11-11',
                'months' => '13',
            ],
            [
                'company_id' => '43',
                'company_name' => 'ANS Import and Export',
                'plan_name' => 'standard',
                'starts_on' => '2022-12-13',
                'months' => '12',
            ],
            [
                'company_id' => '46',
                'company_name' => 'BIG BOSS MO TRADING PLC',
                'plan_name' => 'professional',
                'starts_on' => '2022-12-20',
                'months' => '12',
            ],
            [
                'company_id' => '47',
                'company_name' => 'Sami House Hold & Office Furniture',
                'plan_name' => 'v2-professional',
                'starts_on' => '2022-12-8',
                'months' => '12',
            ],
            [
                'company_id' => '48',
                'company_name' => 'COMFORT ZONE SHOES SHOP',
                'plan_name' => 'standard',
                'starts_on' => '2023-01-25',
                'months' => '12',
            ],
            [
                'company_id' => '50',
                'company_name' => 'AGS IMPORT EXPORT',
                'plan_name' => 'v2-professional',
                'starts_on' => '2023-01-16',
                'months' => '12',
            ],
            [
                'company_id' => '52',
                'company_name' => 'Rani Ceramics',
                'plan_name' => 'v2-standard',
                'starts_on' => '2023-03-24',
                'months' => '12',
            ],
            [
                'company_id' => '53',
                'company_name' => 'Tamar Trading PLC',
                'plan_name' => 'standard',
                'starts_on' => '2023-01-2',
                'months' => '12',
            ],
            [
                'company_id' => '56',
                'company_name' => 'TEGA GENERAL TRADING',
                'plan_name' => 'v2-production',
                'starts_on' => '2023-01-3',
                'months' => '12',
            ],
            [
                'company_id' => '58',
                'company_name' => 'HMS general trading',
                'plan_name' => 'v2-standard',
                'starts_on' => '2023-03-24',
                'months' => '12',
            ],
            [
                'company_id' => '60',
                'company_name' => 'YENOHAS CERAMICS',
                'plan_name' => 'standard',
                'starts_on' => '2023-03-15',
                'months' => '12',
            ],
            [
                'company_id' => '61',
                'company_name' => 'AONE MARBLE & GRANITE PLC',
                'plan_name' => 'v2-hr',
                'starts_on' => '2023-03-13',
                'months' => '12',
            ],
            [
                'company_id' => '63',
                'company_name' => 'GIBE DRUG STORE',
                'plan_name' => 'v2-starter',
                'starts_on' => '2023-03-22',
                'months' => '12',
            ],
            [
                'company_id' => '64',
                'company_name' => 'REBO DRUG STORE',
                'plan_name' => 'v2-starter',
                'starts_on' => '2023-03-23',
                'months' => '12',
            ],
            [
                'company_id' => '65',
                'company_name' => 'FWB Health and Social Services PLC',
                'plan_name' => 'v2-professional',
                'starts_on' => '2023-03-24',
                'months' => '12',
            ],
            [
                'company_id' => '67',
                'company_name' => 'SAS CONSTRUCTION CHEMICALS LTD (ETHIOPIAN BRANCH)',
                'plan_name' => 'v2-premium',
                'starts_on' => '2023-05-2',
                'months' => '12',
            ],
            [
                'company_id' => '69',
                'company_name' => 'Hanan PLC',
                'plan_name' => 'v2-premium',
                'starts_on' => '2023-08-14',
                'months' => '12',
            ],
            [
                'company_id' => '70',
                'company_name' => 'Elva Trading PLC',
                'plan_name' => 'v2-starter',
                'starts_on' => '2023-05-15',
                'months' => '12',
            ],
            [
                'company_id' => '72',
                'company_name' => 'JS Construction Machinery Spare Part Importers',
                'plan_name' => 'v2-professional',
                'starts_on' => '2023-05-14',
                'months' => '12',
            ],
            [
                'company_id' => '75',
                'company_name' => 'KAN ELECTRICS TRADING PLC',
                'plan_name' => 'v2-professional',
                'starts_on' => '2023-07-4',
                'months' => '12',
            ],
            [
                'company_id' => '76',
                'company_name' => 'JONY CERAMICS',
                'plan_name' => 'v2-starter',
                'starts_on' => '2023-07-19',
                'months' => '12',
            ],
            [
                'company_id' => '77',
                'company_name' => 'JIGJIG TRADING PLC',
                'plan_name' => 'v2-standard',
                'starts_on' => '2023-07-18',
                'months' => '12',
            ],
            [
                'company_id' => '78',
                'company_name' => 'ZAD Pharmacy',
                'plan_name' => 'v3-premium',
                'starts_on' => '2023-09-19',
                'months' => '12',
            ],
            [
                'company_id' => '79',
                'company_name' => 'ABR Pharma Trading PLC',
                'plan_name' => 'v3-professional',
                'starts_on' => '2023-10-5',
                'months' => '12',
            ],
            [
                'company_id' => '80',
                'company_name' => 'Hanan Trading',
                'plan_name' => 'v3-standard',
                'starts_on' => '2023-10-24',
                'months' => '12',
            ],
            [
                'company_id' => '82',
                'company_name' => 'Izara Import',
                'plan_name' => 'v3-professional',
                'starts_on' => '2023-10-24',
                'months' => '12',
            ],
            [
                'company_id' => '83',
                'company_name' => 'ABIY TEFERI SAMUEL',
                'plan_name' => 'v3-professional',
                'starts_on' => '2023-10-26',
                'months' => '12',
            ],
        ];

        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                $company = Company::find($item['company_id']);

                if (is_null($company)) {
                    continue;
                }

                $subscription = $company->subscriptions()->create(Arr::only($item, ['starts_on', 'months']));

                $subscription->update([
                    'is_approved' => 1,
                    'plan_id' => $company->plan_id,
                ]);

                $subscription->company->update([
                    'subscription_expires_on' => $subscription->expiresOn,
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
