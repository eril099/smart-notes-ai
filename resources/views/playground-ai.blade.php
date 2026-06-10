<form method="GET" action="">
    <textarea name="prompt" rows="6" id=""></textarea>
    <button>Kirim</button>
</form>

@if(isset($response))
    {{ $response }}
@endif