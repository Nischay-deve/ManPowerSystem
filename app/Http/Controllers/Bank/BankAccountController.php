<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $status = $request->get('status'); // unverified/verified/rejected
        $primary = $request->get('primary'); // 1/0

        $accounts = EmployeeBankAccount::query()
            ->with(['employee.designation', 'employee.department'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('bank_name', 'like', "%{$q}%")
                        ->orWhere('ifsc', 'like', "%{$q}%")
                        ->orWhere('branch', 'like', "%{$q}%")
                        ->orWhere('account_last4', 'like', "%{$q}%")
                        ->orWhereHas('employee', function ($e) use ($q) {
                            $e->where('employee_code', 'like', "%{$q}%")
                                ->orWhere('first_name', 'like', "%{$q}%")
                                ->orWhere('surname', 'like', "%{$q}%")
                                ->orWhere('mobile', 'like', "%{$q}%");
                        });
                });
            })
            ->when($status, fn($query) => $query->where('verification_status', $status))
            ->when($primary !== null && $primary !== '', fn($query) => $query->where('is_primary', (int)$primary))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('bank.index', compact('accounts', 'q', 'status', 'primary'));
    }

    public function create(Request $request)
    {
        $employeeId = $request->get('employee_id');

        $employees = Employee::query()
            ->orderByDesc('id')
            ->limit(200) // adjust if needed
            ->get(['id', 'employee_code', 'first_name', 'surname']);

        return view('bank.create', compact('employees', 'employeeId'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateBank($request);

        return DB::transaction(function () use ($validated) {

            // If primary, unset previous primary for this employee
            if (!empty($validated['is_primary'])) {
                EmployeeBankAccount::where('employee_id', $validated['employee_id'])->update(['is_primary' => 0]);
            }

            EmployeeBankAccount::create([
                'employee_id' => $validated['employee_id'],
                'account_number' => $validated['account_number'], // mutator encrypts + last4
                'account_holder_name' => $validated['account_holder_name'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'branch' => $validated['branch'] ?? null,
                'ifsc' => $validated['ifsc'] ?? null,
                'is_primary' => (int)($validated['is_primary'] ?? 0),
                'verification_status' => $validated['verification_status'] ?? 'unverified',
                'verification_notes' => $validated['verification_notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('bank.index')->with('success', 'Bank account created successfully.');
        });
    }

    public function edit(EmployeeBankAccount $bank)
    {
        $employees = Employee::query()
            ->orderByDesc('id')
            ->limit(200)
            ->get(['id', 'employee_code', 'first_name', 'surname']);

        return view('bank.edit', compact('bank', 'employees'));
    }

    public function update(Request $request, EmployeeBankAccount $bank)
    {
        $validated = $this->validateBank($request, true);

        return DB::transaction(function () use ($validated, $bank) {

            if (!empty($validated['is_primary'])) {
                EmployeeBankAccount::where('employee_id', $validated['employee_id'])->update(['is_primary' => 0]);
            }

            // account_number optional on update (if empty, keep existing)
            $payload = [
                'employee_id' => $validated['employee_id'],
                'account_holder_name' => $validated['account_holder_name'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'branch' => $validated['branch'] ?? null,
                'ifsc' => $validated['ifsc'] ?? null,
                'is_primary' => (int)($validated['is_primary'] ?? 0),
                'verification_status' => $validated['verification_status'] ?? 'unverified',
                'verification_notes' => $validated['verification_notes'] ?? null,
                'updated_by' => auth()->id(),
            ];

            if (!empty($validated['account_number'])) {
                $payload['account_number'] = $validated['account_number']; // will re-encrypt + update last4
            }

            $bank->update($payload);

            return redirect()->route('bank.index')->with('success', 'Bank account updated successfully.');
        });
    }

    public function destroy(EmployeeBankAccount $bank)
    {
        $bank->delete(); // hard delete (table has no deleted_at)
        return redirect()->route('bank.index')->with('success', 'Bank account deleted successfully.');
    }

    private function validateBank(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'account_number' => ($isUpdate ? 'nullable' : 'required') . '|string|min:6|max:30',
            'account_holder_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'ifsc' => 'nullable|string|max:20',
            'is_primary' => 'nullable|boolean',
            'verification_status' => 'nullable|in:unverified,verified,rejected',
            'verification_notes' => 'nullable|string|max:500',
        ]);
    }
}
