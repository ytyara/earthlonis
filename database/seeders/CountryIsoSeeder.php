<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountryIsoSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            'Afghanistan' => 'AF', 'Albania' => 'AL', 'Algeria' => 'DZ',
            'Argentina' => 'AR', 'Armenia' => 'AM', 'Australia' => 'AU',
            'Austria' => 'AT', 'Azerbaijan' => 'AZ', 'Bahrain' => 'BH',
            'Bangladesh' => 'BD', 'Belarus' => 'BY', 'Belgium' => 'BE',
            'Bolivia' => 'BO', 'Bosnia and Herzegovina' => 'BA', 'Brazil' => 'BR',
            'Bulgaria' => 'BG', 'Cambodia' => 'KH', 'Canada' => 'CA',
            'Chile' => 'CL', 'China' => 'CN', 'Colombia' => 'CO',
            'Croatia' => 'HR', 'Cuba' => 'CU', 'Czech Republic' => 'CZ',
            'Denmark' => 'DK', 'Ecuador' => 'EC', 'Egypt' => 'EG',
            'Estonia' => 'EE', 'Ethiopia' => 'ET', 'Finland' => 'FI',
            'France' => 'FR', 'Georgia' => 'GE', 'Germany' => 'DE',
            'Ghana' => 'GH', 'Greece' => 'GR', 'Guatemala' => 'GT',
            'Hungary' => 'HU', 'Iceland' => 'IS', 'India' => 'IN',
            'Indonesia' => 'ID', 'Iran' => 'IR', 'Iraq' => 'IQ',
            'Ireland' => 'IE', 'Israel' => 'IL', 'Italy' => 'IT',
            'Japan' => 'JP', 'Jordan' => 'JO', 'Kazakhstan' => 'KZ',
            'Kenya' => 'KE', 'Kuwait' => 'KW', 'Kyrgyzstan' => 'KG',
            'Latvia' => 'LV', 'Lebanon' => 'LB', 'Libya' => 'LY',
            'Lithuania' => 'LT', 'Luxembourg' => 'LU', 'Malaysia' => 'MY',
            'Mexico' => 'MX', 'Moldova' => 'MD', 'Mongolia' => 'MN',
            'Montenegro' => 'ME', 'Morocco' => 'MA', 'Myanmar' => 'MM',
            'Nepal' => 'NP', 'Netherlands' => 'NL', 'New Zealand' => 'NZ',
            'Nigeria' => 'NG', 'North Korea' => 'KP', 'North Macedonia' => 'MK',
            'Norway' => 'NO', 'Oman' => 'OM', 'Pakistan' => 'PK',
            'Palestine' => 'PS', 'Panama' => 'PA', 'Paraguay' => 'PY',
            'Peru' => 'PE', 'Philippines' => 'PH', 'Poland' => 'PL',
            'Portugal' => 'PT', 'Qatar' => 'QA', 'Romania' => 'RO',
            'Russia' => 'RU', 'Saudi Arabia' => 'SA', 'Serbia' => 'RS',
            'Singapore' => 'SG', 'Slovakia' => 'SK', 'Slovenia' => 'SI',
            'South Africa' => 'ZA', 'South Korea' => 'KR', 'Spain' => 'ES',
            'Sri Lanka' => 'LK', 'Sweden' => 'SE', 'Switzerland' => 'CH',
            'Syria' => 'SY', 'Taiwan' => 'TW', 'Tajikistan' => 'TJ',
            'Tanzania' => 'TZ', 'Thailand' => 'TH', 'Tunisia' => 'TN',
            'Turkey' => 'TR', 'Turkmenistan' => 'TM', 'Uganda' => 'UG',
            'Ukraine' => 'UA', 'United Arab Emirates' => 'AE',
            'United Kingdom' => 'GB', 'United States' => 'US',
            'Uruguay' => 'UY', 'Uzbekistan' => 'UZ', 'Venezuela' => 'VE',
            'Vietnam' => 'VN', 'Yemen' => 'YE', 'Zimbabwe' => 'ZW',
        ];

        foreach ($codes as $name => $code) {
            Country::where('name', $name)->update(['iso_code' => $code]);
        }

        $this->command->info('ISO codes updated!');
    }
}
