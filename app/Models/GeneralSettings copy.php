<?php

namespace App\Models;

use App\ClearResponseCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Joaopaulolndev\FilamentGeneralSettings\Models\GeneralSetting;

class GeneralSettings extends Model
{
    // use ClearResponseCache;
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_description',
        'theme_color',
        'support_email',
        'support_phone',
        'google_analytics_id',
        'posthog_html_snippet',
        'seo_title',
        'seo_keywords',
        'seo_metadata',
        'social_network',
        'email_settings',
        'email_from_name',
        'email_from_address',
        'more_configs',
        'school_portal_enabled',
        'online_enrollment_enabled',
        'school_portal_maintenance',
        'school_portal_url',
        'school_starting_date',
        'school_ending_date',
        'semester',
        'enrollment_courses',
        'school_portal_logo',
        'school_portal_favicon',
        'school_portal_title',
        'school_portal_description',
        'enable_clearance_check',
        'enable_signatures',
        'enable_public_transactions',
        'enable_qr_codes',
        'enable_support_page',
        'features',
        'curriculum_year',
    ];

    protected $casts = [
        'seo_metadata' => 'array',
        'email_settings' => 'array',
        'social_network' => 'array',
        'more_configs' => 'array',
        'school_starting_date' => 'date',
        'school_ending_date' => 'date',
        'school_portal_enabled' => 'boolean',
        'online_enrollment_enabled' => 'boolean',
        'school_portal_maintenance' => 'boolean',
        'semester' => 'integer',
        'enrollment_courses' => 'array',
        'enable_signatures' => 'boolean',
        'enable_public_transactions' => 'boolean',
        'enable_qr_codes' => 'boolean',
        'enable_support_page' => 'boolean',
        'features' => 'array',
        'curriculum_year' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($settings) {
            self::clearCache();
        });
    }

    public static function clearCache()
    {
        Cache::forget('general_settings');
    }

    public function getSchoolYear(): string
    {
        return $this->getSchoolYearStarting().
            '-'.
            $this->getSchoolYearEnding();
    }

    public function getSchoolYearStarting(): string
    {
        return $this->school_starting_date->format('Y');
    }

    public function getSchoolYearEnding(): string
    {
        return $this->school_ending_date->format('Y');
    }

    public function getSchoolYearString(): string
    {
        return $this->getSchoolYearStarting().
            ' - '.
            $this->getSchoolYearEnding();
    }

    public function getSemester(): string
    {
        return match ($this->semester) {
            1 => '1st Semester',
            2 => '2nd Semester',
            default => '1st Semester',
        };
    }
}
