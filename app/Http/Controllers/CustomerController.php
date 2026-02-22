<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Customer::withCount('transactions')->withSum('transactions', 'total_amount');

        if ($search) {
            $query->search($search);
        }

        $customers = $query->orderBy('name')->paginate(15)->withQueryString();
        $totalCustomers = Customer::count();
        $totalSpent = Customer::sum('total_spent');
        $totalPoints = Customer::sum('points');

        return view('customers.index', compact('customers', 'search', 'totalCustomers', 'totalSpent', 'totalPoints'));
    }

    public function create()
    {
        return redirect()->route('customers.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:customers,phone',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'nullable|string|max:500',
        ]);

        $customer = Customer::create($validated);
        ActivityLog::log('created', "Pelanggan '{$customer->name}' berhasil ditambahkan", $customer);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'customer' => $customer], 201);
        }

        Alert::success('Berhasil', 'Pelanggan berhasil ditambahkan.');
        return redirect()->route('customers.index');
    }

    public function show(Customer $customer)
    {
        $customer->load(['transactions.paymentMethod', 'transactions.details.product']);
        $totalTransactions = $customer->transactions->count();
        $totalSpent = $customer->transactions->sum('total_amount');
        $recentTransactions = $customer->transactions()->latest()->take(10)->get();

        return view('customers.show', compact('customer', 'totalTransactions', 'totalSpent', 'recentTransactions'));
    }

    public function edit(Customer $customer)
    {
        if (request()->wantsJson()) {
            return response()->json($customer);
        }
        return redirect()->route('customers.index');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string|max:500',
        ]);

        $old = $customer->toArray();
        $customer->update($validated);
        ActivityLog::log('updated', "Data pelanggan '{$customer->name}' diubah", $customer, $old, $customer->toArray());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'customer' => $customer->fresh()]);
        }

        Alert::success('Berhasil', 'Data pelanggan berhasil diperbarui.');
        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        ActivityLog::log('deleted', "Pelanggan '{$customer->name}' dihapus", $customer);
        $customer->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        Alert::success('Berhasil', 'Pelanggan berhasil dihapus.');
        return redirect()->route('customers.index');
    }

    public function search(Request $request)
    {
        $term = $request->input('q', '');
        $customers = Customer::search($term)->select('id', 'name', 'phone', 'points', 'total_spent')->limit(10)->get();
        return response()->json($customers);
    }
}
