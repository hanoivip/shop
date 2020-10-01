<?php
namespace Hanoivip\Shop\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Hanoivip\Shop\Services\ShopService;

class ShopController extends Controller
{

    protected $shopBusiness;

    public function __construct(ShopService $shopBusiness)
    {
        $this->shopBusiness = $shopBusiness;
    }

    /**
     * List all player's shop
     * And shop items
     *
     * @param Request $request
     */
    public function list(Request $request)
    {
        $uid = Auth::user()->getAuthIdentifier();
        $shops = $this->shopBusiness->filterUserShops($uid);
        $shop = $this->shopBusiness->getDefaultShop();
        if ($request->has('shop')) // shop id
            $shop = $request->input('shop');
        $shopItems = [];
        if (! empty($shop)) {
            $shopItems = $this->shopBusiness->getShopItems($shop);
        }
        return view('hanoivip::shop-list', [
            'shops' => $shops,
            'current' => $shop, // current shop
            'shop_items' => $shopItems
        ]);
    }

    /**
     * Server role helpers
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function roleHelper(Request $request)
    {
        $shop = $request->input('shop');
        $item = $request->input('item');
        $count = $request->input('count');
        session()->put('buying', ['shop' => $shop, 'item' => $item, 'count' => $count]);
        // open role wizard
        return redirect()->route('wizard.role', [
            'next' => 'shop.confirm'
        ]);
    }

    public function confirm(Request $request)
    {
        if (!session()->has('buying'))
        {
            return view('hanoivip::shop-item-confirm-error');
        }
        $server = $request->input('svname');
        $role = $request->input('role');
        $params = session()->get('buying');
        $shop = $params['shop'];
        $item = $params['item'];
        $count = $params['count'];
        Log::debug(print_r($params, true));
        // item detail
        $itemDetail = $this->shopBusiness->getShopItems($shop, $item);
        Log::debug($item . ':' . print_r($itemDetail, true));
        // caculate final price
        $price = $this->shopBusiness->caculatePrice($shop, $itemDetail, $count);
        // include sale..
        return view('hanoivip::shop-item-confirm', [
            'server' => $server,
            'role' => $role,
            'shop' => $shop,
            'item_detail' => $itemDetail,
            'count' => $count,
            'price' => $price
        ]);
    }

    public function order(Request $request)
    {
        $server = $request->input('server');
        $role = $request->input('role');
        $shop = $request->input('shop');
        $item = $request->input('item');
        $count = $request->input('count');
        $receiver = Auth::user()->getAuthIdentifier();
        // create order
        $order = $this->shopBusiness->order($receiver, $server, $role, $shop, $item, $count);
        // redirect to pay order
        if ($order !== false) {
            return redirect()->route('shop.pay', [
                'order' => $order->serial
            ]);
        } else {
            return view('hanoivip::shop-order-fail');
        }
    }

    /**
     * User pay for order
     *
     * @param Request $request
     */
    public function pay(Request $request)
    {
        $serial = $request->input('order');
        $payer = Auth::user()->getAuthIdentifier();
        $result = $this->shopBusiness->pay($payer, $serial);
        if ($result === true) {
            return redirect()->route('shop.success');
        } else {
            return view('hanoivip::shop-pay-fail');
        }
    }

    /**
     * User paid, open success page
     *
     * @param Request $request
     */
    public function paySuccess(Request $request)
    {
        return view('hanoivip::shop-pay-success');
    }

    public function listOrder(Request $request)
    {
        $uid = Auth::user()->getAuthIdentifier();
        $orders = $this->shopBusiness->listOrder($uid);
        return view('hanoivip::shop-order-list', [
            'orders' => $orders
        ]);
    }
}