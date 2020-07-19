<html>
<head>
    <title>iSat.com.ua - Цена {{ $now }}</title>
    <meta charset="utf-8">
</head>
<body>
<table border="1" style="width: 100%">
    <tr>
        <td>Наименование</td>
        <td>Розница</td>
        @switch($customerGroupId)
            @case(2)
            <td>Диллер</td>
            @break
            @case(3)
            <td>ОПТ</td>
            @break
            @case(4)
            <td>Партнет</td>
            @break
        @endswitch
    </tr>
    @foreach($categories as $category)
        <tr>
            @if($customerGroupId > 1)
                <td colspan="3" style="text-align: center">
                    <a href="{{ $category['url'] }}">{{ $category['name'] }}</a>
                </td>
            @else
                <td colspan="2" style="text-align: center">
                    <a href="{{ $category['url'] }}">{{ $category['name'] }}</a>
                </td>
            @endif
        </tr>
        @foreach($category['products'] as $product)
            <tr>
                <td>
                    <a href="{{ $product['url'] }}">{{ $product['name'] }}</a>
                </td>
                <td>
                    {{ $currency['symbol_left'] }}
                    {{ round($product['retail'] * $currency['value'], $currency['decimal_place']) }}
                    {{ $currency['symbol_right'] }}
                </td>
                @switch($customerGroupId)
                    @case(2)
                    <td>
                        {{ $currency['symbol_left'] }}
                        {{ round($product['dealer'] * $currency['value'], $currency['decimal_place']) }}
                        {{ $currency['symbol_right'] }}
                    </td>
                    @break
                    @case(3)
                    <td>
                        {{ $currency['symbol_left'] }}
                        {{ round($product['wholesale'] * $currency['value'], $currency['decimal_place']) }}
                        {{ $currency['symbol_right'] }}
                    </td>
                    @break
                    @case(4)
                    <td>
                        {{ $currency['symbol_left'] }}
                        {{ round($product['partner'] * $currency['value'], $currency['decimal_place']) }}
                        {{ $currency['symbol_right'] }}
                    </td>
                    @break
                @endswitch
            </tr>
        @endforeach
    @endforeach
</table>
</body>
</html>

