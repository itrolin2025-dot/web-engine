<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\CustomersWebsite;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransactionsController extends Controller
{
    protected $modul      = "transactions";
    protected $path       = "transaksi";
    protected $modul_name = "Transaksi";

    /**
     * Status transaksi yang tersimpan di DB (value) beserta label Indonesia
     * yang ditampilkan ke user.
     */
    public const STATUSES = [
        'Pending'    => 'Pending',
        'Paid'       => 'Validasi Pembayaran',
        'Shipped'    => 'Proses Pengiriman',
        'ShippedOut' => 'Dalam Pengiriman',
        'Completed'  => 'Barang Diterima',
        'Cancelled'  => 'Transaksi Dibatalkan',
    ];

    /**
     * Warna badge per status.
     */
    private function statusColor(string $status): string
    {
        return match ($status) {
            'Pending'    => 'bg-warning/10 text-warning',
            'Paid'       => 'bg-info/10 text-info',
            'Shipped'    => 'bg-primary/10 text-primary',
            'ShippedOut' => 'bg-secondary/10 text-secondary',
            'Completed'  => 'bg-success/10 text-success',
            'Cancelled'  => 'bg-danger/10 text-danger',
            default      => 'bg-slate-100 text-slate-500',
        };
    }

    protected function getRoleId()
    {
        return auth()->user()->role_id ?? 0;
    }

    /**
     * Generate an automatic transaction code: TRX-YYYYMMDD-NNNN (sequential per day).
     */
    private function generateCode(): string
    {
        $prefix = 'TRX-' . date('Ymd') . '-';

        $lastToday = Transaction::withTrashed()
            ->where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->value('code');

        $next = $lastToday ? ((int) substr($lastToday, strlen($prefix))) + 1 : 1;

        do {
            $code = $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
            $next++;
        } while (Transaction::withTrashed()->where('code', $code)->exists());

        return $code;
    }

    public function index()
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'view') == false) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.' . $this->path . '.index', [
            'canAdd'     => canAccess($this->modul, $role_id, 'add'),
            'canEdit'    => canAccess($this->modul, $role_id, 'edit'),
            'canDelete'  => canAccess($this->modul, $role_id, 'delete'),
            'canDetail'  => canAccess($this->modul, $role_id, 'detail'),
            'canRecycle' => canAccess($this->modul, $role_id, 'recycle'),
            'modul'      => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'List'
        ]);
    }

    public function recycle()
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'recycle') == false) {
            if (canAccess($this->modul, $role_id, 'view') == true) {
                return redirect()->route('admin.' . $this->modul)->with('warning', 'Tidak Memiliki Akses');
            }
            return redirect()->route('admin.dashboard');
        }

        return view('admin.' . $this->path . '.recycle', [
            'modul'      => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Recycle'
        ]);
    }

    public function create()
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'add') == false) {
            if (canAccess($this->modul, $role_id, 'view') == true) {
                return redirect()->route('admin.' . $this->modul)->with('warning', 'Tidak Memiliki Akses');
            }
            return redirect()->route('admin.dashboard');
        }

        $customers_websites = CustomersWebsite::orderBy('title', 'asc')->get();
        $autoCode = $this->generateCode();

        return view('admin.' . $this->path . '.create', [
            'customers_websites' => $customers_websites,
            'autoCode'           => $autoCode,
            'modul'              => $this->modul,
            'modul_path'         => $this->path,
            'modul_name'         => $this->modul_name,
            'modul_type'         => 'Create'
        ]);
    }

    /**
     * AJAX: customer information (name, address) for a selected customers_website.
     */
    public function getCustomerByWebsite($website_id)
    {
        $website = CustomersWebsite::with('customer')->find($website_id);
        if (!$website || !$website->customer) {
            return response()->json(null);
        }

        return response()->json([
            'name'    => $website->customer->name,
            'address' => $website->customer->address,
        ]);
    }

    /**
     * AJAX: products of a selected customers_website (for transaction detail items).
     */
    public function getProductsByWebsite($website_id)
    {
        $products = DB::table('products')
            ->where('customers_website_id', $website_id)
            ->whereNull('deleted_at')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'price']);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $this->validateTransaction($request);

        DB::beginTransaction();
        try {
            [$details, $subtotal] = $this->buildDetails($request);

            $transaction = Transaction::create([
                'code'                   => $this->generateCode(),
                'customers_website_id'   => $validated['customers_website_id'],
                'customer_name'          => $validated['customer_name'],
                'customer_address'       => $validated['customer_address'],
                'shipping_address'       => $validated['shipping_address'] ?? $validated['customer_address'],
                'shipping_courier'       => $validated['shipping_courier'] ?? null,
                'shipping_tracking_number' => $validated['shipping_tracking_number'] ?? null,
                'shipping_cost'          => $validated['shipping_cost'] ?? 0,
                'shipping_status'        => $validated['shipping_status'] ?? 'Pending',
                'subtotal'               => $subtotal,
                'total'                  => $subtotal + ($validated['shipping_cost'] ?? 0),
                'status'                 => $validated['status'] ?? 'Pending',
                'created_by'             => auth()->id(),
            ]);

            foreach ($details as $detail) {
                $detail['transaction_id'] = $transaction->id;
                TransactionDetail::create($detail);
            }

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'create',
                $transaction->id,
                ['name' => $transaction->code, 'code' => $transaction->code, 'total' => $transaction->total],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul)->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'edit') == false) {
            if (canAccess($this->modul, $role_id, 'view') == true) {
                return redirect()->route('admin.' . $this->modul)->with('warning', 'Tidak Memiliki Akses');
            }
            return redirect()->route('admin.dashboard');
        }

        $transaction = Transaction::with('details')->findOrFail($id);
        $customers_websites = CustomersWebsite::orderBy('title', 'asc')->get();
        $websiteProducts = DB::table('products')
            ->where('customers_website_id', $transaction->customers_website_id)
            ->whereNull('deleted_at')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'price']);

        return view('admin.' . $this->path . '.edit', [
            'transaction'        => $transaction,
            'customers_websites' => $customers_websites,
            'websiteProducts'    => $websiteProducts,
            'modul'              => $this->modul,
            'modul_path'         => $this->path,
            'modul_name'         => $this->modul_name,
            'modul_type'         => 'Edit',
        ]);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $this->validateTransaction($request);

        DB::beginTransaction();
        try {
            [$details, $subtotal] = $this->buildDetails($request);

            $transaction->update([
                'customers_website_id'     => $validated['customers_website_id'],
                'customer_name'            => $validated['customer_name'],
                'customer_address'         => $validated['customer_address'],
                'shipping_address'         => $validated['shipping_address'] ?? $validated['customer_address'],
                'shipping_courier'         => $validated['shipping_courier'] ?? null,
                'shipping_tracking_number' => $validated['shipping_tracking_number'] ?? null,
                'shipping_cost'            => $validated['shipping_cost'] ?? 0,
                'shipping_status'          => $validated['shipping_status'] ?? 'Pending',
                'subtotal'                 => $subtotal,
                'total'                    => $subtotal + ($validated['shipping_cost'] ?? 0),
                'status'                   => $validated['status'] ?? 'Pending',
            ]);

            // Replace details with the submitted ones
            TransactionDetail::where('transaction_id', $transaction->id)->delete();
            foreach ($details as $detail) {
                $detail['transaction_id'] = $transaction->id;
                TransactionDetail::create($detail);
            }

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'update',
                $transaction->id,
                ['name' => $transaction->code, 'code' => $transaction->code, 'total' => $transaction->total],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul)->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal update transaksi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        ActivityLogger::log(
            $this->modul,
            'delete',
            $transaction->id,
            ['name' => $transaction->code, 'code' => $transaction->code],
            auth()->id()
        );

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction has been deleted successfully.'
        ]);
    }

    public function restore($id)
    {
        $data = Transaction::onlyTrashed()->findOrFail($id);
        $data->restore();

        ActivityLogger::log(
            $this->modul,
            'restore',
            $data->id,
            ['name' => $data->code, 'code' => $data->code],
            auth()->id()
        );

        return response()->json([
            'status' => true,
            'message' => 'Transaction has been restored successfully.'
        ]);
    }

    public function getData(Request $request)
    {
        $role_id = $this->getRoleId();
        $query = DB::table('transactions')
            ->leftJoin('customers_website', 'customers_website.id', '=', 'transactions.customers_website_id')
            ->select([
                'transactions.*',
                'customers_website.title as website_title',
            ])
            ->whereNull('transactions.deleted_at');

        if ($request->filled('filter_website')) {
            $query->where('transactions.customers_website_id', $request->filter_website);
        }
        if ($request->filled('filter_status')) {
            $query->where('transactions.status', $request->filter_status);
        }

        $statuses = self::STATUSES;

        $data = $query->orderBy('transactions.id', 'desc')->get();

        return DataTables::of($data)
            ->addColumn('mobile_view', function ($row) {
                $shippingInfo = trim(($row->shipping_courier ?? '-') . ($row->shipping_tracking_number ? ' • ' . $row->shipping_tracking_number : ''));

                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between" style="padding:15px;">
                        <div class="flex items-center flex-1 min-w-0">
                            <div class="h-10 w-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <div class="fw-bold truncate">' . e($row->code) . '</div>
                                <span class="text-xs text-slate-400">' . e($row->website_title) . '</span>
                            </div>
                        </div>
                        <a class="toggle-expand btn btn-xs btn-secondary ml-2">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <div class="mobile-meta" style="padding: 10px 0;">
                            <span class="flex items-center mb-2"><i class="fa-solid fa-user mr-2"></i><span class="fw-bold" style="margin-left:8px;">' . e($row->customer_name) . '</span></span>
                            <span class="flex items-center mb-2"><i class="fa-solid fa-truck-fast mr-2"></i><span class="fw-bold" style="margin-left:8px;">' . e($shippingInfo) . '</span></span>
                            <span class="flex items-center mb-2"><i class="fa-solid fa-money-bill-wave mr-2"></i><span class="fw-bold" style="margin-left:8px;">Rp ' . number_format((float) $row->total, 0, ',', '.') . '</span></span>
                            <br>
                            <div class="action-mobile" style="margin-top:10px;">
                                ' . view('components.datatables.button-edit', ['id' => $row->id, 'modul' => $this->modul])->render() . '
                                ' . view('components.datatables.button-delete', ['id' => $row->id, 'name' => $row->code])->render() . '
                            </div>
                        </div>
                    </div>
                </div>
                ';
            })
            ->addColumn('status_view', function ($row) use ($statuses) {
                $color = $this->statusColor($row->status);
                $label = $statuses[$row->status] ?? $row->status;

                // Badge clickable -> popup pilih status
                return '<button type="button" data-id="' . $row->id . '" data-code="' . e($row->code) . '" data-status="' . e($row->status) . "\" class=\"js-status-toggle badge rounded-full " . $color
                    . ' px-2.5 py-1 text-xs font-medium cursor-pointer hover:opacity-80 transition-opacity" title="Klik untuk ubah status">'
                    . e($label)
                    . ' <i class="fa-solid fa-chevron-down text-[9px] ml-0.5"></i>'
                    . '</button>';
            })
            ->addColumn('total_view', function ($row) {
                return '<span class="fw-semibold">Rp ' . number_format((float) $row->total, 0, ',', '.') . '</span>';
            })
            ->addColumn('action', function ($row) use ($role_id) {
                $btn = "";
                if (canAccess($this->modul, $role_id, 'edit')) {
                    $btn .= view('components.datatables.button-edit', ['id' => $row->id, 'modul' => $this->modul])->render();
                }
                if (canAccess($this->modul, $role_id, 'delete')) {
                    $btn .= view('components.datatables.button-delete', ['id' => $row->id, 'name' => $row->code])->render();
                }
                return $btn;
            })
            ->rawColumns(['action', 'mobile_view', 'status_view', 'total_view'])
            ->make(true);
    }

    public function getDataRecycle(Request $request)
    {
        $query = DB::table('transactions')
            ->leftJoin('customers_website', 'customers_website.id', '=', 'transactions.customers_website_id')
            ->select([
                'transactions.*',
                'customers_website.title as website_title',
            ])
            ->whereNotNull('transactions.deleted_at');

        $data = $query->orderBy('transactions.id', 'desc')->get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                return view('components.datatables.button-restore', [
                    'id'   => $row->id,
                    'name' => $row->code,
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Quick status update dari popup di tabel (klik badge status).
     */
    public function updateStatus(Request $request, $id)
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'edit') == false) {
            return response()->json(['success' => false, 'message' => 'Tidak Memiliki Akses'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(self::STATUSES)),
        ]);

        $transaction = Transaction::findOrFail($id);
        $oldStatus = $transaction->status;
        $transaction->update(['status' => $validated['status']]);

        ActivityLogger::log(
            $this->modul,
            'update_status',
            $transaction->id,
            ['name' => $transaction->code, 'code' => $transaction->code, 'old' => $oldStatus, 'new' => $validated['status']],
            auth()->id()
        );

        $labels = self::STATUSES;

        return response()->json([
            'success' => true,
            'message' => 'Status transaksi ' . $transaction->code . ' diubah menjadi "' . ($labels[$validated['status']] ?? $validated['status']) . '".',
            'status'  => $validated['status'],
        ]);
    }

    /**
     * Shared validation for store & update.
     */
    private function validateTransaction(Request $request): array
    {
        return $request->validate([
            'customers_website_id'     => 'required|integer|exists:customers_website,id',
            'customer_name'            => 'required|string|max:255',
            'customer_address'         => 'required|string',
            'shipping_address'         => 'nullable|string',
            'shipping_courier'         => 'nullable|string|max:100',
            'shipping_tracking_number' => 'nullable|string|max:100',
            'shipping_cost'            => 'nullable|numeric|min:0',
            'shipping_status'          => 'nullable|string|in:Pending,Packing,Shipped,Delivered',
            'status'                   => 'nullable|string|in:' . implode(',', array_keys(self::STATUSES)),

            // Detail items (parallel arrays from the repeater)
            'product_id'  => 'required|array|min:1',
            'product_id.*' => 'nullable|integer|exists:products,id',
            'product_name' => 'required|array',
            'product_name.*' => 'required|string|max:255',
            'qty'         => 'required|array',
            'qty.*'       => 'required|integer|min:1',
            'price'       => 'required|array',
            'price.*'     => 'required|numeric|min:0',
        ]);
    }

    /**
     * Build the detail rows (with subtotal snapshot) from the parallel arrays.
     *
     * @return array [array $details, float $subtotal]
     */
    private function buildDetails(Request $request): array
    {
        $details = [];
        $subtotal = 0;

        foreach ($request->input('product_id', []) as $i => $productId) {
            $name  = $request->input("product_name.{$i}");
            $qty   = (int) $request->input("qty.{$i}", 1);
            $price = (float) $request->input("price.{$i}", 0);

            if (!$name || $qty < 1) {
                continue;
            }

            $rowSubtotal = $qty * $price;
            $subtotal += $rowSubtotal;

            $details[] = [
                'product_id' => $productId ?: null,
                'product_name' => $name,
                'price'      => $price,
                'qty'        => $qty,
                'subtotal'   => $rowSubtotal,
            ];
        }

        if (empty($details)) {
            throw new \Exception('Minimal satu produk harus ditambahkan pada detail transaksi.');
        }

        return [$details, $subtotal];
    }
}
