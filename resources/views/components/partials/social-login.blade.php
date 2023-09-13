<a href="{{ $attributes['routeUrl'] }}">
    <button type="button" class="btn btn-block btn-outline-dark col-12 social-auth-links text-center mb-3 d-flex">
        <div class="w-100 row">
            <div class="image col-4">
                <img class="w-50" src="{{ $attributes['imgPath'] }}" alt="" srcset="">
            </div>
            <div class="info col-8 d-flex align-items-center">
                <span>
                    login with {{ $attributes['name'] }}
                </span>
            </div>
        </div>
    </button>
</a>
