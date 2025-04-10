<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

if (!function_exists('refreshCaches')) {
	function refreshCaches()
	{
		try {
			Cache::forget('admin_products_all');
			Cache::forget('front_products_all');
		} catch (\Exception $e) {
			Log::error('Failed to refresh caches: ' . $e->getMessage());
		}
	}
}
