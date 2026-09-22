<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{

    public function index($client = null, $pages = null)
    {

        // Join customers_websites_layout → customers_website → customers
        // filtered by domain "elska"
        $website = DB::table('customers_website')
            ->join('customers', 'customers.id', '=', 'customers_website.customer_id')
            ->where('customers_website.domain', $client)
            ->select(
                'customers_website.*',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers_website.customer_type as cust_type'
            )
            ->first();

        $title = $website->title ?? 'Your Brand Page';
        $customerName = $website->customer_name ?? 'Your Brand';
        $customerType = $website->cust_type ?? 'Free Edition';
        
        // Get layout sections for this website, ordered by position
        $layouts = collect();
        if ($website) {
            $layouts = DB::table('customers_websites_layout')
                ->join('templates_section', 'templates_section.id', '=', 'customers_websites_layout.templates_section_id')
                ->join('template', 'template.id', '=', 'templates_section.template_id')
                ->where('customers_websites_layout.customers_website_id', $website->id)
                ->where('customers_websites_layout.status', true)
                ->where('customers_websites_layout.page_type', $pages)
                ->orderBy('customers_websites_layout.position')
                ->select(
                    'customers_websites_layout.*',
                    'templates_section.name as section_name',
                    'templates_section.slug as section_slug',
                    'template.path as template_path'
                )
                ->get();

            $categories = DB::table('category_products')
                ->where('customers_website_id', $website->id)
                ->whereNull('deleted_at')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('products')
                        ->whereColumn('products.category_products_id', 'category_products.id')
                        ->whereNull('products.deleted_at');
                })
                ->get();

            $products = DB::table('products')
                ->where('customers_website_id', $website->id)
                ->whereNull('deleted_at')
                ->get();

            $article_categories = DB::table('article_categories')
                ->where('customers_website_id', $website->id)
                ->whereNull('deleted_at')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('articles')
                        ->whereColumn('articles.article_categories_id', 'article_categories.id')
                        ->whereNull('articles.deleted_at');
                })
                ->get();

            $articles = DB::table('articles')
                ->join('article_categories', 'article_categories.id', '=', 'articles.article_categories_id')
                ->select(
                    'articles.*',
                    'article_categories.name as article_category'
                )
                ->where('articles.customers_website_id', $website->id)
                ->whereNull('articles.deleted_at')
                ->get();

        } else {
            $categories = collect();
            $products = collect();
            $article_categories = collect();
            $articles = collect();
        }

        $navbarPresets = \App\Http\Controllers\FrontController::getNavbarPresets($categories, $customerType);
        $footerPresets = \App\Http\Controllers\FrontController::getFooterPresets();

        return view('template.index', compact('title', 'website', 'customerName', 'layouts', 'categories', 'products', 'article_categories', 'articles', 'navbarPresets', 'footerPresets'));
    }

    /**
     * Checkout page: /{client}/checkout
     * Renders template.pages.checkout with the customer website context so the
     * shared cart (cookie) from template.shop.cart can be checked out there.
     */
    public function checkout($client = null)
    {
        $website = DB::table('customers_website')
            ->join('customers', 'customers.id', '=', 'customers_website.customer_id')
            ->where('customers_website.domain', $client)
            ->select(
                'customers_website.*',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers_website.customer_type as cust_type'
            )
            ->first();

        if (!$website) {
            abort(404);
        }

        $title = $website->title ?? 'Checkout';
        $customerName = $website->customer_name ?? null;
        $customerType = $website->cust_type ?? 'Free Edition';

        // Fallback: gunakan layout homepage jika page_type 'checkout' belum ada.
        $pageType = 'checkout';
        $hasCheckoutLayout = DB::table('customers_websites_layout')
            ->where('customers_website_id', $website->id)
            ->where('status', true)
            ->where('page_type', 'checkout')
            ->exists();
        if (!$hasCheckoutLayout) {
            $pageType = 'homepage';
        }

        $layouts = DB::table('customers_websites_layout')
            ->join('templates_section', 'templates_section.id', '=', 'customers_websites_layout.templates_section_id')
            ->join('template', 'template.id', '=', 'templates_section.template_id')
            ->where('customers_websites_layout.customers_website_id', $website->id)
            ->where('customers_websites_layout.status', true)
            ->where('customers_websites_layout.page_type', $pageType)
            ->orderBy('customers_websites_layout.position')
            ->select(
                'customers_websites_layout.*',
                'templates_section.name as section_name',
                'templates_section.slug as section_slug',
                'template.path as template_path'
            )
            ->get();

        $categories = DB::table('category_products')
            ->where('customers_website_id', $website->id)
            ->whereNull('deleted_at')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('products')
                    ->whereColumn('products.category_products_id', 'category_products.id')
                    ->whereNull('products.deleted_at');
            })
            ->get();

        $products = DB::table('products')
            ->where('customers_website_id', $website->id)
            ->whereNull('deleted_at')
            ->get();

        $article_categories = collect();
        $articles = collect();

        $navbarPresets = \App\Http\Controllers\FrontController::getNavbarPresets($categories, $customerType);
        $footerPresets = \App\Http\Controllers\FrontController::getFooterPresets();

        return view('template.pages.checkout', compact(
            'title', 'website', 'customerName', 'layouts', 'categories', 'products',
            'article_categories', 'articles', 'navbarPresets', 'footerPresets'
        ));
    }

    /**
     * Storefront checkout: place an order from the checked cart items.
     *
     * Creates a `transactions` row (with auto code TRX-YYYYMMDD-NNNN) plus its
     * `transaction_details` rows (product snapshot: name, price, qty, subtotal),
     * so the order appears directly in the admin Transaksi module.
     */
    public function placeOrder(Request $request, $client = null)
    {
        $website = DB::table('customers_website')
            ->where('domain', $client)
            ->first();

        if (!$website) {
            return response()->json(['success' => false, 'message' => 'Store not found.'], 404);
        }

        if ($request->has('items') && is_array($request->input('items'))) {
            $normalized = [];
            foreach ($request->input('items') as $item) {
                // Some form encodings (e.g. items[][name]) split one product into
                // several partial elements — merge any element that is missing 'name'.
                if (!isset($item['name'])) {
                    $idx = count($normalized) - 1;
                    if ($idx >= 0 && isset($normalized[$idx]['name']) && (!isset($normalized[$idx]['qty']) || !isset($normalized[$idx]['price']))) {
                        $normalized[$idx] = array_merge($normalized[$idx], $item);
                        continue;
                    }
                }
                $normalized[] = $item;
            }
            $request->merge(['items' => array_values($normalized)]);
        }

        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'nullable|string|max:50',
            'customer_email'   => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'courier'          => 'nullable|string|max:100',
            'payment_method'   => 'nullable|string|max:100',

            // Checked items passed from the cart drawer / checkout bag
            'items'            => 'required|array|min:1',
            'items.*.name'     => 'required|string|max:255',
            'items.*.qty'      => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $details = [];
            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                $qty   = (int) $item['qty'];
                $price = (float) $item['price'];
                $rowSubtotal = $qty * $price;

                // Resolve the product of this website by name (snapshot is kept even if not found)
                $product = DB::table('products')
                    ->where('customers_website_id', $website->id)
                    ->where('name', $item['name'])
                    ->whereNull('deleted_at')
                    ->first();

                $details[] = [
                    'product_id'   => $product->id ?? null,
                    'product_name' => $item['name'],
                    'price'        => $price,
                    'qty'          => $qty,
                    'subtotal'     => $rowSubtotal,
                ];
                $subtotal += $rowSubtotal;
            }

            if (empty($details)) {
                throw new \Exception('No items to order.');
            }

            // Shipping cost is decided on the server (matches the checkout UI options):
            // Standard = free, Express = Rp 50.000.
            $shippingCost = 0;
            if (!empty($validated['courier']) && stripos($validated['courier'], 'express') !== false) {
                $shippingCost = 50000;
            }

            $transaction = \App\Models\Transaction::create([
                'code'                     => \App\Models\Transaction::generateCode(),
                'customers_website_id'     => $website->id,
                'customer_name'            => $validated['customer_name'],
                'customer_phone'           => $validated['customer_phone'] ?? null,
                'customer_email'           => $validated['customer_email'] ?? null,
                'customer_address'         => $validated['shipping_address'],
                'shipping_address'         => $validated['shipping_address'],
                'shipping_courier'         => $validated['courier'] ?? null,
                'shipping_cost'            => $shippingCost,
                'shipping_status'          => 'Pending',
                'payment_method'           => $validated['payment_method'] ?? null,
                'subtotal'                 => $subtotal,
                'total'                    => $subtotal + $shippingCost,
                'status'                   => 'Pending',
            ]);

            foreach ($details as $detail) {
                $detail['transaction_id'] = $transaction->id;
                DB::table('transaction_details')->insert($detail + ['created_at' => now(), 'updated_at' => now()]);
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Order placed successfully.',
                'code'     => $transaction->code,
                'total'    => (float) $transaction->total,
                'redirect' => route('pages.checkout.success', ['client' => $client, 'code' => $transaction->code]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to place order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Order success page: /{client}/checkout/success?code=TRX-...
     */
    public function orderSuccess(Request $request, $client = null)
    {
        $website = DB::table('customers_website')
            ->where('domain', $client)
            ->first();

        if (!$website) {
            abort(404);
        }

        $code = $request->query('code');
        $transaction = null;

        if ($code) {
            $transaction = \App\Models\Transaction::with('details')
                ->where('code', $code)
                ->where('customers_website_id', $website->id)
                ->first();
        }

        return view('template.pages.checkout-success', compact('website', 'transaction', 'code'));
    }
}


