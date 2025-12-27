<?php

namespace App\Http\Controllers;

use App\Models\ServicePlan;
use Illuminate\Http\Request;

class ServicePlanController extends Controller
{
    public function index()
    {
        $servicePlans = ServicePlan::where('is_active', true)
            ->orderBy('price')
            ->get()
            ->groupBy('plan_type');

        return view('service-plans.index', compact('servicePlans'));
    }

    public function show(ServicePlan $servicePlan)
    {
        if (!$servicePlan->is_active) {
            abort(404);
        }

        return view('service-plans.show', compact('servicePlan'));
    }
}
