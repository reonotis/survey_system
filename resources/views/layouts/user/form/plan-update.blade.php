
<div class="flex-center-center my-4">
    <form method="POST" action="{{ route('user_form_billing_checkout', ['form_setting' => $form_setting->id]) }}">
        @csrf
        <input type="submit"
               class="btn plan-upgrade"
               value="このフォームのプランをアップグレードする" >
    </form>
</div>
