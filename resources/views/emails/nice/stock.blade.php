@component('mail::message')

    @foreach($stocks as $stock)
        股票：{{ $stock->name }} 代码：{{ $stock->code }} <br />
    @endforeach

@endcomponent
