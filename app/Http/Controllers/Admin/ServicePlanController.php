<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicePlan;
use Illuminate\Http\Request;

class ServicePlanController extends Controller
{
    public function index()
    {
        $servicePlans = ServicePlan::latest()->paginate(15);
        return view('admin.service-plans.index', compact('servicePlans'));
    }

    public function create()
    {
        return view('admin.service-plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:service_plans',
            'description' => 'required|string',
            'plan_type' => 'required|in:priority_support,installation_service,maintenance_plan,vip_support',
            'price' => 'required|numeric|min:0',
            'billing_interval' => 'required|in:monthly,quarterly,annually',
            'features' => 'nullable|array',
            'limits' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        ServicePlan::create($validated);

        return redirect()->route('admin.service-plans.index')
            ->with('success', 'Service plan created successfully!');
    }

    public function edit(ServicePlan $servicePlan)
    {
        return view('admin.service-plans.edit', compact('servicePlan'));
    }

    public function update(Request $request, ServicePlan $servicePlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:service_plans,slug,' . $servicePlan->id,
            'description' => 'required|string',
            'plan_type' => 'required|in:priority_support,installation_service,maintenance_plan,vip_support',
            'price' => 'required|numeric|min:0',
            'billing_interval' => 'required|in:monthly,quarterly,annually',
            'features' => 'nullable|array',
            'limits' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $servicePlan->update($validated);

        return redirect()->route('admin.service-plans.index')
            ->with('success', 'Service plan updated successfully!');
    }

    public function destroy(ServicePlan $servicePlan)
    {
        $servicePlan->delete();

        return redirect()->route('admin.service-plans.index')
            ->with('success', 'Service plan deleted successfully!');
    }
}
