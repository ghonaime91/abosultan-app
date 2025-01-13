<?php
declare(strict_types=1);

return [

    "validation_error" => "خطأ في البيانات المدخلة",

    // 4xx Client Errors
    "verification_link_invalid" => "رابط التحقق غير صالح أو منتهي الصلاحية",
    "bad_request" => "طلب غير صحيح",
    "unauthenticated" => "يجب تسجيل الدخول أولاً",
    "unauthorized" => "غير مصرح بالطلب ",
    "forbidden" => "تم حظر الوصول",
    "not_found" => "المورد غير موجود",
    "method_not_allowed" => "خطأ في طريقةالطلب",
    "request_timeout" => "انتهت مهلة الطلب",
    "conflict" => "تعارض في الطلب",
    "gone" => "المورد غير موجود بعد الآن",
    "throttle" => "لقد وصلت للحد المسموح به من الطلبات",
    // 5xx Server Errors
    "internal_server_error" => "خطأ في الخادم",
    "not_implemented" => "الميزة غير مدعومة",
    "bad_gateway" => "بوابة غير صالحة",
    "service_unavailable" => "الخدمة غير متاحة",
    "gateway_timeout" => "انتهت مهلة البوابة",
    "http_version_not_supported" => "إصدار HTTP غير مدعوم",
];
