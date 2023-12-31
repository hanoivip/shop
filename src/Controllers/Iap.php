<?php
namespace Hanoivip\Shop\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Exception;
/**
 * IAP module migration
 * TODO: change domain in app client and remove this
 * 
 * @author GameOH
 *
 */
class Iap extends Controller
{
    public function getItems(Request $request)
    {
        $shop = $request->input('client');//= shop slug
        $items = [];
        $message = null;
        $error_message = null;
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            if ($this->shopBusiness->canOpen($userId, $shop))
            {
                $items = $this->shopBusiness->getShopItems($shop, null, null, null);
            }
            else
            {
                $error_message = __('hanoivip.shop::open.forbidden');
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 open shop exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::open.error');
        }
        return ['error' => empty($error_message) ? 0 : 1, 'message' => empty($error_message) ? $message : $error_message,
            'data' => ['items' => $items]];
    }
}