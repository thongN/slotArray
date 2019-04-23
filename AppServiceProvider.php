<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Slot html data as an array
         * slotarray can be used with slot
         * 
         * usage:
         * @component('xxxx')
         *     @foreach($list as $item)
         *         @slotarray('arrayName')
         *             {{-- html code --}}
         *         @endslotarray
         *     @endforeach
         * @endcomponent
         * 
         * xxxx.blade.php
         * @foreach($arrayName as $html)
         *     {{-- html code --}}
         *         {{ $html }}
         *     {{-- html code --}}
         * @endforeach
         * 
         * e.g.)
         * @component('component.swiper')
         *     @foreach($banners as $banner)
         *         @slotarray('sliderElement')
         *             @include('component.swiper-item', ['item' => $banner])
         *         @endslotarray
         *     @endforeach
         * @endcomponent
         * 
         * component/swiper.blade.php
         * @foreach($sliderElement as $element)
         *     <div class="swiper-slider">
         *         {{ $element }}
         *     </div>
         * @endforeach
         */
        Blade::directive('slotarray', function ($name) {
            return "<?php 
            if (!isset(\$__componentSlotArray)) {
                \$__componentSlotArray = new \\stdclass();
            }
            \$__slotarray_name = {$name};
            if (!isset(\$__componentSlotArray->{\$__slotarray_name})) {
                \$__componentSlotArray->{\$__slotarray_name} = [];
            }
            ob_start(); 
            ?>";
        });
        Blade::directive('endslotarray', function () {
            return "<?php 
            \$__componentSlotArray->{\$__slotarray_name}[] = new HtmlString(ob_get_contents());
            ob_end_clean();
            \$__env->slot(\$__slotarray_name, \$__componentSlotArray->{\$__slotarray_name});
            unset(\$__slotarray_name);
            ?>";
        });

    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
