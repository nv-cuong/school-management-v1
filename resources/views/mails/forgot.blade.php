@component('mail::message')
    Xin chào {{ $user->name }},
    <p>We understand it happens.</p>

    @component('mail::button', ['url' => url('reset/'.$user->remember_token)])
        Thay đổi mật khẩu
    @endcomponent

    <p>Trong trường hợp bạn gặp bất kỳ sự cố nào khi khôi phục mật khẩu, vui lòng liên hệ với chúng tôi.</p>

    Cảm ơn bạn, <br>
    {{ config('app.name') }}
@endcomponent