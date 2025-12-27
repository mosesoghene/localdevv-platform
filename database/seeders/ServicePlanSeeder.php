<?php

namespace Database\Seeders;

use App\Models\ServicePlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Priority Support Monthly',
                'slug' => 'priority-support-monthly',
                'description' => 'Get priority support with 4-hour SLA response time',
                'price' => 49.99,
                'billing_interval' => 'monthly',
                'plan_type' => 'priority_support',
                'features' => [
                    '4-hour SLA response time',
                    'Priority queue access',
                    'Email and chat support',
                    '10 support tickets per month',
                ],
                'limits' => [
                    'tickets_per_month' => 10,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Installation Service Monthly',
                'slug' => 'installation-service-monthly',
                'description' => 'Professional installation service for your products',
                'price' => 99.99,
                'billing_interval' => 'monthly',
                'plan_type' => 'installation_service',
                'features' => [
                    '3 installations per month',
                    '48-hour turnaround time',
                    'Configuration assistance',
                    'Post-installation support',
                ],
                'limits' => [
                    'installations_per_month' => 3,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Maintenance Annual',
                'slug' => 'maintenance-annual',
                'description' => 'Annual maintenance package with quarterly updates',
                'price' => 599.99,
                'billing_interval' => 'annual',
                'plan_type' => 'maintenance',
                'features' => [
                    'Quarterly software updates',
                    '5 maintenance requests per quarter',
                    'Bug fixes and patches',
                    'Performance optimization',
                ],
                'limits' => [
                    'maintenance_requests_per_month' => 5,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'VIP Support Package',
                'slug' => 'vip-support-package',
                'description' => 'Premium VIP support with all-inclusive benefits',
                'price' => 199.99,
                'billing_interval' => 'monthly',
                'plan_type' => 'vip_support',
                'features' => [
                    '1-hour SLA response time',
                    'Unlimited support tickets',
                    '5 installations per month',
                    'Priority development requests',
                    'Dedicated account manager',
                ],
                'limits' => [
                    'tickets_per_month' => 999,
                    'installations_per_month' => 5,
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            ServicePlan::create($plan);
        }
    }
}
