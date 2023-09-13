<?php
namespace App\Services;

use App\Models\RestaurantPhoto;
use Illuminate\Support\Str;

class RestaurantService
{
    public function __construct()
    {
    }

    // 上傳圖片
    public function uploadImage($restaurant_id, $image)
    {
        // 產生圖片名稱
        $imageName = $this->generateImageName($image->extension());
        // 將圖片存到 storage/app/public/images 資料夾下
        $image->storeAs('public/images', $imageName);

        // 將圖片路徑存到資料庫 並 關聯到餐廳
        $restaurantPhoto = new RestaurantPhoto;
        $restaurantPhoto->restaurant_id = $restaurant_id;
        $restaurantPhoto->url = '/storage/images/' . $imageName;
        $restaurantPhoto->save();
        return $imageName;
    }

    // 產生隨機圖片名稱 e.g. 12345678-1234-1234-1234-123456789012.jpg
    private function generateImageName($imageExtension = 'jpg'): string
    {
        return Str::uuid() . '.' . $imageExtension;
    }
}
