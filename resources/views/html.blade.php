<html>
<head>
    <title>iSat.com.ua - Цена {{ $now }}</title>
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
                <td>{{ $product['retail'] }}</td>
                @switch($customerGroupId)
                    @case(2)
                    <td>{{ $product['dealer'] }}</td>
                    @break
                    @case(3)
                    <td>{{ $product['wholesale'] }}</td>
                    @break
                    @case(4)
                    <td>{{ $product['partner'] }}</td>
                    @break
                @endswitch
            </tr>
        @endforeach
    @endforeach
</table>
</body>
</html>

