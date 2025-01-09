<?php

namespace App\Models;

use App\Traits\{HasAddress, HasPhone};
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasOne};

/**
 * Class setting
 *
 * @property \App\Models\Employee $ceo
 * @method HasOne ceo()
 *
 * @property string $id
 * @property ?string $employee_id
 * @property ?string $name
 * @property ?string $cnpj
 * @property ?string $opened_in
 * @property string $zip_code
 * @property string $street
 * @property string $number
 * @property string $neighborhood
 * @property string $city
 * @property string $state
 * @property ?string $complement
 * @property ?string $about
 * @property ?string $phone
 * @property ?string $email
 * @property ?string $logo
 * @property ?string $favicon
 * @property ?string $x
 * @property ?string $instagram
 * @property ?string $facebook
 * @property ?string $linkedin
 * @property ?string $youtube
 * @property ?string $whatsapp
 * @property ?float $interest_rate_sale
 * @property ?float $interest_rate_installment
 * @property ?float $late_fee
 *
 * @property ?string $font_family
 * @property ?string $primary_color
 * @property ?string $secondary_color
 * @property ?string $tertiary_color
 * @property ?string $nav_color
 * @property ?string $nav_border_color
 * @property ?string $footer_color
 * @property ?string $body_bg_color
 * @property ?string $text_variant_color_1
 * @property ?string $text_variant_color_2
 * @property ?string $text_variant_color_3
 * @property ?string $text_variant_color_4
 * @property ?string $text_variant_color_5
 * @property ?string $text_variant_color_6
 * @property ?string $text_variant_color_7
 * @property ?string $text_variant_color_8
 * @property ?string $text_variant_color_9
 * @property ?string $text_variant_color_10
 * @property ?string $card_color
 * @property ?string $variant_color_1
 * @property ?string $variant_color_2
 * @property ?string $variant_color_3
 * @property ?string $variant_color_4
 * @property ?string $variant_color_5
 * @property ?string $variant_color_6
 * @property ?string $variant_color_7
 * @property ?string $variant_color_8
 * @property ?string $variant_color_9
 * @property ?string $variant_color_10
 * @property ?string $variant_color_11
 * @property ?string $bg_img
 * @property ?string $bg_img_opacity
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class Settings extends Model
{
    use HasFactory;
    use HasAddress;
    use HasUlids;
    use HasPhone;

    protected $table = 'settings';

    protected $fillable = [
        'employee_id',
        'name',
        'cnpj',
        'opened_in',
        'about',
        'email',
        'logo',
        'favicon',
        'x',
        'instagram',
        'facebook',
        'linkedin',
        'youtube',
        'whatsapp',

        'interest_rate_sale',
        'interest_rate_installment',
        'late_fee',

        'font_family',

        'primary_color',
        'secondary_color',
        'tertiary_color',

        'nav_color',
        'nav_border_color',

        'footer_color',

        'body_bg_color',

        'text_variant_color_1',
        'text_variant_color_2',
        'text_variant_color_3',
        'text_variant_color_4',
        'text_variant_color_5',
        'text_variant_color_6',
        'text_variant_color_7',
        'text_variant_color_8',
        'text_variant_color_9',
        'text_variant_color_10',

        'card_color',

        'variant_color_1',
        'variant_color_2',
        'variant_color_3',
        'variant_color_4',
        'variant_color_5',
        'variant_color_6',
        'variant_color_7',
        'variant_color_8',
        'variant_color_9',
        'variant_color_10',
        'variant_color_11',

        'bg_img',
        'bg_img_opacity',
    ];

    public function ceo(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
