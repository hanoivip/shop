<?php
namespace Hanoivip\Shop\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Hanoivip\Shop\Services\IShopData;
use Hanoivip\Shop\Services\ReceiptService;
use Hanoivip\Shop\Services\ShopService;
use Hanoivip\Shop\Services\OrderService;
use Hanoivip\Shop\Jobs\SendShopOrderJob;
use Hanoivip\Shop\Jobs\CheckPendingReceipt;
use Illuminate\Support\Facades\Notification;
use Hanoivip\User\Facades\UserFacade;
use Hanoivip\Shop\Models\ShopOrder;
use Hanoivip\Shop\Notifications\NewOrder;
use Exception;

class Admin extends Controller
{
    private $shopData;
    
    private $shopBusiness;
    
    private $orderService;
    
    protected $receiptBusiness;
    
    public function __construct(
        IShopData $shopData,
        ShopService $shopBusiness,
        OrderService $orderService,
        ReceiptService $receiptService)
    {
        $this->shopData = $shopData;
        $this->shopBusiness = $shopBusiness;
        $this->orderService = $orderService;
        $this->receiptBusiness = $receiptService;
    }
    
    public function listShop(Request $request)
    {
        try
        {
            $list = $this->shopData->allShop();
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin list shop exception: " . $ex->getMessage());
        }
        return view('hanoivip::admin.shopv2-list', [
            'shops' => $list
        ]);
    }
    
    public function viewShop(Request $request)
    {
        $slug = $request->input('slug');//slug
        $items = [];
        $message = null;
        $error_message = null;
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            $items = $this->shopData->getShopItems($slug);
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin open shop exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::open.error');
        }
        return view('hanoivip::admin.shopv2-view', [
            'slug' => $slug,
            'items' => $items,
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    // list order by user
    public function listOrder(Request $request)
    {
        $message = null;
        $error_message = null;
        $page = 0;
        $records = [];
        $tid = $request->input('tid');
        $order = null;
        if ($request->has('order')) {
            $order = $request->input('order');
        }
        try
        {
            // $records = $this->orderService->list($tid, $page);
            $query = ShopOrder::where('user_id', $tid)
            ->orderBy('id', 'desc');
            if (!empty($order)) {
                $query = $query->where('serial', $order);
            }
            $records = $query->paginate(10, ['*'], 'page', $page);
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin list order exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::order.list.error');
        }
        return view('hanoivip::admin.shopv2-order-list', [
            'tid' => $tid,
            'orders' => $records,
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
    
    public function findOrder(Request $request)
    {
        $message = null;
        $error_message = null;
        $records = null;
        try 
        {
            $query = DB::table("shop_orders");
            if ($request->getMethod() == 'POST')
            {
                $order = $request->input('order');
                $query = $query->where('serial', $order);
            }
            $records = $query->orderBy('id', 'desc')->paginate(15);
        } catch (Exception $ex) {
            Log::error("ShopV2 find order exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::order.find.error');
        }
        return view('hanoivip::admin.shopv2-order-find', [
            'orders' => $records,
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
    
    public function viewOrder(Request $request)
    {
        $message = null;
        $error_message = null;
        $orderRec = null;
        try
        {
            $order = $request->input('order');
            $orderRec = $this->orderService->detail($order);
            if (empty($orderRec))
            {
                $error_message = __('hanoivip.shop::order.invalid');
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin list shop exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::order.view.error');
        }
        return view('hanoivip::admin.shopv2-order-view', [
            'message' => $message,
            'error_message' => $error_message,
            'order' => $orderRec
        ]);
    }
    /**
     * Admin manually check order payment and finish it, if ok
     * Reason:
     * Pay callback may be lost?
     * @param Request $request
     */
    public function checkOrder(Request $request)
    {
        $order = $request->input('order');
        $receipt = $request->input('receipt');
        $message = null;
        $error_message = null;
        $notice_message = null;
        try
        {
            $orderDetail = $this->orderService->detail($order);
            $userId = $orderDetail->user_id;
            $result = $this->receiptBusiness->check($userId, $order, $receipt);
            if (gettype($result) === 'boolean')
            {
                if ($result === true)
                {
                    $message = __('hanoivip.shop::pay.success-done');
                }
                else
                {
                    $error_message = __('hanoivip.shop::pay.failure-done');
                }
            }
            else
            {
                /** @var \Hanoivip\PaymentMethodContract\IPaymentResult $result */
                if ($result->isFailure())
                {
                    $error_message = __('hanoivip.shop::pay.failure');
                }
                else if ($result->isPending())
                {
                    $notice_message = __('hanoivip.shop::pay.pending');
                    dispatch(new CheckPendingReceipt($userId, $order, $receipt));
                }
                else if ($result->isSuccess())
                {
                    $this->orderService->onPayDone($order, $receipt);
                    $message = __('hanoivip.shop::pay.success');
                }
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 pay callback exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::pay.error');
        }
        return view('hanoivip::admin.result', [
            'message' => $message,
            'notice_message' => $notice_message,
            'error_message' => $error_message,
        ]);
    }
    /**
     * Admin manually send items without checking payment status
     * NOTICE: dangerous, repeated sent
     * 
     * TODO: need to check order payment and send items
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function finishOrder(Request $request)
    {
        $message = null;
        $error_message = null;
        try
        {
            $order = $request->input('order');
            $orderRec = $this->orderService->detail($order);
            if (empty($orderRec))
            {
                $error_message = __('hanoivip.shop::order.invalid');
            }
            else if ($orderRec->delivery_status == OrderService::UNSENT)
            {
                $orderRec->delivery_status = OrderService::SENDING;
                $orderRec->save();
                dispatch(new SendShopOrderJob($order, "AdminFlow"));
                $message = "success";
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin finish order exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::order.finish.exception');
        }
        return view('hanoivip::admin.result', [
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
    
    public function newShop(Request $request)
    {
        try
        {
            if ($request->getMethod() == 'POST')
            {
                $message = null;
                $error_message = null;
                $result = $this->shopBusiness->newShop($request->all());
                if ($result)
                {
                    $message = "success";
                }
                else
                {
                    $error_message = "failure";
                }
                return view('hanoivip::admin.result', [
                    'message' => $message,
                    'error_message' => $error_message,
                ]);
            }
            else 
            {
                return view('hanoivip::admin.shopv2-new');
            }
        }
        catch (Exception $ex)
        {
        }
    }
    
    public function delShop(Request $request)
    {
        try
        {
            
        }
        catch (Exception $ex)
        {
        }
    }
    
    public function emailOrder(Request $request)
    {
        $message = null;
        $error_message = null;
        try
        {
            $order = $request->input('order');
            if ($this->orderService->isValid($order))
            {
                $record = $this->orderService->detail($order);
                $user = UserFacade::getUserCredentials($record->user_id);
                Notification::send($user, new NewOrder($record->serial, $record->cart));
                $message = "success";
            }
            else
            {
                $error_message = "invalid order";
            }
        }
        catch (Exception $ex)
        {
        }
        return view('hanoivip::admin.result', [
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
    
    public function newItem(Request $request)
    {
        $slug = $request->input('slug');
        try
        {
            if ($request->getMethod() == 'POST')
            {
                $message = null;
                $error_message = null;
                $result = $this->shopBusiness->newShopItem($slug, $request->all());
                if ($result)
                {
                    $message = "success";
                }
                else
                {
                    $error_message = "failure";
                }
                return view('hanoivip::admin.result', [
                    'message' => $message,
                    'error_message' => $error_message,
                ]);
            }
            else
            {
                return view('hanoivip::admin.shopv2-new-item', ['slug' => $slug]);
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin list shop exception: " . $ex->getMessage());
        }
    }
    
    public function removeItem(Request $request)
    {
        $slug = $request->input('slug');
        $code = $request->input('code');
        $message = null;
        $error_message = null;
        try
        {
            $result = $this->shopBusiness->removeShopItem($slug, $code);
            if ($result)
            {
                $message = "success";
            }
            else
            {
                $error_message = "failure";
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin remove shop item exception: " . $ex->getMessage());
        }
        return view('hanoivip::admin.result', [
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
}