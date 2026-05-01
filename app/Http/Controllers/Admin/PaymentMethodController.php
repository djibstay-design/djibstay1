<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->get();
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment_methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code_marchand' => 'nullable|string|max:255',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['nom', 'description', 'code_marchand']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo_file')) {
            $data['logo'] = $request->file('logo_file')->store('payment_methods', 'public');
        }

        PaymentMethod::create($data);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Type de paiement ajouté.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code_marchand' => 'nullable|string|max:255',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['nom', 'description', 'code_marchand']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo_file')) {
            if ($paymentMethod->logo) {
                Storage::disk('public')->delete($paymentMethod->logo);
            }
            $data['logo'] = $request->file('logo_file')->store('payment_methods', 'public');
        }

        $paymentMethod->update($data);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Type de paiement mis à jour.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->logo) {
            Storage::disk('public')->delete($paymentMethod->logo);
        }
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')->with('success', 'Type de paiement supprimé.');
    }

    public function toggleActive(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);
        return back()->with('success', 'Statut mis à jour.');
    }
}
